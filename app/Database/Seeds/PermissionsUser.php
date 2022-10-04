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
            [
                "name"        => "USER_GROUP_READ",
                "label"       => "Ver grupos de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite ver los usuarios registrados en el sistema.",
                "icon"        => FA_EYE,
                "is_active"   => "1",
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
        ];

        // Insertamos los permisos principales
        $this->insertBatchIfNotExists(TBL_PERMISSION, $main_permissions, "name");

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
            [
                "name"        => "USER_GROUP_CREATE",
                "label"       => "Crear grupo de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite registrar nuevos grupos de usuarios en el sistema.",
                "icon"        => FA_CIRCLE_PLUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_GROUP_UPDATE",
                "label"       => "Actualizar grupo de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite actualizar datos de los grupos de usuarios en el sistema.",
                "icon"        => FA_PEN_CIRCLE,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_GROUP_DELETE",
                "label"       => "Eliminar grupo de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite borrar grupos de usuarios del sistema.",
                "icon"        => FA_CIRCLE_TRASH,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_GROUP_ENABLE",
                "label"       => "Activar grupo de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite activar grupos de usuarios que se encuentren desactivados.",
                "icon"        => FA_CIRCLE_CHECK,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_GROUP_DISABLE",
                "label"       => "Desactivar grupo de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite desactivar grupos de usuarios que se encuentren activos.",
                "icon"        => FA_CIRCLE_MINUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "name"        => "USER_GROUP_PERMISSION",
                "label"       => "Asignar permisos a grupos de usuarios",
                "group_tag"   => "Gupos de Usuarios",
                "description" => "Permite asignar permisos a los grupos de usuarios del sistema.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],

        ];

        // Insertamos los permisos hijos
        $this->insertBatchIfNotExists(TBL_PERMISSION, $permissions, "name");
    }
}
