<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

if (!function_exists('rule_array')) {
    /**
     * Crea un `string válido` con reglas de validación a partir de un arreglo
     * de strings
     *
     * Esta función se crea por el motivo de que codeigniter 4 no permite
     * espacios entre las reglas de validación tiene que ser un string continuo
     * lo que genera lineas de código demasiado largas que superan la longitud de
     * 80 caracteres.
     *
     * @param array $rules contiene los diferentes strings con las reglas de
     * validación
     *
     * @return string
     */
    function rule_array(array $rules): string
    {

        // Almacenara la cadena de validación
        $string_rule = '';

        foreach ($rules as $key => $value) {
            $string_rule .= $value;
            if ($key + 1 < count($rules)) {
                $string_rule .= '|';
            }
        }

        return $string_rule;
    }
}

if (!function_exists('get_errors_array')) {
    /**
     * 
     * Aplana un arreglo de errores devolviendo unicamente sus valores
     *
     * @param array $errors contiene el arreglo asociativo con los errores
     *
     * @return array
     */
    function get_errors_array(array $errors): array
    {
        $array = [];

        foreach ($errors as $value) {
            $array[] = $value;
        }

        return $array;
    }
}
