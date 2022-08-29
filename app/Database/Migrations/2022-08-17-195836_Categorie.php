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

class Categorie extends Migration
{
    protected $table_name = TBL_CATEGORIE;

    public function up()
    {
        // Campos de la tabla settings
        $fields = [
            'id_categorie'       => [
                'type'           => 'VARCHAR',
                'constraint'     => 36
            ],
            'name'               => [
                'type'           => 'VARCHAR',
                'constraint'     => '100'
            ],
            'description'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '100'
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
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_categorie', true);
        $this->forge->createTable($this->table_name);
    }

    public function down()
    {
        //
    }
}
