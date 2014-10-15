<?php

namespace Precursor\Tests;

use Doctrine\DBAL\Configuration,
    Doctrine\DBAL\DriverManager,
    Precursor\Application\Model,
    Precursor\Options\Doctrine;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Model
     */
    protected $_model;
    
    /**
     * @var Connection
     */
    protected $_conn;
    
    protected function setUp()
    {
        $config = new Configuration();
        
        $params = Doctrine::getOptions();
        
        $this->_conn = DriverManager::getConnection($params, $config);
        
        $this->_model = new Model($this->_conn);
    }
    
    public function testDb()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        
        $this->assertTrue(get_class($this->_conn) == 'Doctrine\DBAL\Connection');
    }

    public function testQueryBuilder()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        
        $queryBuilder = $this->_model->queryBuilder();
        
        $this->assertTrue(get_class($queryBuilder) == 'Doctrine\DBAL\Query\QueryBuilder');
        
        $queryBuilder
                ->select('*')
                ->from('usuario')
                ->where('id = ?')
                ->setParameter(0, 1);
        
    }

    public function testGetTodo()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        
        $this->_model->setTable('usuario');
        
        $users = $this->_model->getTodo();

        $this->assertTrue(is_array($users));
    }

    
    public function testGetPorId()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
        
        $this->_model->setTable('usuario');
        
        $user = $this->_model->getPorId(1);

        $this->assertTrue(is_array($user));
    }

}