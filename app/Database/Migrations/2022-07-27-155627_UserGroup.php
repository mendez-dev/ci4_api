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
use App\Libraries\MigrationUtils;

class UserGroup extends Migration
{
    protected $table_name  = TBL_GROUP;

    public function up()
    {
        $fiels = [
            'id_user_group' => [
                'type'           => 'VARCHAR',
                'constraint'     => 36,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '250'
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => '0 = false, 1 = true ',
            ],
            'created_by' => array(
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ),
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_by' => array(
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ),
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP',
            'deleted_by' => array(
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true
            ),
            'deleted_at' => array(
                'type'   => 'DATETIME',
                'null'   => true
            )
        ];

        $this->forge->addField($fiels);

        // Agregamos llave primaria
        $this->forge->addKey('id_user_group', true);

        // Agregamos las relaciones
        $this->forge->addForeignKey('created_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('updated_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('deleted_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');

        // Creamos la tabla
        $this->forge->createTable($this->table_name);

        // Creamos el triger para usar unique id
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, 'id_user_group');
        $id_user = $migrationUtils->getFirstUserId();

        // Insertamos el primer registro
        $this->db->table($this->table_name)->insert([
            'name'        => 'SUPER ADMINISTRADOR',
            'description' => 'ADMINISTRADOR CON TODOS LOS PRIVILEGIOS DEL SISTEMA',
            'is_active'   => 1,
            'created_by'  => $id_user,
            'updated_by'  => $id_user
        ]);

        // Obtenemos el id del grupo creado, para agregar nuestro usuario administrador al grupo
        $first_user    = $this->db->table($this->table_name)->orderBy("created_by", 'ASC')->get()->getRow();
        $id_user_group = $first_user->id_user_group;

        // Actualizamos del usuario agregandolo al grupo super administrador
        $this->db->table(TBL_USER)->update([
            'id_user_group' => $id_user_group,
        ], ['id_user' => $id_user]);

        // Agregamos la llave foranea
        $this->db->query("ALTER TABLE `" . TBL_USER . "` ADD CONSTRAINT `fk_user_group` 
        FOREIGN KEY(`id_user_group`) REFERENCES $this->table_name(`id_user_group`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;");

        // Modificamos el campo id_user_group en la tabla de usuarios para que ya no admita valores nulos
        $fields = [
            'id_user_group' => [
                'type'        => 'VARCHAR',
                'null'        => false,
                'constraint'  => 36,
            ],
        ];
        $this->forge->modifyColumn(TBL_USER, $fields);
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `" . TBL_USER . "` DROP FOREIGN KEY `fk_user_group`;");
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);
    }
}
