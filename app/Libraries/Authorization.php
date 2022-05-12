<?php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilberdev@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Libraries; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;

/**
 * Libreria Authorization
 * 
 * Usa una libreria de JWT para generar tokens de autenticación
 * 
 * para mas información ver https://github.com/firebase/php-jwt
 * 
 * @author Wilber Méndez <mendezwilberdev@gmail.com>
 */
class Authorization
{

    /**
     * Genera el token de autenticación
     * 
     * Recibe como parametros los datos que se quieren cifrar en el token
     * 
     * @param array    data
     * @return string  $token
     */
    public static function generateToken($data)
    {

        // prepare payload
        $payload  = array(
            "iss" => base_url(),
            "aud" => base_url(),
            "exp" => strtotime(config('Jwt')->expiration_time),
            "iat" => strtotime("now"),
            "nbf" => strtotime("now"),
            "data" => $data
        );

        // generate and return token
        return JWT::encode($payload, config('Jwt')->jwt_key, 'HS256');
    }

    /**
     * Verifica el token enviado en el header
     * 
     * Lee el token enviado en el encabezado y verifica su validez, en caso que
     * sea inválido o este caducado retornara un codigo de error.
     * 
     * @return array|int|string
     */
    public static function verifyToken()
    {
        $request = \Config\Services::request();

        $token = '';

        if (isset($request->headers("Authorization")["Authorization"])) {
            // Leer el token
            $token = str_replace("Authorization: Bearer ", "", $request->headers("Authorization")["Authorization"]);
        }


        // Evalua el estado del token
        if (empty($token)) {
            $data['hasError'] = TRUE;
            $data['message'] = "Unauthorized";
        } else {
            try {
                $data['data'] = JWT::decode($token, new Key(config('Jwt')->jwt_key, 'HS256'))->data;
                $data['hasError'] = FALSE;
            } catch (\Firebase\JWT\BeforeValidException $e) {
                $data['hasError'] = TRUE;
                $data['message'] = $e->getMessage();
            } catch (\Firebase\JWT\ExpiredException $e) {
                $data['hasError'] = TRUE;
                $data['message'] = $e->getMessage();
            } catch (RuntimeException $e) {
                $data['hasError'] = TRUE;
                $data['message'] = $e->getMessage();
            }
        }


        // si el token no es válido, lo devolvemos unauthorized
        if ($data['hasError']) {
            $data['code'] = 401;
            $data['data'] = ["errors" => $data['message']];
        }

        // si el token es válido devolvemos sus datos
        return $data;
    }

    /**
     * Retorna los datos que se encuentran dentro del token
     */
    public static function getData()
    {
        $request = \Config\Services::request();
        $token = '';

        if (isset($request->headers("Authorization")["Authorization"])) {
            //  Leer el token
            $token = str_replace("Authorization: Bearer ", "", $request->headers("Authorization")["Authorization"]);
        }

        if ($token != '') {
            try {
                $data = JWT::decode($token, new Key(config('Jwt')->jwt_key, 'HS256'))->data;
                return $data;
            } catch (\Throwable $th) {
                return [];
            }
        }
        return [];
    }
}