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

class RoutePermissions extends Migration
{
    protected $table_name = TBL_ROUTE_PERMISSION;

    public function up()
    {
        $fields = [
            'id_route_permission'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
            ],
            'id_route'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
            ],
            'id_permission'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
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

        $this->forge->addField($fields);
        $this->forge->addKey('id_route_permission', true);
        $this->forge->addForeignKey('id_route', TBL_ROUTE, 'id_route', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_permission', TBL_PERMISSION, 'id_permission', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('updated_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('deleted_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->table_name);



        // Creamos los triggers para usar unique id
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, 'id_route_permission');
    }

    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
