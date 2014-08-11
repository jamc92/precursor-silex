<?php
/**
 * Description of Upload.php.
 *
 * @author     Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage File
 */

namespace Precursor\File;

use Precursor\Collection,
    Precursor\File\Upload\ExtensionRegister,
    Precursor\File\Upload\Image,
    Precursor\File\Upload\Php,
    Precursor\File;


class Upload implements ExtensionRegister
{

    /**
     * @var Image|Php $_type
     */
    protected $_type;

    /**
     * @var Collection
     */
    protected $_allowedExtensions;

    /**
     * @param $_type
     * @param array $params
     *
     * @throws \Exception
     */
    function __construct($_type, array $params = array())
    {
        if (class_exists($_type)) {
            $this->_type = new $_type($params);
        } else {
            throw new \Exception("Class $_type don`t exists.");
        }
        $this->_allowedExtensions = new Collection('string');
        $this->registerExtensions($this->_type->_allowedExtensions);
    }

    /**
     * @return Image|Php|File
     */
    public function file() {
        return $this->_type;
    }

    /**
     * @return \Precursor\Collection
     */
    public function getAllowedExtensions()
    {
        return $this->_allowedExtensions;
    }

    /**
     * Register the allowed extensions
     *
     * @param $allowedExtensions
     *
     * @return void
     */
    function registerExtensions($allowedExtensions)
    {
        if (is_string($allowedExtensions))
            $this->_allowedExtensions->add($allowedExtensions);
        elseif (is_array($allowedExtensions)) {
            foreach ($allowedExtensions as $allowedExtension) {
                $this->_allowedExtensions->add($allowedExtension);
            }
        }
    }

}