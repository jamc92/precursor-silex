<?php
/**
 * Description of File.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage File\Upload
 */

namespace Precursor\File\Upload;

use Precursor\File\Reader,
    Precursor\File\Writer,
    Precursor\File\Upload\Functions;


class File extends Functions implements Reader, Writer{

    /**
     * @var string $_content
     */
    protected $_content;

    /**
     * @var string $_extension
     */
    protected $_extension;

    /**
     * @var string $_name
     */
    protected $_name;

    /**
     * @var string $_package
     */
    protected $_namespace;

    /**
     * @param array $options
     */
    function __construct(array $options = array())
    {
        if (isset($options['content'])) {
            $this->_content = $options['content'];
        }
        if (isset($options['extension'])) {
            $this->_extension = $options['extension'];
        }
        if (isset($options['name'])) {
            $this->_name = $options['name'];
        }
        if (isset($options['namespace'])) {
            $this->_namespace = $options['namespace'];
        }
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Validate if the file is readeable
     *
     * @return bool
     */
    public function isReadeable()
    {
        // TODO: Implement isReadeable() method.
    }

    /**
     * Read the file content
     *
     * @param int $bufferSize
     *
     * @return mixed
     */
    public function read($bufferSize = 8192)
    {
        // TODO: Implement read() method.
    }

    /**
     * Validate if the file is writtable
     *
     * @return bool
     */
    public function isWrittable()
    {
        // TODO: Implement isWrittable() method.
    }

    /**
     * Write the file
     *
     * @param int $mode
     *
     * @return mixed
     */
    public function write($mode = 0777)
    {
        // TODO: Implement write() method.
    }

    /**
     * Write a folder
     *
     * @param string $folder
     * @param int $mode
     *
     * @return mixed
     */
    public function writeFolder($folder = "", $mode = 0777)
    {
        // TODO: Implement writeFolder() method.
    }

    /**
     * Overwrite the file
     *
     * @param int $mode
     *
     * @return mixed
     */
    public function overwrite($mode = 0777)
    {
        // TODO: Implement overwrite() method.
    }
}