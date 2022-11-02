<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use APP\Commands\CustomGeneratorTrait;

class GeneratorController extends BaseCommand
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
    protected $name = 'create:controller';

    /**
     * Descripción del comando
     *
     * @var string
     */
    protected $description = 'Crea un controlador personalizado.';

    /**
     /**
     * Uso del comando
     *
     * @var string
     */
    protected $usage = 'create:controller <name> [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'name' => 'Nombre del controlador.',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--data'     => 'Datos para crear el controlador.',
    ];

    public function run(array $params)
    {
        $params[0] = $params[0] . 'Controller';
        $this->component = 'Controller';
        $this->directory = 'Controllers';
        $this->template  = 'controller.tpl.php';

        $this->classNameLang = 'CLI.generator.className.controller';
        $this->execute($params);
    }

    public function prepare(string $class): string
    {
        $entityName = str_replace('Controllers', 'Entities',  $class);
        if (preg_match('/^(\S+)Controller$/i', $entityName, $match) === 1) {
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
