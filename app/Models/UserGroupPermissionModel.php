<?php

/**
 * This file is part of the SYSTEM_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
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
     * Validaciones
     *
     */

    //  Verificar que el id_user_group exista en la tabla app_user_group y que este activo
    protected $validationRules = [
        'id_user_group' => [
            'label' => 'grupo de usuario',
            'rules' => 'required|is_not_unique[' . TBL_GROUP . '.id_user_group,is_active,1]',
        ],
        'id_permission' => [
            'label' => 'permiso',
            'rules' => 'required|is_not_unique[' . TBL_PERMISSION . '.id_permission,is_active,1]',
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
}
