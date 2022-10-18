<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use APP\Commands\CustomGeneratorTrait;

class CustomMigration extends BaseCommand
{
    use CustomGeneratorTrait;

    /**
     * Grupo al que pertenece el comando
     *
     * @var string
     */
    protected $group = 'Personalizados';

    /**
     * Nombre del comando
     *
     * @var string
     */
    protected $name = 'create:migration';

    /**
     * Descripción del comando
     *
     * @var string
     */
    protected $description = 'Crea una migración personalizada.';

    /**
     * Uso del comando
     *
     * @var string
     */
    protected $usage = 'make:migration <name> [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'name' => 'Nombre de la migración.',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--table'     => 'Nombre de la tabla. Por defecto: "el nombre de la clase en minúsculas y plural".',
        '--dbgroup'   => 'Grupo de base de datos a usar. Por defecto: "default".',
        '--namespace' => 'Define el espacio de nombres. Por defecto: "App".',
        '--suffix'    => 'Define el sufijo de la clase. Por defecto: "".',
        '--session'   => 'Define si la migración es para la tabla de sesiones. Por defecto: "false".',
        '--matchIP'   => 'Define si la migración es para la tabla de sesiones. Por defecto: "false".',
    ];


    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $this->component = 'Migration';
        $this->directory = 'Database\Migrations';
        $this->template  = 'migration.tpl.php';

        if (array_key_exists('session', $params) || CLI::getOption('session')) {
            $table     = $params['table'] ?? CLI::getOption('table') ?? 'ci_sessions';
            $params[0] = "_create_{$table}_table";
        }

        $this->classNameLang = 'CLI.generator.className.migration';
        $this->execute($params);
    }

    /**
     * Prepare options and do the necessary replacements.
     */
    protected function prepare(string $class): string
    {
        $data = [];
        $fields = [];
        $data['table'] = $this->getOption('table');
        $data['id']    = $this->getOption('id');
        $data['uuid'] = false;



        if (empty($data['table'])) $data['table'] = CLI::prompt(
            lang('CLI.generator.tableName'),
            null
        );

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

        return $this->parseTemplate($class, [], [], $data);
    }

    /**
     * Change file basename before saving.
     */
    protected function basename(string $filename): string
    {
        return gmdate(config('Migrations')->timestampFormat) . basename($filename);
    }
}
