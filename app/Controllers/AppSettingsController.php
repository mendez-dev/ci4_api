<?php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use \App\Entities\AppSettings;

 /**
 * Controllador `AppSettingsController`
 * 
 * Se encarga de la lógica de negocios de los ajustes de la
 * aplicación movil.
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

    public function __construct(){
        // Cargamos modelos librerias y helpers
        $this->appSettingsModel = model('AppSettingsModel');
        helper('validation');
    }

  
    /**
     * @OA\Get(
     *     path="/settings",
     *     tags={"Ajustes APP"},
     *     @OA\Response(
     *       response="200",
     *       description="Retorna los ajustes globales de la aplicación",
     *       @OA\JsonContent(
     *         type="object",
     *         example={
     *           "id_settings": 1,
     *           "app_name": "BASE APP",
     *           "default_tax": 0.13,
     *           "default_currency": "$",
     *           "main_color": "#FF4E6A",
     *           "main_dark_color": "#EA5C44",
     *           "second_color": "#344968",
     *           "second_dark_color": "#CCCCDD",
     *           "accent_color": "#8C98A8",
     *           "accent_dark_color": "#9999AA",
     *           "scaffold_dark_color": "#FAFAFA",
     *           "scaffold_color": "#2C2C2C",
     *           "created_by": 1,
     *           "created_at": {
     *             "date": "2022-05-12 10:49:07.000000",
     *             "timezone_type": 3,
     *             "timezone": "America/El_Salvador"
     *           },
     *           "updated_by": 1,
     *           "updated_at": {
     *             "date": "2022-05-12 10:49:07.000000",
     *             "timezone_type": 3,
     *             "timezone": "America/El_Salvador"
     *           },
     *           "deleted_by": null,
     *           "deleted_at": null
     *         }
     *       )  
     *     )
     * )
     * 
     * Retorna las configuraciones iniciales de la aplicación
     *
     * @return Response
     */
    public function index()
    {
        // Buscamos las parametros de las cunfiguraciones de la app
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
        // Cargamos la libreria para validar
        $validation = service('validation');

        // Definimos las reglas de validación
        $validation->setRules([
            'app_name' => [
                'label' => 'nombre de la aplicación',
                'rules' => rule_array([
                    'required',
                    'max_length[100]'
                ])
            ],
            'default_tax' => [
                'label' => 'inpuesto',
                'rules' => rule_array([
                    'required',
                    'decimal'
                    ])
            ],
            'default_currency' => [
                'label' => 'signo de la moneda',
                'rules' => rule_array([
                    'required',
                    'max_length[2]'
                ])
            ],
            'default_currency' => [
                'label' => 'signo de la moneda',
                'rules' => rule_array([
                    'required',
                    'max_length[2]'
                ])
            ],
            'main_color' => [
                'label' => 'color principal',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'main_dark_color' => [
                'label' => 'color principal (modo oscuro)',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'second_color' => [
                'label' => 'color secundario',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'second_dark_color' => [
                'label' => 'color secundario (modo oscuro)',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'accent_color' => [
                'label' => 'color de acento',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'accent_dark_color' => [
                'label' => 'color de acento (modo oscuro)',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'scaffold_color' => [
                'label' => 'color del scaffold',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
            'scaffold_dark_color' => [
                'label' => 'color del scaffold (modo oscuro)',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ],
        ]);

        // Si las validaciones fallan
        if (!$validation->withRequest($this->request)->run()) {
            return $this->respond(['errors' => get_errors_array($validation->getErrors())], 400);
        }
        
        // Creamos la entidad con los nuevos valores
        $appSettings = new AppSettings( (array) $this->request->getVar() );
        $appSettings->id_settings = 1;
        
        // Asignamos el id del usuario que modifica los ajustes
        // TODO: Obtener el id del usuario que actualiza el registro
        $appSettings->updated_by = 1;
        
        // Actualizamos los datos
        if ($this->appSettingsModel->save($appSettings)) {
            $settings = $this->appSettingsModel->first();
            return $this->respond($settings);
        }

        return $this->respond(['errors' => ['La petición no se pudo procesar']], 400);
    }
}


