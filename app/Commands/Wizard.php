<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use APP\Commands\CustomGeneratorTrait;

class Wizard extends BaseCommand
{
    use CustomGeneratorTrait;

    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'CodeIgniter';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'wizard';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $class = $params[0] ?? CLI::getSegment(2);

        if (empty($class)) {
            $class = CLI::prompt('Nombre de la clase');
        }

        $data['uuid'] = false;
        $migration = $this->requestMigrationData($data, $class);
        $migrationOpts = [
            'data' => $migration
        ];



        $this->call('create:migration', array_merge([$class], $migrationOpts));
        $this->call('create:entity', array_merge([$class], $migrationOpts));
        $this->call('create:model', array_merge([$class], $migrationOpts));
        $this->call('create:controller', array_merge([$class], $migrationOpts));
    }
}
