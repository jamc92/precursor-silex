<?php
/**
 * Description of Reader.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage File
 */

namespace Precursor\File;

interface Reader {

    /**
     * Validate if the file is readeable
     *
     * @return bool
     */
    public function isReadeable();

    /**
     * Read the file content
     *
     * @param int $bufferSize
     *
     * @return mixed
     */
    public function read($bufferSize = 8192);

}