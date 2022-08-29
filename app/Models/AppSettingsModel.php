<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Models;

use CodeIgniter\Model;
use \App\Entities\AppSettings;


/**
 * Modelo `AppSettingsModel`
 *
 * Administra la interaccion con la base de datos de los ajustes de la app,
 * hace uso de la tabla `app_settings`
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class AppSettingsModel extends Model
{
    // Configuración del modelo
    protected $table            = TBL_APP_SETTINGS;
    protected $primaryKey       = 'id_settings';
    protected $returnType       = AppSettings::class;
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // Campos permitidos para inserción y edición
    protected $allowedFields    = [
        'app_name',
        'default_tax',
        'default_currency',
        'primary_color',
        'dark_primary_color',
        'primary_color_variant',
        'dark_primary_color_variant',
        'secondary_color',
        'dark_secondary_color',
        'secondary_color_variant',
        'dark_secondary_color_variant',
        'background',
        'dark_background',
        'surface',
        'dark_surface',
        'error',
        'dark_error',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $validationRules = [
        'app_name' => [
            'label' => 'nombre de la aplicación',
            'rules' => 'required|max_length[100]'
        ],
        'default_tax' => [
            'label' => 'inpuesto',
            'rules' => 'required|decimal'
        ],
        'default_currency' => [
            'label' => 'signo de la moneda',
            'rules' => 'required|max_length[2]'
        ],
        'primary_color' => [
            'label' => 'primario',
            'rules' => 'required|max_length[7]'
        ],
        'dark_primary_color' => [
            'label' => 'primario oscuro',
            'rules' => 'required|max_length[7]'
        ],
        'primary_color_variant' => [
            'label' => 'primario variante',
            'rules' => 'required|max_length[7]'
        ],
        'dark_primary_color_variant' => [
            'label' => 'primario oscuro variante',
            'rules' => 'required|max_length[7]'
        ],
        'secondary_color' => [
            'label' => 'secundario',
            'rules' => 'required|max_length[7]'
        ],
        'dark_secondary_color' => [
            'label' => 'secundario oscuro',
            'rules' => 'required|max_length[7]'
        ],
        'secondary_color_variant' => [
            'label' => 'secundario variante',
            'rules' => 'required|max_length[7]'
        ],
        'dark_secondary_color_variant' => [
            'label' => 'secundario oscuro variante',
            'rules' => 'required|max_length[7]'
        ],
        'background' => [
            'label' => 'fondo',
            'rules' => 'required|max_length[7]'
        ],
        'dark_background' => [
            'label' => 'fondo oscuro',
            'rules' => 'required|max_length[7]'
        ],
        'surface' => [
            'label' => 'superficie',
            'rules' => 'required|max_length[7]'
        ],
        'dark_surface' => [
            'label' => 'superficie oscuro',
            'rules' => 'required|max_length[7]'
        ],
        'error' => [
            'label' => 'error',
            'rules' => 'required|max_length[7]'
        ],
        'dark_error' => [
            'label' => 'error oscuro',
            'rules' => 'required|max_length[7]'
        ],


    ];
}
