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
            echo isset($value['null']) ?  '?integer' : 'integer';
            break;
        case 'VARCHAR':
            echo isset($value['null']) ?  '?string' : 'string';
            break;
        case 'TEXT':
            echo isset($value['null']) ?  '?string' : 'string';
            break;
        case 'DECIMAL':
            echo isset($value['null']) ?  '?float' : 'float';
            break;
        case 'FLOAT':
            echo isset($value['null']) ?  '?float' : 'float';
            break;
        case 'DOUBLE':
            echo isset($value['null']) ?  '?float' : 'float';
            break;
        case 'TINYINT':
            echo isset($value['null']) ?  '?integer' : 'integer';
            break;
        default:
            echo isset($value['null']) ?  '?string' : 'string';
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