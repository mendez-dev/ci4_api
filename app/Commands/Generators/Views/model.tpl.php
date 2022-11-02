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

use App\Entities\<?= $entity ?>;
use App\Models\CustomModel;

class <?= $entity ?>Model extends CustomModel
{
    protected $table          = '<?= $table ?>';
    protected $primaryKey     = '<?= $id ?>';
    protected $uuidFields     = ['<?= $id ?>'];
<?php unset($fields[$id]) ?>
    protected $returnType     = <?= $entity ?>::class;
<?php if($controlFields):?>
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
<?php endif?>
    protected $allowedFields  = [
<?php foreach ($fields as $field => $value) : ?>
<?php if(isset($value['type'])) :?>
        '<?= $field ?>',
<?php endif ?>
<?php endforeach ?>
<?php if($controlFields):?>
        'created_by',
        'updated_by',
        'deleted_by',
<?php endif?>
    ];

    protected $validationRules = [
<?php foreach ($fields as $field => $value) : ?>
<?php if(isset($value['type']) ) :?>
        '<?= $field ?>' => [
            'label' => '<?= $field ?>',
            'rules' => '<?php
                $rules = '';
                if (isset($value['null'])) {
                    $rules .= 'permit_empty|';
                }else{
                    $rules .= 'required|';
                }
                if (isset($value['unique'])) {
                    $rules .= 'is_unique[' . $table . '.' . $field . ',' . $id . ',{'. $id .'}]|';
                }
                if ($value['type']  == 'VARCHAR') {
                    $rules .= 'max_length[' . $value['constraint'] . ']|';
                }
                if ($value['type'] == 'INT') {
                    $rules .= 'integer|';
                }
                if ($value['type'] == 'DECIMAL') {
                    $rules .= 'decimal|';
                }
                if ($value['type'] == 'DATETIME') {
                    $rules .= 'valid_date[Y-m-d H:i:s]|';
                }
                if ($value['type'] == 'DATE') {
                    $rules .= 'valid_date[Y-m-d]|';
                }
                if ($value['type'] == 'TIME') {
                    $rules .= 'valid_date[H:i:s]|';
                }
                echo rtrim($rules, '|');
            ?>'
        ],
<?php endif ?>
<?php endforeach ?>
    ];

    /**
     *
     * Busca un elemento a partir de la información de sus columnas
     *
     * @param string|array $column nombre de la columna por la que se buscara
     * @param string $value valor a buscar
     *
     * @return User
     */
    public function get<?= $entity ?>By(string $column, string $value)
    {
        if (in_array($column, $this->allowedFields)) {
            return $this->where($column, $value)->first();
        }
    }
}
