<?php
/**
 * Description of Writer.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage File
 */

namespace Precursor\File;

interface Writer {

    /**
     * Validate if the file is writtable
     *
     * @return bool
     */
    public function isWrittable();

    /**
     * Write the file
     *
     * @param int $mode
     *
     * @return mixed
     */
    public function write($mode = 0777);

    /**
     * Write a folder
     *
     * @param string $folder
     * @param int $mode
     *
     * @return mixed
     */
    public function writeFolder($folder = "", $mode = 0777);

    /**
     * Overwrite the file
     *
     * @param int $mode
     *
     * @return mixed
     */
    public function overwrite($mode = 0777);

} 