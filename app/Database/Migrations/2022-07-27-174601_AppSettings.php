<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Libraries\MigrationUtils;

class AppSettings extends Migration
{

    protected $table_name = TBL_APP_SETTINGS;

    public function up()
    {
        // Campos de la tabla settings
        $fields = [
            'id_settings'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36
            ],
            'app_name'           => [
                'type'           => 'VARCHAR',
                'constraint'     => '100'
            ],
            'default_tax'        => [
                'type'           => 'DECIMAL',
                'constraint'     => '11,4'
            ],
            'default_currency'   => [
                'type'           => 'VARCHAR',
                'constraint'     => '2'
            ],
            'primary_color'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_primary_color'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'primary_color_variant' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_primary_color_variant' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'secondary_color'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_secondary_color'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'secondary_color_variant' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_secondary_color_variant' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'background_color' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_background_color'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'surface_color' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_surface_color'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'error_color' => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'dark_error_color'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '7'
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP',
            'deleted_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true
            ],
            'deleted_at' => [
                'type'   => 'DATETIME',
                'null'   => true
            ],
        ];

        $this->forge->addField($fields);  // Se agregan los campos de la tabla
        $this->forge->addKey('id_settings', true); // Se define la llave primaria
        $this->forge->addForeignKey('created_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('updated_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('deleted_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->table_name); // Se crea la tabla

        // Creamos el triger para usar unique id
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, 'id_settings');
        $id_user = $migrationUtils->getFirstUserId();

        // Isertamos el primer registro con las configuraciónes iniciales
        $this->db->table($this->table_name)->insert([
            'app_name'                     => 'BASE APP',
            'default_tax'                  => 0.13,
            'default_currency'             => '$',
            'primary_color'                => '#6200EE',
            'dark_primary_color'           => '#6200EE',
            'primary_color_variant'        => '#3700B3',
            'dark_primary_color_variant'   => '#3700B3',
            'secondary_color'              => '#03DAC6',
            'dark_secondary_color'         => '#03DAC6',
            'secondary_color_variant'      => '#018786',
            'dark_secondary_color_variant' => '#018786',
            'background_color'             => '#FAFAFA',
            'dark_background_color'        => '#FAFAFA',
            'surface_color'                => '#ffffff',
            'dark_surface_color'           => '#ffffff',
            'error_color'                  => '#B00020',
            'dark_error_color'             => '#B00020',
            'created_by'                   => $id_user,
            'updated_by'                   => $id_user,
            'deleted_by'                   => null,
        ]);
    }

    public function down()
    {
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);
    }
}
