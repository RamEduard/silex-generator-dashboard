<?php

namespace Application\Model;

use Application\BaseModel;
use Doctrine\DBAL\Connection;

/**
 * Description of Color
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Color extends BaseModel
{
    
    /**
     * Product Id
     * 
     * @var int
     */
    protected $productId;
    
    /**
     * Name
     *
     * @var string
     */
    protected $name;
    
    /**
     * Color Hexadecimal
     * 
     * @var string
     */
    protected $colorHex;

    /**
     * Static Color Instance like singleton
     * 
     * @var Color
     */
    static $instance;

    /**
     * Color model construct
     * 
     * @param Connection $connection
     * @param int $id
     * @param string $name
     */
    public function __construct(Connection $connection, $id = null, $productId = null, $name = "", $colorHex = "")
    {
        parent::__construct($connection, 'colors');
        
        $this->setId($id)
                ->setProductId($productId)
                ->setName($name)
                ->setColorHex($colorHex);
    }
    
    /**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return Color
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof Color)) {
            self::$instance = new Color($connection);
        }
        
        return self::$instance;
    }
    
    /**
     * Exists color
     * 
     * @return boolean
     */
    protected function exists()
    {
        if (!is_null($this->id)) {
            $color = $this->getById($this->id);
        } else if (($this->id && $this->productId) && ($this->name && $this->colorHex)) {
            $where = "WHERE (product_id = ? AND name = ? AND color_hex = ?)";
            $criteria = array($this->productId, $this->name, $this->colorHex);
            $color = $this->getAll(array(), array(), $where, $criteria);
        }
        return (is_array($color) && !empty($color));
    }
    
    /**
     * Get colors by product id
     * 
     * @param int $id
     * @return arry
     */
    public function getColorsByProductId($id)
    {
        return $this->getAll(array(), array(), "WHERE product_id = $id");
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
     * Get the product id
     * 
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
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
     * Get the color hexadecimal
     * 
     * @return string
     */
    public function getColorHex()
    {
        return $this->colorHex;
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
     * Set product id
     * 
     * @param int $productId
     * @return Category
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        
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
     * Set color hexadecimal
     * 
     * @param string $colorHex
     * @return Category
     */
    public function setColorHex($colorHex)
    {
        $this->colorHex = $colorHex;
        
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
            'name'       => $this->name,
            'color_hex'  => $this->colorHex,
        );
        if ($this->exists()) {
            return $this->_update($data, array('id' => $this->id, 'product_id' => $this->productId));
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
