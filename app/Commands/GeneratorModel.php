<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use APP\Commands\CustomGeneratorTrait;

class GeneratorModel extends BaseCommand
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
    protected $name = 'create:model';

    /**
     * Descripción del comando
     *
     * @var string
     */
    protected $description = 'Crea un modelo personalizado.';

    /**
     /**
     * Uso del comando
     *
     * @var string
     */
    protected $usage = 'create:model <name> [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'name' => 'Nombre del modelo.',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--table'     => 'Supply a table name. Default: "the lowercased plural of the class name".',
        '--dbgroup'   => 'Database group to use. Default: "default".',
        '--return'    => 'Return type, Options: [array, object, entity]. Default: "array".',
        '--namespace' => 'Set root namespace. Default: "APP_NAMESPACE".',
        '--suffix'    => 'Append the component title to the class name (e.g. User => UserModel).',
        '--force'     => 'Force overwrite existing file.',
    ];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $params[0] = $params[0] . 'Model';
        $this->component = 'Model';
        $this->directory = 'Models';
        $this->template  = 'model.tpl.php';

        $this->classNameLang = 'CLI.generator.className.model';
        $this->execute($params);
    }

    /**
     * Preparar el contenido de la entidad
     */
    protected function prepare(string $class): string
    {

        // Obtenemos el nombre de la entidad a partir del nombre del modelo

        $entityName = str_replace('Models', 'Entities', $class);
        if (preg_match('/^(\S+)Model$/i', $entityName, $match) === 1) {
            $entityName = $match[1];
        }

        // Eliminar App\Entities\ de $entityName
        $entityName = str_replace('App\Entities\\', '', $entityName);


        // Accedemos a las opciones
        $data = [];
        $data = $this->getOption('data');
        $data['entity'] = $entityName;

        $class = $class . 'Model';

        return $this->parseTemplate($class, [], [], $data);
    }
}
