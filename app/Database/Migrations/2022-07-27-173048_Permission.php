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

class Permission extends Migration
{
    protected $table_name = TBL_PERMISSION;

    public function up()
    {
        $fields = [
            'id_permission'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
            ],
            'id_menu'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 36
            ],
            'name'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'unique'         => true
            ],
            'label'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50
            ],
            'description'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50
            ],
            'icon'        => [
                'type'           => 'VARCHAR',
                'constraint'     => 50
            ],
            'depends_on' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['ALL', 'WEB', 'MOBILE'],
                'default'    => 'ALL',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
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
        $this->forge->addKey('id_permission', true); // Se define la llave primaria
        $this->forge->addForeignKey('id_menu', TBL_MENU, 'id_menu', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('created_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('updated_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('deleted_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('depends_on', $this->table_name, 'id_permission', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->table_name); // Se crea la tabla

        // Creamos el triger para usar unique id
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, 'id_permission');
    }

    public function down()
    {
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);
    }
}
