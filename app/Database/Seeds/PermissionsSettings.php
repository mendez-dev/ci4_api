<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;
use App\Libraries\MigrationUtils;

class PermissionsSettings extends CustomSeeder
{
    public function run()
    {
        // Obtenemos el id del primer usuario registrado
        $migrationUtils = new MigrationUtils();
        $id_user = $migrationUtils->getFirstUserId();


        // Definimos los permisos principales o padres
        $main_permissions = [
            [
                "name"        => "APP_SETTINGS_READ",
                "label"       => "Ver configuración de la aplicación",
                "group_tag"   => "Configuración",
                "description" => "Permite ver los ajustes de la aplicación",
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
                "name"        => "APP_SETTINGS_UPDATE",
                "label"       => "Actualizar la configuración de la aplicación",
                "group_tag"   => "Configuración",
                "description" => "Permite cambiar la configuración de la aplicación.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("APP_SETTINGS_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
        ];

        // Insertamos los permisos hijos
        $this->insertBatchIfNotExists(TBL_PERMISSION, $permissions, "name");
    }
}
