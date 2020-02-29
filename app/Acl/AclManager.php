<?php

namespace App\Acl;

use App\Module;
use App\User;
use App\Profile;

class AclManager {

    public $name = '';
    public $display_name = '';
    protected $admin_only = false;
    protected $general_actions = [];
    protected $specific_actions = [];
    protected $levels = [];
    protected $lock_actions = [];
    protected $active = false;
    protected $module = null;
    static private $mapped_level_actions = [
        'global' => 'checkGlobalPermission',
        'owner' => 'checkOwnerPermission',
        'relationship' => 'checkRelationshipPermission',
        'user_relationship' => 'checkUserRelationshipPermission',
        'column' => 'checkColumnPermission',
        'user' => 'checkUserPermission',
    ];
    protected $level_translation;
    protected $modelless_actions = [
        'list',
        'create',
    ];

    public function __construct(string $model_name)
    {
        $module = config('acl.modules')[$model_name];
        $this->name = $module['name'];
        $this->display_name = $module['display_name'];
        $this->admin_only = $module['admin_only'];
        $this->levels = $module['levels'];
        $this->lock_actions = $module['lock_actions'];
        $this->specific_actions = $module['specific_actions'];
        $this->active = $module['active'];
        $this->general_actions = config('acl.general_actions');
        $this->module = Module::where('class_name', $model_name)->first();
        $this->level_translation = config('acl.level_translation');
    }

    ############################################################################
    ################################ GETTERS ###################################
    ############################################################################

    /**
     * Get all modules
     * @return array App\Acl\AclManager
     */
    public static function allModules()
    {
        $modules = [];
        foreach (config('acl.modules') as $model_name => $module) {
            $modules[] = new self($model_name);
        }
        return $modules;
    }

    /**
     * Get the module levels
     * @return array
     */
    public function getLevels()
    {
        $levels = [];
        foreach ($this->levels as $level => $value) {
            if ($value && $this->isLevelEnabled($level)) {
                $levels[] = $level;
            }
        }
        return $levels;
    }

    /**
     * Get the module level name translate
     * @param string $level_name
     * @return array
     */
    public function getLevelTranslated($level_name)
    {
        if (isset($this->level_translation[$level_name])) {
            return $this->level_translation[$level_name];
        } else {
            return $level_name;
        }
    }

    /**
     * Get the module actions
     * @return array
     */
    public function getActions()
    {
        return array_merge(
            $this->general_actions,
            $this->specific_actions
        );
    }

    /**
     * Get the module actions ready to put on database
     * @return array
     */
    public function getDatabaseFormatedActions()
    {
        $actions = $this->getActions();
        $levels = $this->getLevels();

        $formated_actions = [];

        foreach ($levels as $level) {
            foreach ($actions as $action) {
                $formated_actions[$level][] = "$level.$action";
            }
        }

        return $formated_actions;
    }

    /**
     * Get the action to a given level type
     * @param string level_name
     * @return string action name
     */
    public static function getMappedAction(string $level_name)
    {
        return self::$mapped_level_actions[$level_name] ?? 'error';
    }

    ############################################################################
    ############################# GENERAL CHECKERS #############################
    ############################################################################

    /**
     * Check if authorization level is enable by the acl system
     * @param string $level_name
     * @return boolean
     */
    public static function isLevelEnabled(string $level_name)
    {
        $enabled_levels = config('acl.enabled_levels');
        return in_array($level_name, $enabled_levels) && $enabled_levels[$level_name] === true;
    }

    /**
     * Check if its an only admin modules
     *
     * @return boolean
     */
    public function isAdminOnly()
    {
        return $this->admin_only;
    }

    /**
     * Check if module has a permission (permissions table)
     * @param string $permission_name
     * @return boolean
     */
    public function hasPermission(string $permission_name)
    {
        return $this->module
            ->permissions()
            ->where('name', $permission_name)
            ->count() >= 1;
    }

    /**
     * Check if a given profile have some given permission
     * @param string $permission_name
     * @return boolean
     */
    public function profileHasPermission(Profile $profile, string $db_action_name)
    {
        return $profile->permissions()
            ->where('module_id', $this->module->id)
            ->where('name', $db_action_name)
            ->count() >= 1;
    }

    /**
     * Check if it is necessary a model instance for the given gate rule
     * @param string $action
     * @return boolean
     */
    public static function isModelNecessary(string $action)
    {
        return !in_array($action, $this->modelless_actions);
    }

    ############################################################################
    ############################ LEVEL CHECKERS ################################
    ############################################################################

    /**
     * Check if an action can be performed by and user on a given module/model
     * @param User $user
     * @param Illuminate\Database\Eloquent\Model | null $model
     * @return boolean
     */
    public function checkGlobalPermission(User $user, $model = null, $db_action_name = '')
    {
        foreach ($user->profiles as $profile) {
            if ($this->profileHasPermission($profile, $db_action_name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if an action can be performed by and user on a given module/model
     * @param User $user
     * @param Illuminate\Database\Eloquent\Model | null $model
     * @return boolean
     */
    public function checkOwnerPermission(User $user, $model = null)
    {
        if ($model === null) {
            return false;
        }
        $model = clone $model;
        $model_foreign = $this->levels['owner'];
        return $user->id === $model->$model_foreign;
    }

    /**
     * Check if an action can be performed by and user on a given module/model
     * @param User $user
     * @param Illuminate\Database\Eloquent\Model | null $model
     * @return boolean
     */
    public function checkRelationshipPermission(User $user, $model = null)
    {
        if (!$model) {
            return false;
        }

        $ways = $this->levels['relationship'];
        $model = clone $model;
        foreach ($ways as $way) {
            $path = $way['path'];
            $attribute = end($path);
            $expected_value = $way['expected_value'];
            $contains = $way['contains'];


            foreach ($path as $relation) {
                if ($relation == $attribute) {
                    $result = $model->$relation;
                } else {
                    $result = $model->$relation();
                }
            }

            if ($contains) {
                if ($result->contains($user)) {
                    return true;
                }
            } else {
                if ($result == $user->$expected_value) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if an action can be performed by and user on a given module/model
     * @param User $user
     * @param Illuminate\Database\Eloquent\Model | null $model
     * @return boolean
     */
    public function checkUserRelationshipPermission(User $user, $model = null)
    {
        $ways = $this->levels['user_relationship'];
        foreach ($ways as $way) {
            $path = $way['path'];
            $attribute = end($path);
            $expected_value = $way['expected_value'];
            $contains = $way['contains'];

            foreach ($path as $relation) {
                if ($relation == $attribute) {
                    $result = $user->$relation;
                } else {
                    $result = $user->$relation();
                }
            }

            if ($contains && $model) {
                if ($result->contains($model)) {
                    return true;
                }
            } else {
                if ($result === $expected_value) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if an action can be performed by and user on a given module/model
     * @param User $user
     * @param Illuminate\Database\Eloquent\Model | null $model
     * @return boolean
     */
    public function checkColumnPermission(User $user, $model = null)
    {
        $ways = $this->levels['column'];
        foreach ($ways as $way) {
            $column = $way[0];
            $op = $way[1];
            $value = $way[2];

            switch ($op) {
                case '==':
                    return $model->$column == $value;
                break;
                case '===':
                    return $model->$column === $value;
                break;
                case '>':
                    return $model->$column > $value;
                break;
                case '<':
                    return $model->$column < $value;
                break;
                case '>=':
                    return $model->$column >= $value;
                break;
                case '<=':
                    return $model->$column <= $value;
                break;
            }

        }
        return true;
    }

    /**
     * Check if an action can be performed by an user on a given module/model
     * @param User $user
     * @param Illuminate\Database\Eloquent\Model | null $model
     * @return boolean
     */
    public function checkUserPermission(User $user, $model = null)
    {
        // TODO: CHECK THE VALIDATION METHOD
        return true;
    }

    /**
     * Return an error
     * @return string
     */
    public function error()
    {
        return false;
    }

}
