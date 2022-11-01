<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace App\Commands;

use CodeIgniter\CLI\GeneratorTrait;
use CodeIgniter\CLI\CLI;

/**
 * GeneratorTrait contains a collection of methods
 * to build the commands that generates a file.
 */
trait CustomGeneratorTrait
{
    use GeneratorTrait;

    protected $allowed_field_types = ['varchar', 'int', 'text', 'enum', 'date', 'time', 'datetime', 'decimal', 'tinyint'];

    /**
     * Gets the generator view as defined in the `Config\Generators::$views`,
     * with fallback to `$template` when the defined view does not exist.
     */
    protected function renderTemplate(array $data = []): string
    {
        return view("App\\Commands\\Generators\\Views\\{$this->template}", $data, ['debug' => false]);
    }

    /**
     * Solicita al usuario toda la información necesaria para generar la migración.
     * 
     * @param array $data
     * 
     * @return array
     */
    protected function requestMigrationData(array $data = [], string $className = ''): array
    {
        if (empty($data['table'])) $data['table'] = CLI::prompt(
            lang("Nombre de la tabla por defecto [{$className}]"),
            null
        );

        if (empty($data['table'])) {
            $data['table'] = $className;
        }

        if (empty($data['id'])) $data['id'] = CLI::prompt(
            'Nombre del campo ID',
            null,
            'required'
        );

        $id_type = CLI::prompt('Tipo de ID', ['UUID', 'AI'], 'required');

        if ($id_type == 'UUID') {
            $fields[$data['id']] = [
                'type' => 'VARCHAR',
                'constraint' => 36
            ];
            $data['uuid'] = true;
        } else {
            $fields[$data['id']] = [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ];
        }

        $fields = array_merge($fields, $this->defineFields());
        $fields = array_merge($fields, $this->addTimestampsAndUserFields());
        $data['fields'] = $fields;

        return $data;
    }

    /**
     * En esta función se definen las columnas de la tabla que se van a generar
     * en el archivo de migración.
     * 
     * @return array
     */
    protected function defineFields(): array
    {
        $fields = [];
        do {
            $field_name = '';

            $field_name = CLI::prompt('Nombre del campo');

            if ($field_name) {

                $fields[$field_name]['type'] = strtoupper(CLI::prompt('Tipo de dato', $this->allowed_field_types, 'required'));

                $fields[$field_name]['constraint'] = $this->defineConstraints($fields[$field_name]['type']);
                if (!$fields[$field_name]['constraint']) unset($fields[$field_name]['constraint']);

                $unique = CLI::prompt('¿Es único?', ['n', 'y'], 'required');
                if ($unique == 'y') $fields[$field_name]['unique'] = true;

                $null = CLI::prompt('¿Puede ser nulo?', ['n', 'y'], 'required');
                if ($null == 'y') $fields[$field_name]['null'] = true;

                $comment = CLI::prompt('Comentario');
                if ($comment) $fields[$field_name]['comment'] = $comment;
            }
        } while ($field_name != '');

        return $fields;
    }

    /**
     * En esta función se definen las restricciones de los campos de la tabla
     * que se van a generar en el archivo de migración.
     * 
     * @param string $type
     * @return string
     */
    protected function defineConstraints($type)
    {
        switch ($type) {
            case $type == 'VARCHAR' || $type == 'INT':
                return CLI::prompt('Longitud', null, 'integer');
                break;
            case $type == 'DECIMAL':
                $number = CLI::prompt('Longitud', null, 'integer');
                return $number . ',' . CLI::prompt('Decimales', null, 'integer');
                break;
            case $type == 'ENUM':
                $options = [];
                do {
                    $option = CLI::prompt('Opción');
                    if ($option) $options[] = $option;
                } while ($option != '');
                return "['" . implode("','", $options) . "']";
                break;
            default:
                break;
        }
    }

    /**
     * Se consulta al desarrollador si desea agregar los timestamps y cambios a
     * la referencia del usuario.
     */
    protected function addTimestampsAndUserFields(): array
    {
        $fields = [];
        $add_timestamps = CLI::prompt('¿Añadir campos de timestamps y referencias de usuario?', ['y', 'n'], 'required');
        if ($add_timestamps == 'y') {
            $fields = [
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
        }
        return $fields;
    }
}
