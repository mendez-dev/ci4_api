<?php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AppGroups extends Migration
{
    protected $table_name  = 'app_group';

    public function up()
    {
        $fiels = [
            'id_app_group' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '250'
            ],
            'created_by' => array(
                'type'       => 'INT',
                'constraint' => 11,
            ),
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_by' => array(
                'type'       => 'INT',
                'constraint' => 11,
            ),
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP',
            'deleted_by' => array(
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true
            ),
            'deleted_at' => array(
                'type'   => 'DATETIME',
                'null'   => true
            )
        ];

        $this->forge->addField($fiels);

        // Agregamos llave primaria
        $this->forge->addKey('id_app_group', true);

        // Creamos la tabla
        $this->forge->createTable($this->table_name);

        // Insertamos el primer registro
        $this->db->table($this->table_name)->insert([
            'name'        => 'SUPER ADMINISTRADOR',
            'description' => 'ADMINISTRADOR CON TODOS LOS PRIVILEGIOS DEL SISTEMA',
            'created_by'  => 1,
            'updated_by'  => 1
        ]);
    }

    public function down()
    {
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);
    }
}
