<?php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Libraries; 

use Firebase\JWT\JWT;
use RuntimeException;

/**
 * Authorization librarie
 * 
 * Use the JWT library to generate authentication tokens
 * 
 * for more information see https://github.com/firebase/php-jwt
 * 
 * @author Wilber Méndez <mendezwilberdev@gmail.com>
 */
class Authorization
{

    /**
     * Generate an authentication token
     * 
     * It receives as a parameter the data that you want to encrypt in the
     * token, which could be, for example, the user's id
     * 
     * @param int|string	$data
     * @return string		$token
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
     * Check the token sent in the header
     * 
     * Read the bearer token sent in the header, and check its status, in 
     * case thetoken is invalid or has expired, it will return unauthorized
     * 
     * @return array|int|string
     */
    public static function verifyToken()
    {
        $request = \Config\Services::request();

        $token = '';

        if (isset($request->headers("Authorization")["Authorization"])) {
            // read bearer token
            $token = str_replace("Authorization: Bearer ", "", $request->headers("Authorization")["Authorization"]);
        }


        // evaluate token status
        if (empty($token)) {
            $data['hasError'] = TRUE;
            $data['message'] = "Unauthorized";
        } else {
            try {
                $data['data'] = JWT::decode($token, config('Jwt')->jwt_key, array('HS256'))->data;
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


        // if the token is invalid we return unauthorized
        if ($data['hasError']) {
            $data['code'] = UNAUTHORIZED;
            $data['data'] = self::pack(UNAUTHORIZED, $data['message']);
        }

        // if the token is valid we return its data
        return $data;
    }
}