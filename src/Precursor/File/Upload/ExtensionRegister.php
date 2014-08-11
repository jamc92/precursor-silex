<?php
/**
 * Description of ExtensionRegister.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage File\Upload
 */

namespace Precursor\File\Upload;


interface ExtensionRegister {

    /**
     * Register the allowed extensions
     *
     * @param $allowedExtensions
     *
     * @return mixed
     */
    function registerExtensions($allowedExtensions);

} 