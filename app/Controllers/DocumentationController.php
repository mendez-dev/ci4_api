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

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

/**
 * Proporcionar la documentación de la API para los programadores FrontEnd
 * 
 * @package  API_CI4
 * @category Controller
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class DocumentationController extends ResourceController
{
    /**
     * Carga la vista de Swagger UI
     *
     * @return string
     */
    public function index(): string
    {
        return view("documentation/index");
    }

    /**
     * Retorna el json con la documentación de la api
     * 
     * para mas información ver:
     *   https://zircote.github.io/swagger-php/guide/installation.html
     *
     * @return Response
     */
    public function json(): Response
    {
        $openapi = \OpenApi\Generator::scan([APPPATH . '/Controllers']);
        header('Content-Type: application/json');
        return $this->respond($openapi);
    }
}
