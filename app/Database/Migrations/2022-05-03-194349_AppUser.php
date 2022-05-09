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

class AppUser extends Migration
{
    protected $table_name = "app_user";

    public function up()
    {
        // Campos de la tabla settings
        $fields = [
            'id_app_user'        => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true
            ],
            'id_group'           => [
                'type'           => 'INT',
            ],
            'firstname'           => [
                'type'           => 'VARCHAR',
                'constraint'     => '100'
            ],
            'lastname'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '100'
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'comment'    => 'SHA512',
            ],
            'picture' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => '0 = disabled, 1 = enabled ',
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
            ),
        ];

        $this->forge->addField($fields);  // Se agregan los campos de la tabla
        $this->forge->addKey('id_app_user', true); // Se define la llave primaria
        $this->forge->createTable($this->table_name); // Se crea la tabla

        // Insertamos el primer registro
        $this->db->table($this->table_name)->insert([
            'id_group'      => 1,
            'firstname'     => 'ADMIN',
            'lastname'      => 'ADMIN',
            'username'      => 'ADMIN',
            'email'         => 'admin@admin.com',
            'password_hash' => 'f146d5f4a14c117a715dcb9d1554127b7b52a08bb3642ab86f32324c5d79efc1f2cba088b4368ec63de7ba34709cbb0eb8abf5d8f66fc2755827462a9611fe69',
            'picture'       => '',
            'status'        => 1,
            'created_by'    => 1,
            'updated_by'    => 1
        ]);

        // modificamos la tabla `app_group` agregando las llaves foraneas --------------------
        $this->db->query('ALTER TABLE `app_group` ADD CONSTRAINT `fk_group_created` 
        FOREIGN KEY(`created_by`) REFERENCES app_user(`id_app_user`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `app_group` ADD CONSTRAINT `fk_group_updated`
        FOREIGN KEY(`updated_by`) REFERENCES app_user(`id_app_user`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `app_group` ADD CONSTRAINT `fk_group_deleted`
        FOREIGN KEY(`deleted_by`) REFERENCES app_user(`id_app_user`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `app_user` ADD CONSTRAINT `fk_user_group`
        FOREIGN KEY(`id_app_user`) REFERENCES app_group(`id_app_group`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        // modificamos la tabla `app_settings` agregando las llaves foraneas --------------------
        $this->db->query('ALTER TABLE `app_settings` ADD CONSTRAINT `fk_settings_created` 
        FOREIGN KEY(`created_by`) REFERENCES app_user(`id_app_user`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `app_settings` ADD CONSTRAINT `fk_settings_updated`
        FOREIGN KEY(`updated_by`) REFERENCES app_user(`id_app_user`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `app_settings` ADD CONSTRAINT `fk_settings_deleted`
        FOREIGN KEY(`deleted_by`) REFERENCES app_user(`id_app_user`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');
    }

    /**
     * Revierte los cambios realizados por la funcion `up`
     *
     * Elimina las llaves foraneas de la tabla app_group
     * Elimina la tabla app_user
     *
     * @return void
     */
    public function down()
    {
         // Eliminamos las llaves foraneas
         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_group_created`;");
         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_group_updated`;");
         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_group_deleted`;");
         
         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_user_group`;");

         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_settings_updated`;");
         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_settings_deleted`;");
         $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_settings_deleted`;");
         
         // Eliminamos la tabla
         $this->forge->dropTable($this->table_name);
    }
}
