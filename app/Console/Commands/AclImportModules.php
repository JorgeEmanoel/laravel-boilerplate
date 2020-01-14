<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Module;
use App\Permission;
use App\Acl\AclManager;

class AclImportModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:import-modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the modules within the acl.php config file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modules = config('acl.modules');
        $general_actions = config('acl.general_actions');
        $count = 0;

        $this->info('Importing ' . count($modules) . ' modules.');

        foreach ($modules as $model_name => $local_module) {
            $module = Module::where('class_name', $model_name)->first();

            if (!$module) {
                $module = new Module;
                $this->info("Module '$model_name' not found on database. Will be created.");
            } else {
                $this->info("Module '$model_name' found on database. Updating info.");
            }

            $module->name = $local_module['name'];
            $module->display_name = $local_module['display_name'];
            $module->class_name = $model_name;
            $module->active = $local_module['active'];
            $module->admin_only = $local_module['admin_only'];
            $module->save();

            $manager = new AclManager($model_name);
            $permissions = $manager->getDatabaseFormatedActions();

            $this->info("Importing " . count($permissions) . " permissions for module $model_name");
            foreach ($permissions as $level_name => $level_permissions) {
                $this->info($level_name);
                foreach ($level_permissions as $name) {
                    $permission = new Permission;
                    $permission->name = $name;
                    $permission->module_id = $module->id;
                    $permission->save();
                }
            }
            $this->info("All permisisons successfully imported.");

            $count++;

        }

        $this->info($count . ' modules successfully imported.');
    }
}
