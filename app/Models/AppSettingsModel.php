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
    protected $table            = 'app_settings';
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
        'main_color',
        'main_dark_color',
        'second_color',
        'second_dark_color',
        'accent_color',
        'accent_dark_color',
        'scaffold_dark_color',
        'scaffold_color',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
