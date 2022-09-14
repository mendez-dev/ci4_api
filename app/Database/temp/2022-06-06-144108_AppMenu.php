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

class AppMenu extends Migration
{
    protected $table_name = "app_menu";

    public function up()
    {
        $fields = [
            'id_menu'        => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => true
            ],
            'label'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '50'
            ],
            'icon'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '50'
            ],
            'route'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '100'
            ],
            'priority' => [
                'type'           => 'INT',
                'constraint'     => '11',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => '0 = false, 1 = true ',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP',
            'deleted_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true
            ],
            'deleted_at' => [
                'type'   => 'DATETIME',
                'null'   => true
            ]
        ];

        $this->forge->addField($fields);  // Se agregan los campos de la tabla
        $this->forge->addKey('id_menu', true); // Se define la llave primaria
        $this->forge->addForeignKey('created_by', 'app_user', 'id_app_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('updated_by', 'app_user', 'id_app_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('deleted_by', 'app_user', 'id_app_user', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->table_name); // Se crea la tabla
    }

    public function down()
    {
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);
    }
}
