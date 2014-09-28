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
     * @param string $result
     * @param array $vars
     * @param string $type
     *
     * @return JSON|string
     */
    protected function exitStatus($result, array $vars = array(), $type = 'json')
    {
        if (strtolower($type) == 'json') {
            return json_encode(
                array(
                    'result' => $result,
                    'vars'   => $vars
                )
            );
        } else {
            return array(
                'result' => $result,
                'vars'   => $vars
            );
        }
    }

    /**
     * @param string $file_name
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