<@php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilberdev@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace {namespace};

use CodeIgniter\Database\Migration;
<?php if($uuid): ?>
use App\Libraries\MigrationUtils;
<?php endif; ?>

class {class} extends Migration
{
    protected $table_name = '<?= $table ?>';

    public function up()
    {
        $fields = [
            '<?= $id ?>'=> [
                'type'           => '<?= $fields[$id]['type'] ?>',
                'constraint'     => <?= $fields[$id]['constraint'] ?>,
<?php unset($fields[$id]) ?>
            ],
<?php foreach ($fields as $field => $value) : ?>
<?php if(isset($value['type'])) :?>
            '<?= $field ?>'=> [
                'type'           => '<?= $value['type'] ?>',
<?php if(isset($value['constraint'])) : ?>
                'constraint'     => '<?= $value['constraint'] ?>',
<?php endif ?>
<?php if (isset($value['unique'])) : ?>
                'unique'         => true,
<?php endif ?>
<?php if (isset($value['null'])) : ?>
                'null'           => true,
<?php endif ?>
                ],
<?php else : ?>
                '<?= $value ?>',
<?php endif?>
<?php endforeach ?>
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('<?= $id ?>', true);
<?php if(isset($fields['created_by'])) :?>
        $this->forge->addForeignKey('created_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
<?php endif?>
<?php if(isset($fields['updated_by'])) :?>
        $this->forge->addForeignKey('updated_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
<?php endif?>
<?php if(isset($fields['deleted_by'])) :?>
        $this->forge->addForeignKey('deleted_by', TBL_USER, 'id_user', 'CASCADE', 'RESTRICT');
<?php endif?>
        $this->forge->createTable($this->table_name);

<?php if($uuid): ?>
        $migrationUtils = new MigrationUtils();
        $migrationUtils->createUniqueIdTrigger($this->table_name, '<?= $id ?>');
<?php endif; ?>

    }

    public function down()
    {
        // Se elimina la tabla
        $this->forge->dropTable($this->table_name);

    }

}