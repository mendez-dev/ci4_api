<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;
use App\Libraries\MigrationUtils;

class AssignPermissions extends CustomSeeder
{
    public function run()
    {
        // Asignación de permisos
        $migrationUtils = new MigrationUtils();
        $id_user = $migrationUtils->getFirstUserId();
        /**
         * @var UserGroupModel
         */
        $super_admin_group = $migrationUtils->getFirstRow(TBL_GROUP);
        $id_super_admin_group = $super_admin_group->id_user_group;

        // Definimos los builders que utilizaremos
        $permissionsBuilder     = $this->db->table(TBL_PERMISSION);
        $groupPermissionBuilder = $this->db->table(TBL_USER_GROUP_PERMISSION);

        // Asignamos todos los permisos al grupo super administrador si no los tiene asignados
        $all_permissions = $permissionsBuilder->get()->getResult();
        foreach ($all_permissions as $permission) {

            // Verificamos si el permiso ya está asignado al grupo
            $group_permission = $groupPermissionBuilder
                ->where("id_user_group", $id_super_admin_group)
                ->where("id_permission", $permission->id_permission)
                ->get()->getRow();

            // Si no está asignado, lo asignamos
            if (empty($group_permission)) {
                $groupPermissionBuilder->insert([
                    "id_user_group" => $id_super_admin_group,
                    "id_permission" => $permission->id_permission,
                    "created_by"    => $id_user,
                    "updated_by"    => $id_user,
                ]);
            }
        }
    }
}
