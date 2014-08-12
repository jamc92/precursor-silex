<?php
/**
 * Description of Php.php.
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage File\Upload
 */

namespace Precursor\File\Upload;

use Precursor\File\Upload\File;


class Php extends File{

    /**
     * @var array $_allowedExtensions
     */
    var $_allowedExtensions = array('php', 'php4', 'php5');

    /**
     * @var bool
     */
    var $ignore_uploads = false;

    /**
     * Settings depending on options
     *
     * @param array $options
     */
    function __construct(array $options = array())
    {
        if (isset($options['ignore_uploads']))
            $this->ignore_uploads = $options['ignore_uploads'];
        parent::__construct($options);
    }

    /**
     * Upload the file. Save the file in the server.
     *
     * @param $file Uploaded File
     *
     * @return JSON
     */
    public function upload($file)
    {

        if ($file['error'] == 0) {

            if (!in_array($this->getExtension($file['name']), $this->_allowedExtensions) && $file['type'] != 'application/octet-stream') {
                return $this->exitStatus('Only ' . implode(',', $this->_allowedExtensions) . ' files are allowed!', array(), 'array');
            }

            if ($this->ignore_uploads) {
                // File uploads are ignored. We only log them.

                $line = implode('		', array(date('r'), $_SERVER['REMOTE_ADDR'], $file['size'], $file['name']));
                file_put_contents('php-log.txt', $line . PHP_EOL, FILE_APPEND);

                return $this->exitStatus('Uploads are not permited.', array(), 'array');
            }

            // Move the uploaded file from the temporary
            // directory to the uploads folder:

            if (move_uploaded_file($file['tmp_name'], $this->upload_dir . $file['name'])) {
                $message = array(
                    'file' => $file['name']
                );
                return $this->exitStatus('File was uploaded successfuly!', $message, 'array');
            }
        } else {
            return $this->exitStatus('Something went wrong with your upload!', array(), 'array');
        }
    }

} 