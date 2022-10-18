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
     * En esta función se definen las columnas de la tabla que se van a generar
     * en el archivo de migración.
     * 
     * @return array
     */
    protected function defineFields(): array
    {
        $columns = [];
        do {
            $column_name = '';

            $column_name = CLI::prompt('Nombre del campo');

            if ($column_name) {

                $columns[$column_name]['type'] = strtoupper(CLI::prompt('Tipo de dato', $this->allowed_field_types, 'required'));

                $columns[$column_name]['constraint'] = $this->defineConstraints($columns[$column_name]['type']);
                if (!$columns[$column_name]['constraint']) unset($columns[$column_name]['constraint']);

                $unique = CLI::prompt('¿Es único?', ['n', 'y'], 'required');
                if ($unique == 'y') $columns[$column_name]['unique'] = true;

                $null = CLI::prompt('¿Puede ser nulo?', ['n', 'y'], 'required');
                if ($null == 'y') $columns[$column_name]['null'] = true;

                $comment = CLI::prompt('Comentario');
                if ($comment) $columns[$column_name]['comment'] = $comment;
            }
        } while ($column_name != '');

        return $columns;
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
