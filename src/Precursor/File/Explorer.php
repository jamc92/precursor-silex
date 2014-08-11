<?php
/**
 * Description of Explorer.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @package Precursor
 * @subpackage File
 */

namespace Precursor\File;

use \FilesystemIterator,
    Precursor\File\File;

class Explorer {

    /**
     * @var array $_folders
     */
    protected $_folders = array();

    /**
     * @var array $_foldersProtected
     */
    protected $_foldersProtected = array();

    /**
     * @return array|bool
     */
    public function getFiles()
    {
        $files = array();
        foreach ($this->_folders as $folder) {
            $fsi = new FilesystemIterator($folder);
            while ($fsi->valid()) {
                $file = $fsi->current();
                if ($file->isFile()) {
                    $files[] = array(
                        'bytes'   => $file->getSize(),
                        'carpeta' => $file->getPath(),
                        'nombre'  => $file->getFilename(),
                        'tipo'    => 'archivo'
                    );
                } elseif ($file->isDir()) {
                    $files[] = array(
                        'bytes'   => $file->getSize(),
                        'carpeta' => $file->getPath(),
                        'tipo'    => 'carpeta'
                    );
                }
                $fsi->next();
            }
        }
        return $files;
    }

    /**
     * @param array $folders
     */
    public function setFolders(array $folders = array())
    {
        $this->_folders = array_merge($this->_folders, $folders);
    }

    /**
     * Get protected files
     *
     * @return array
     */
    public function getFilesProtected() {
        $files = array();
        foreach ($this->_foldersProtected as $folder) {
            $fsi = new FilesystemIterator($folder);
            while ($fsi->valid()) {
                $file = $fsi->current();
                if ($file->isFile()) {
                    $files[] = array(
                        'bytes'   => $file->getSize(),
                        'carpeta' => $file->getPath(),
                        'nombre'  => $file->getFilename(),
                        'tipo'    => 'archivo'
                    );
                } elseif ($file->isDir()) {
                    $files[] = array(
                        'bytes'   => $file->getSize(),
                        'carpeta' => $file->getPath(),
                        'tipo'    => 'carpeta'
                    );
                }
                $fsi->next();
            }
        }
        return $files;
    }

    /**
     * @param array $foldersProtected
     */
    public function setFoldersProtected(array $foldersProtected = array())
    {
        $this->_foldersProtected = array_merge($this->_foldersProtected, $foldersProtected);
    }

}