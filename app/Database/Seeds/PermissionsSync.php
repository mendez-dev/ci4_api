<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;
use App\Libraries\MigrationUtils;

class PermissionsSync extends CustomSeeder
{
    public function run()
    {
        // Obtenemos el id del primer usuario registrado
        $migrationUtils = new MigrationUtils();
        $id_user = $migrationUtils->getFirstUserId();

        // Definimos los permisos principales o padres
        $main_permissions = [
            [
                "name"        => "SYNC",
                "label"       => "Sincronización de datos con el servidor",
                "group_tag"   => "Sincronización",
                "description" => "Permite descargar los datos del servidor y subir los datos locales al servidor.",
                "icon"        => FA_EYE,
                "is_active"   => "1",
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ]
        ];

        // Insertamos los permisos principales
        $this->insertBatchIfNotExists(TBL_PERMISSION, $main_permissions, "name");
    }
}