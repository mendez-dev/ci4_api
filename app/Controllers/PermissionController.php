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

use App\Entities\Permission;
use App\Libraries\Authorization;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

/**
 * Controlador ´UserController´
 * 
 * Gestión de permisos, registro, actualización, eliminación, cambio de estado
 * de los permisos del sistema.
 */
class PermissionController extends ResourceController
{

    /**
     * Instancia de PermissionModel.
     * @var \App\Models\PermissionModel
     */
    private $permissionModel;

    public function __construct()
    {
        // Cargamos modelos librerias y helpers
        $this->permissionModel = model('PermissionModel');
        helper('validation');
        helper('utils');
    }

    /**
     * Retorna el listado de permisos paginados
     *
     * @return Response
     */
    public function index(): Response
    {
        // Obtenemos el numero de registros por página
        $page = getPage($this->request);

        $records_per_page = getRecordsPerPage($this->request);
        // Aplicamos los filtros enviados por query params
        $this->permissionModel->filterArray($this->request->getVar());
        // Buscamos los datos paginados

        if ($page) {
            $response = $this->permissionModel->getPagination($page, $records_per_page);
            if (!empty($response["data"])) {
                return $this->respond($response);
            }
        } else {
            $response = $this->permissionModel->findAll();
            if (!empty($response)) {
                return $this->respond($response);
            }
        }

        return $this->respond(["errors" => ['No se encontraron registros']], 404);
    }
}
