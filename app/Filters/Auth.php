<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Authorization;


class Auth implements FilterInterface
{
    /**
     * Verifica el token de autenticacón y el estado del usuario
     * 
     * Comprueba el estado del token y su validez así como el estado
     * del usuario al que corresponde el token
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Creamos una instancia de response
        $response = \Config\Services::response();

        // Verificamos el estado del token
        $token = Authorization::verifyToken();

        // Si el token es inválido retornamos un mensaje de error
        if ($token['hasError']) {
            // Si el token es invalido retornamos un mensaje de error
            $response->setStatusCode(401);
            $response->setJSON($token['data']);
            $response->send();
            die();
        }
        
        // Obtenemos la información del usuario
        $userModel = model('UserModel');
        $user = $userModel->find($token['data']->id_user);
        
        // Si no existe el usuario en la base de datos retornamos un error
        if (empty($user)) {
            $response->setStatusCode(401);
            $response->setJSON(['errors' => ['No existen datos del usuario']]);
            $response->send();
            die();
        }
        
        // Si el usuario esta desactivado retornamos un mensaje de error
        if (!$user->is_active) {
            $response->setStatusCode(401);
            $response->setJSON(['errors' => ['Usuario desactivado']]);
            $response->send();
            die();
        }


    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
