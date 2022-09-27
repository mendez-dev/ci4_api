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

use App\Entities\Group;
use App\Models\CustomModel;

/**
 * Modelo `GroupModel`
 *
 * Administra la interaccion con la base de datos de la tabla app_menu
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class GroupModel extends CustomModel
{
    protected $table            = TBL_GROUP;
    protected $primaryKey       = 'id_user_group';
    protected $returnType       = Group::class;
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $protectFields    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $allowedFields    = [
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $validationRules  = [
        'name' => [
            'label' => 'nombre',
            'rules' => 'required|max_length[50]|is_unique[' . TBL_GROUP . '.name,id_user_group,{id_user_group}]'
        ],
        'description' => [
            'label' => 'descripcion',
            'rules' => 'required|max_length[100]'
        ],
        'is_active' => [
            'label' => 'activo',
            'rules' => 'required|in_list[0,1]'
        ],
    ];


    public function deletePermissions($id_user_group, $id_user)
    {
        // Eliminar permisos usando soft delete
        $this->db->table(TBL_USER_GROUP_PERMISSION)
            ->where('id_user_group', $id_user_group)
            ->set('deleted_by', $id_user)
            ->set('deleted_at', date('Y-m-d H:i:s'))
            ->update();
    }

    /**
     * Eliminar los permisos de un grupo que no esten en la lista de permisos
     */
    public function deletePermissionsNotIn($id_user_group, $id_user, $permissions)
    {
        // Eliminar permisos usando soft delete
        $this->db->table(TBL_USER_GROUP_PERMISSION)
            ->where('id_user_group', $id_user_group)
            ->whereNotIn('id_permission', $permissions)
            ->set('deleted_by', $id_user)
            ->set('deleted_at', date('Y-m-d H:i:s'))
            ->update();
    }


    public function assignPermission($id_user_group, $id_permission, $id_user)
    {
        $user_group_permission = new UserGroupPermissionModel();
        // Insertar permisos y si falla devolvemos los errores
        if (!$user_group_permission->insert([
            'id_user_group' => $id_user_group,
            'id_permission' => $id_permission,
            'created_by' => $id_user,
            'updated_by' => $id_user
        ])) {
            return $user_group_permission->errors();
        }
    }
}
