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

class User extends Migration
{
    protected $table_name = TBL_USER;

    public function up()
    {
        // Campos de la tabla settings
        $fields = [
            'id_user'     => [
                'type'        => 'VARCHAR',
                'constraint'  => 36,
            ],
            'id_legacy'       => [
                'type'        => 'INT',
                'null'        => true,
                'unique'      => true
            ],
            'id_user_group'        => [
                'type'        => 'VARCHAR',
                'null'        => true,
                'constraint'  => 36,
            ],
            'firstname'       => [
                'type'        => 'VARCHAR',
                'constraint'  => '100'
            ],
            'lastname'        => [
                'type'        => 'VARCHAR',
                'constraint'  => '100'
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
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => '0 = disabled, 1 = enabled ',
            ],
            'created_by' => array(
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true,
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
            ),
        ];

        $this->forge->addField($fields);  // Se agregan los campos de la tabla
        $this->forge->addKey('id_user', true); // Se define la llave primaria
        $this->forge->createTable($this->table_name); // Se crea la tabla

        // Creamos el triger para usar unique id
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, 'id_user');

        // Insertamos el primer registro
        $this->db->table($this->table_name)->insert([
            'firstname'     => 'ADMIN',
            'lastname'      => 'ADMIN',
            'username'      => 'ADMIN',
            'email'         => 'admin@admin.com',
            'password_hash' => 'f146d5f4a14c117a715dcb9d1554127b7b52a08bb3642ab86f32324c5d79efc1f2cba088b4368ec63de7ba34709cbb0eb8abf5d8f66fc2755827462a9611fe69',
            'picture'       => '',
            'is_active'     => 1
        ]);
        $id_user = $migrationUtils->getFirstUserId();

        // Actualizamos el id del usuario que creó y actualizó el registro
        $this->db->table($this->table_name)->update([
            'created_by' => $id_user,
            'updated_by' => $id_user
        ], ['id_user' => $id_user]);

        $this->db->query("ALTER TABLE `$this->table_name` ADD CONSTRAINT `fk_user_created` 
        FOREIGN KEY(`created_by`) REFERENCES $this->table_name(`id_user`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;");

        $this->db->query("ALTER TABLE `$this->table_name` ADD CONSTRAINT `fk_user_updated` 
        FOREIGN KEY(`updated_by`) REFERENCES $this->table_name(`id_user`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;");

        $this->db->query("ALTER TABLE `$this->table_name` ADD CONSTRAINT `fk_user_deleted` 
        FOREIGN KEY(`deleted_by`) REFERENCES $this->table_name(`id_user`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;");

        // modificamos la tabla `app_group` agregando las llaves foraneas --------------------

        // $this->db->query('ALTER TABLE `app_group` ADD CONSTRAINT `fk_group_created` 
        // FOREIGN KEY(`created_by`) REFERENCES app_user(`id_user`) 
        // ON DELETE RESTRICT ON UPDATE CASCADE;');

        // $this->db->query('ALTER TABLE `app_group` ADD CONSTRAINT `fk_group_updated`
        // FOREIGN KEY(`updated_by`) REFERENCES app_user(`id_user`)
        // ON DELETE RESTRICT ON UPDATE CASCADE;');

        // $this->db->query('ALTER TABLE `app_group` ADD CONSTRAINT `fk_group_deleted`
        // FOREIGN KEY(`deleted_by`) REFERENCES app_user(`id_user`)
        // ON DELETE RESTRICT ON UPDATE CASCADE;');

        // TODO: MOVER A LA TABLA DE GRUPOS
        // $this->db->query('ALTER TABLE `app_user` ADD CONSTRAINT `fk_user_group`
        // FOREIGN KEY(`id_group`) REFERENCES app_group(`id_app_group`)
        // ON DELETE RESTRICT ON UPDATE CASCADE;');

        // // modificamos la tabla `app_settings` agregando las llaves foraneas --------------------
        // $this->db->query('ALTER TABLE `app_settings` ADD CONSTRAINT `fk_settings_created` 
        // FOREIGN KEY(`created_by`) REFERENCES app_user(`id_user`) 
        // ON DELETE RESTRICT ON UPDATE CASCADE;');

        // $this->db->query('ALTER TABLE `app_settings` ADD CONSTRAINT `fk_settings_updated`
        // FOREIGN KEY(`updated_by`) REFERENCES app_user(`id_user`)
        // ON DELETE RESTRICT ON UPDATE CASCADE;');

        // $this->db->query('ALTER TABLE `app_settings` ADD CONSTRAINT `fk_settings_deleted`
        // FOREIGN KEY(`deleted_by`) REFERENCES app_user(`id_user`)
        // ON DELETE RESTRICT ON UPDATE CASCADE;');
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
        // $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_group_created`;");
        // $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_group_updated`;");
        // $this->db->query("ALTER TABLE `app_group` DROP FOREIGN KEY `fk_group_deleted`;");

        // $this->db->query("ALTER TABLE `app_user` DROP FOREIGN KEY `fk_user_group`;");

        // $this->db->query("ALTER TABLE `app_settings` DROP FOREIGN KEY `fk_settings_created`;");
        // $this->db->query("ALTER TABLE `app_settings` DROP FOREIGN KEY `fk_settings_updated`;");
        // $this->db->query("ALTER TABLE `app_settings` DROP FOREIGN KEY `fk_settings_deleted`;");

        // Eliminamos las llaves foraneas
        $this->db->query("ALTER TABLE `$this->table_name` DROP FOREIGN KEY `fk_user_created`;");
        $this->db->query("ALTER TABLE `$this->table_name` DROP FOREIGN KEY `fk_user_updated`;");
        $this->db->query("ALTER TABLE `$this->table_name` DROP FOREIGN KEY `fk_user_deleted`;");

        // Eliminamos la tabla
        $this->forge->dropTable($this->table_name);
    }
}
