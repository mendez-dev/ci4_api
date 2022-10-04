<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\Authorization;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class RouteController extends ResourceController
{
    /**
     * Instancia de RouteModel.
     * @var \App\Models\RouteModel
     */
    private $routeModel;

    /**
     * Instancia de UserModel.
     * @var \App\Models\UserModel;
     */
    private $userModel;

    public function __construct()
    {
        // Cargamos modelos librerías y helpers
        $this->routeModel = model('RouteModel');
        $this->userModel = model('UserModel');
        helper('validation');
        helper('utils');
    }

    public function index(): Response
    {
        // Obteemos los permisos del usuario
        $auth = Authorization::getData();
        $user = $this->userModel->find($auth->id_user);

        $ids_permissions = array_map(function ($permission) {
            return $permission->id_permission;
        }, $user->group->permissions);

        $data = $this->routeModel->getRoutes($ids_permissions);
        if (empty($data)) {
            return $this->respond(["errors" => ['No se encontraron registros']], 404);
        }
        return $this->respond($data, 200);
    }
}
