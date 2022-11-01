<?php
    if (isset($fields['created_by']) && isset($fields['updated_by']) && isset($fields['deleted_by'])) {
        $controlFields = true;
        unset($fields['created_by']);
        unset($fields['updated_by']);
        unset($fields['deleted_by']);
        unset($fields['created_at']);
        unset($fields['updated_at']);
        unset($fields['deleted_at']);
    }
?>
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
use CodeIgniter\Entity\Entity;

class {class} extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
<?php foreach ($fields as $field => $value) : ?>
<?php if(isset($value['type'])) :?>
    '<?= $field ?>' => '<?php
    switch ($value['type']) {
        case 'INT':
            echo 'integer';
            break;
        case 'VARCHAR':
            echo 'string';
            break;
        case 'TEXT':
            echo 'string';
            break;
        case 'DECIMAL':
            echo 'float';
            break;
        case 'FLOAT':
            echo 'float';
            break;
        case 'DOUBLE':
            echo 'float';
            break;
        case 'TINYINT':
            echo 'boolean';
            break;
        default:
            echo 'string';
            break;
        }?>',
<?php endif; ?>
<?php endforeach ?>
<?php if($controlFields) : ?>
    'created_by' => 'string',
    'updated_by' => 'string',
    'deleted_by' => '?string'
<?php endif?>
    ];
}