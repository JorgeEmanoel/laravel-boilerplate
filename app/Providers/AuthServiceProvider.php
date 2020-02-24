<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Module;
use App\Acl\AclManager;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        // DEFINE GATES FOR ACL MANAGER

        if (Schema::hasTable('modules')) {
            $modules = AclManager::allModules();

            foreach ($modules as $module) {
                foreach ($module->getActions() as $action_name) {
                    $gate = "{$module->name}.$action_name";
                    Gate::define($gate, function ($user, $model = null) use ($action_name, $module) {
                        foreach ($module->getLevels() as $level_name) {
                            $db_action_name = "$level_name.$action_name";

                            if ($module->isAdminOnly()) {
                                return false;
                            }

                            if (!$module->hasPermission($db_action_name)) {
                                return false;
                            }

                            if (!$user->profiles->count()) {
                                return false;
                            }

                            $return = false;
                            foreach ($user->profiles as $profile) {
                                if ($module->profileHasPermission($profile, $db_action_name)) {
                                    $return = true;
                                }
                            }

                            if ($return) {
                                $action = AclManager::getMappedAction($level_name);
                                if ($module->$action($user, $model, $db_action_name)) {
                                    return true;
                                }
                            }

                        }

                        return false;
                    });
                }
            }
        }
    }
}
