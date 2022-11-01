<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use APP\Commands\CustomGeneratorTrait;

class GeneratorEntity extends BaseCommand
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
    protected $name = 'create:entity';

    /**
     * Descripción del comando
     *
     * @var string
     */
    protected $description = 'Crea una entidad personalizada.';

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
        'name' => 'Nombre de la entidad.',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--namespace' => 'Set root namespace. Default: "APP_NAMESPACE".',
        '--suffix'    => 'Append the component title to the class name (e.g. User => UserEntity).',
        '--force'     => 'Force overwrite existing file.',
    ];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $this->component = 'Entity';
        $this->directory = 'Entities';
        $this->template  = 'entity.tpl.php';

        $this->classNameLang = 'CLI.generator.className.entity';
        $this->execute($params);
    }

    /**
     * Preparar el contenido de la entidad
     */
    protected function prepare(string $class): string
    {
        // Accedemos a las opciones
        $data = [];
        $data = $this->getOption('data');

        return $this->parseTemplate($class, [], [], $data);
    }
}
