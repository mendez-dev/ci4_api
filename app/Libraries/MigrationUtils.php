<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilberdev@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Libraries;

use Config\Database;

/**
 * Libreia MigrationUtils
 *
 * Libreria que nos permite trabajar con `unique id` en lugar de autoincremental
 * esto nos facilita el trabajo al momento de replicar informacion entre
 * diferentes bases de datos.
 *
 * @package API_CI4
 * @category libraries
 * @author Wilber Méndez <mendezwilberdev@gmail.com>
 */
class MigrationUtils
{
  /**
   * Instancia de coneccion a la base de datos.
   *
   * @var ConnectionInterface
   */
  protected $db;

  /**
   * Instancia de Database Forge.
   *
   * @var Forge
   */
  protected $forge;

  /**
   * Carga las instancias de `forge` y `db`
   */
  public function __construct()
  {
    $this->forge = $forge ?? Database::forge($this->DBGroup ?? config('Database')->defaultGroup);
    $this->db = $this->forge->getConnection();
  }

  /**
   * Crea disparador que inserta el unique id
   *
   * Crea un disparador que agrega un unique id a los registros luego de ser
   * insertados siempre y cuando el id identificador este vacio.
   *
   * @param string $table nombre de la tabla
   * @param string $id   campo que sera el unique id
   * @return void
   */
  public function createUniqueIdTrigger(string $table, string $id): void
  {
    $this->db->query("CREATE TRIGGER `insert_uuid_" . $table . "` BEFORE INSERT ON `" . $table . "`\r\n
        FOR EACH ROW\r\n
        BEGIN\r\n
          IF new." . $id . " = \"\" THEN BEGIN\r\n
            SET new." . $id . " = uuid();\r\n
          END; END IF;\r\n
        END\r\n");
  }

  /**
   * Obtiene el id del primer usuario registrado en el sistema
   * 
   * De esta forma se puede usar como referencia al momento de crear otros registros
   *
   * @return string
   */
  public function getFirstUserId(): string
  {
    // Obtenemos el id del usuario administrador, El primer usuario registrado
    $first_user = $this->db->table(TBL_USER)->orderBy("created_by", 'ASC')->get()->getRow();
    return $first_user->id_user;
  }

  /**
   * Obtiene la primera fila de una tabla
   * 
   * De esta forma se puede usar como referencia al momento de crear otros registros
   *
   * @return string
   */
  public function getFirstRow(string $table): object
  {
    // Obtenemos el id del usuario administrador, El primer usuario registrado
    $row = $this->db->table($table)->orderBy("created_by", 'ASC')->get()->getRow();
    return $row;
  }
}
