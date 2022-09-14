<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use \App\Entities\AppSettings;
use App\Libraries\Authorization;

/**
 * Controlador `AppSettingsController`
 * 
 * Se encarga de la lógica de negocios de los ajustes de la
 * aplicación móvil.
 *
 * @package  API_CI4
 * @category Controller
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class AppSettingsController extends ResourceController
{
    /**
     * Instancia de Settings Model
     *
     * @var \App\Models\AppSettingsModel
     */
    protected $appSettingsModel;

    public function __construct()
    {
        // Cargamos modelos librerías y helpers
        $this->appSettingsModel = model('AppSettingsModel');
        helper('validation');
    }


    /**
     * Retorna las configuraciones iniciales de la aplicación
     *
     * @return Response
     */
    public function index()
    {
        // Buscamos las parámetros de las configuraciones de la app
        $settings = $this->appSettingsModel->first();

        // Si se encontró el registro se retornan los datos
        if (!empty($settings)) {
            return $this->respond($settings);
        }

        // Si no se encontraron datos se retorna un 404
        return $this->respond(null, 404);
    }

    /**
     * Actualiza los datos de la configuración inicial de la app
     *
     * @return Response
     */
    public function update($id = null)
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Creamos la entidad con los nuevos valores
        $appSettings = new AppSettings((array) $this->request->getVar());

        // Eliminamos la información de quien creo y actualizo el registro
        unset($appSettings->created_by);
        unset($appSettings->updated_by);
        unset($appSettings->created_at);
        unset($appSettings->updated_at);

        // Obtenemos el id de los ajustes de la app
        $response = $this->appSettingsModel->findAll(1);
        $appSettings->id_settings = $response[0]->id_settings;
        // Asignamos el id del usuario que modifica los ajustes
        $appSettings->updated_by = $auth->id_user;

        // Actualizamos los datos
        if ($this->appSettingsModel->save($appSettings)) {
            $settings = $this->appSettingsModel->first();
            return $this->respond($settings);
        } else {
            return $this->respond(['errors' => get_errors_array($this->appSettingsModel->errors())], 400);
        }

        return $this->respond(['errors' => ['La petición no se pudo procesar']], 400);
    }
}
