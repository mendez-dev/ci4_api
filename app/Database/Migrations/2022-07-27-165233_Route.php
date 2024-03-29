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

class Route extends Migration
{
    protected $table_name = TBL_ROUTE;

    public function up()
    {
        $fields = [
            'id_route'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
            ],
            'id_parent_route'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
                'null'           => true
            ],
            'label'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50
            ],
            'name'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50
            ],
            'path'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 100
            ],
            'icon'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50
            ],
            'priority' => [
                'type'           => 'INT',
                'constraint'     => '11',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['ALL', 'WEB', 'MOBILE'],
                'default'    => 'ALL',
            ],
            'show_in_menu' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => '0 = false, 1 = true ',
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
            ]
        ];

        $this->forge->addField($fields);  // Se agregan los campos de la tabla
        $this->forge->addKey('id_route', true); // Se define la llave primaria
        $this->forge->addForeignKey('created_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('updated_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('deleted_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_parent_route', TBL_ROUTE, 'id_route', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->table_name); // Se crea la tabla

        // Creamos el triger para usar unique id
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, 'id_route');
    }

    public function down()
    {
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);
    }
}
