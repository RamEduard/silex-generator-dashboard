<?php

namespace Application\Model;

use Application\BaseModel;
use Doctrine\DBAL\Connection;

/**
 * Description of Category
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Category extends BaseModel
{
    
    /**
     * Name
     *
     * @var string
     */
    protected $name;
    
    /**
     * Static Category Instance like singleton
     * 
     * @var Category
     */
    static $instance;

    /**
     * Category model construct
     * 
     * @param Connection $connection
     * @param int $id
     * @param string $name
     */
    public function __construct(Connection $connection, $id = null, $name = "")
    {
        parent::__construct($connection, 'categories');
        
        $this->setId($id)->setName($name);
    }
    
    /**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return Category
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof Category)) {
            self::$instance = new Category($connection);
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
        if (!$this->id) return false;
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
     * @return Category
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
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Save the category
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
            $data['created'] = date('Y-m-d H:m:s');
            return $this->_insert($data);
        }
    }
    
    /**
     * Delete category
     * 
     * @param int $id
     * @return int
     * @throws \LogicException
     */
    public function delete($id = null)
    {
        if (!$this->id && !$id) {
            throw new \LogicException('Can\'t delete category without param id.');
        }
        if ($id === $this->id) {
            return $this->_delete(array('id' => $this->id));
        } else {
            return $this->_delete(array('id' => $id));
        }
    }
    
}
