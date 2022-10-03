<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;
use App\Libraries\MigrationUtils;

class PermissionsUser extends CustomSeeder
{
    public function run()
    {
        // Obtenemos el id del primer usuario registrado
        $migrationUtils = new MigrationUtils();
        $id_user = $migrationUtils->getFirstUserId();

        // Definimos los builders que utilizaremosF
        $permissionsBuilder     = $this->db->table(TBL_PERMISSION);



        // Definimos los permisos principales o padres
        $main_permissions = [
            [
                "name"        => "USER_READ",
                "label"       => "Ver usuarios",
                "group_tag"   => "Usuarios",
                "description" => "Permite ver los usuarios registrados en el sistema.",
                "icon"        => FA_EYE,
                "is_active"   => "1",
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
        ];

        // Insertamos los permisos principales
        $permissionsBuilder->insertBatch($main_permissions);

        // Definimos los permisos hijos
        $permissions = [
            [
                "name"        => "USER_CREATE",
                "label"       => "Crear usuario",
                "group_tag"   => "Usuarios",
                "description" => "Permite registrar nuevos usuarios en el sistema.",
                "icon"        => FA_CIRCLE_PLUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_UPDATE",
                "label"       => "Actualizar usuario",
                "group_tag"   => "Usuarios",
                "description" => "Permite actualizar datos de los usuarios en el sistema.",
                "icon"        => FA_PEN_CIRCLE,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_DELETE",
                "label"       => "Eliminar usuario",
                "group_tag"   => "Usuarios",
                "description" => "Permite borrar usuarios del sistema.",
                "icon"        => FA_CIRCLE_TRASH,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_ENABLE",
                "label"       => "Activar usuario",
                "group_tag"   => "Usuarios",
                "description" => "Permite activar usuarios que se encuentren desactivados.",
                "icon"        => FA_CIRCLE_CHECK,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_DISABLE",
                "label"       => "Desactivar usuario",
                "group_tag"   => "Usuarios",
                "description" => "Permite desactivar usuarios que se encuentren activos.",
                "icon"        => FA_CIRCLE_MINUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_PASSWORD",
                "label"       => "Cambiar contraseñas",
                "group_tag"   => "Usuarios",
                "description" => "Permite cambiar las contraseñas de todos los usuarios del sistema.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
        ];

        // Insertamos los permisos hijos
        $permissionsBuilder->insertBatch($permissions);
    }
}
