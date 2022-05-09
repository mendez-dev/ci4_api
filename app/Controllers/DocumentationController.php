<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class DocumentationController extends ResourceController
{
    public function index()
    {
        return view("documentation/index");
    }
    
    public function json(){
        $openapi = \OpenApi\Generator::scan([APPPATH.'/Controllers']);
        header('Content-Type: application/json');
        return $this->respond($openapi);
    }

    
}
