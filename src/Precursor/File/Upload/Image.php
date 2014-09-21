<?php
/**
 * Clase de archivos de imágenes
 *
 * @author     Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Upload
 */

namespace Precursor\File\Upload;

use Precursor\File\Upload\Functions;

class Image extends Functions
{

    /**
     * @var array $_allowedExtensions
     */
    var $_allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

    /**
     * @var bool
     */
    var $ignore_uploads = false;

    /**
     * @var string
     */
    var $upload_dir = '';

    /**
     * @param array $options
     */
    function __construct(array $options = array())
    {
        if (isset($options['ignore_uploads'])) {
            $this->ignore_uploads = $options['ignore_uploads'];
        }
        if (isset($options['upload_dir'])) {
            $this->upload_dir = $options['upload_dir'];
        }
    }

    /**
     * @param $file
     *
     * @return JSON
     */
    public function upload($file)
    {
        if ($file['error'] == 0) {

            if (!in_array($this->getExtension($file['name']), $this->_allowedExtensions)) {
                return $this->exitStatus('Only ' . implode(',', $this->_allowedExtensions) . ' files are allowed!', array(), 'array');
            }

            if ($this->ignore_uploads) {
                // File uploads are ignored. We only log them.

                $line = implode('		', array(date('r'), $_SERVER['REMOTE_ADDR'], $file['size'], $file['name']));
                file_put_contents('image-log.txt', $line . PHP_EOL, FILE_APPEND);

                return $this->exitStatus('Uploads are not permited.', array(), 'array');
            }

            // Create the upload dir if don't exists
            if (!is_null($this->upload_dir) && !file_exists($this->upload_dir)) {
                @mkdir($this->upload_dir);
            }

            // Date
            $date = date("d-m-Y");
            if (!file_exists($this->upload_dir . "$date")) {
                @mkdir($this->upload_dir . "$date");
            }

            // Move the uploaded file from the temporary
            // directory to the uploads folder:

            if (move_uploaded_file($file['tmp_name'], $this->upload_dir . "$date/" . $file['name'])) {
                $message = array(
                    'folder' => $date,
                    'imagen' => $file['name']
                );
                return $this->exitStatus('File was uploaded successfuly!', $message, 'array');
            }
        } else {
            return $this->exitStatus('Something went wrong with your upload!', array(), 'array');
        }
    }
    
}