<?php
/**
 * Funciones de carga de archivos al servidor
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Upload
 */

namespace Precursor\File\Upload;

abstract class Functions
{
    /**
     * @param $status
     * @param $vars
     * @param $type
     *
     * @return JSON|string
     */
    protected function exitStatus($status, $vars = array(), $type = 'json')
    {
        if (strtolower($type) == 'json') {
            return json_encode(
                array(
                    'status' => $status,
                    'vars'   => $vars
                )
            );
        } else {
            return array(
                'status' => $status,
                'vars'   => $vars
            );
        }
    }

    /**
     * @param $file_name
     *
     * @return string
     */
    protected function getExtension($file_name)
    {
        $ext = explode('.', $file_name);
        $ext = array_pop($ext);
        return strtolower($ext);
    }
    
} 