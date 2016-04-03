<?php

namespace Application\Model;

use Application\BaseModel;
use Doctrine\DBAL\Connection;

/**
 * Description of Provider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Provider extends BaseModel
{
    
    /**
     * Name
     *
     * @var string
     */
    protected $name;
    
    /**
     * Static Provider Instance like singleton
     * 
     * @var Provider
     */
    static $instance;

    /**
     * Provider model construct
     * 
     * @param Connection $connection
     * @param int $id
     * @param string $name
     */
    public function __construct(Connection $connection, $id = null, $name = "")
    {
        parent::__construct($connection, 'providers');
        
        $this->setId($id)->setName($name);
    }
    
    /**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return Provider
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof Provider)) {
            self::$instance = new Provider($connection);
        }
        
        return self::$instance;
    }

    /**
     * Exists category
     * 
     * @return boolean
     */
    protected function exists()
    {
        $category = $this->getById($this->id);
        return (is_array($category) && !empty($category));
    }
    
    /**
     * Get the id
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return Provider
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set name
     * 
     * @param string $name
     * @return Provider
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Save the provider
     * 
     * @return int
     */
    public function save()
    {
        $data = array(
            'name' => $this->name
        );
        if ($this->exists()) {
            return $this->_update($data, array('id' => $this->id));
        } else {
            return $this->_insert($data);
        }
    }
    
}
