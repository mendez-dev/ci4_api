<?php

/**
 * This file is part of the SYSTEM_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Models;

use App\Entities\UserGroupPermission;
use App\Models\CustomModel;

/**
 * Modelo `UserGroupPermission`
 *
 * @package  SYSTEM_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class UserGroupPermissionModel extends CustomModel
{

    /**
     * --------------------------------------------------------------------
     * Configurar parámetros del modelo
     *
     */
    protected $table          = TBL_USER_GROUP_PERMISSION;
    protected $primaryKey     = 'id_user_group_permission';
    protected $returnType     = UserGroupPermission::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_user_group',
        'id_permission',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * --------------------------------------------------------------------
     * Relaciones
     *
     */
    protected $has_one = [
        'user_group' => [
            'App\Models\UserGroupModel',
            'id_user_group',
            'id_user_group'
        ],
        'permission' => [
            'App\Models\PermissionModel',
            'id_permission',
            'id_permission'
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Funciones
     *
     */
    public function getPermissionByUserGroup($id_user_group)
    {
        $this->where('id_user_group', $id_user_group);
        $this->orderBy('created_at', 'ASC');
        return $this->findAll();
    }

    // public function getPermissionByUserGroupAndMenu($id_user_group, $id_menu)
    // {
    //     $this->select('id_permission');
    //     $this->where('id_user_group', $id_user_group);
    //     $this->where('id_menu', $id_menu);
    //     $this->where('is_active', 1);
    //     $this->where('deleted_at', null);
    //     $this->orderBy('id_permission', 'ASC');
    //     return $this->findAll();
    // }

    // public function getPermissionByUserGroupAndMenuAndType($id_user_group, $id_menu, $type)
    // {
    //     $this->select('id_permission');
    //     $this->where('id_user_group', $id_user_group);
    //     $this->where('id_menu', $id_menu);
    //     $this->where('type', $type);
    //     $this->where('is_active', 1);
    //     $this->where('deleted_at', null);
    //     $this->orderBy('id_permission', 'ASC');
    //     return $this->findAll();
    // }
}
