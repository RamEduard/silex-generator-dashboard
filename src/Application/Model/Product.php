<?php

namespace Application\Model;

use Application\BaseModel;
use DateTime;
use Doctrine\DBAL\Connection;

/**
 * Description of Product
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Product extends BaseModel
{

    /**
     * Provider id associated
     * 
     * @var int
     */
    protected $providerId;

    /**
     * Category id associated
     * 
     * @var int
     */
    protected $categoryId;

    /**
     * Name
     * 
     * @var string
     */
    protected $name;

    /**
     * Price
     * 
     * @var float
     */
    protected $price;
    
    /**
     * Size
     * 
     * @var string
     */
    protected $sizes;

    /**
     * Image URL
     * 
     * @var string
     */
    protected $image;
    
    /**
     * Created datetime
     * 
     * @var DateTime
     */
    protected $created;

    /**
     * Static Product Instance like singleton
     * 
     * @var Product
     */
    static $instance;

    /**
     * Product model construct
     * 
     * @param Connection $connection
     * @param int $id
     * @param string $name
     */
    public function __construct(Connection $connection, $id = null, $providerId = null, $categoryId = null, $name = "", $price = null, $sizes = "", $image = "")
    {
        parent::__construct($connection, 'products');
        
        $this->setId($id)
             ->setProviderId($providerId)
             ->setCategoryId($categoryId)
             ->setName($name)
             ->setPrice($price)
             ->setSizes($sizes)
             ->setImage($image);
    }
    
    /**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return Product
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof Product)) {
            self::$instance = new Product($connection);
        }
        
        return self::$instance;
    }
    
    /**
     * Get products category id
     * 
     * @param string $where
     * @param string $limit
     * @return array
     */
    public function getAllJoined($where = null, $limit = null)
    {
        $this->_queryBuilder
                ->select('p.*', 'categories.name as category', 'providers.name as provider')
                ->from($this->_table, 'p')
                ->innerJoin('p', 'categories', '', 'p.category_id = categories.id')
                ->innerJoin('p', 'providers', '', 'p.provider_id = providers.id')
                ->orderBy('p.created', 'DESC');
        
        if ($where)
            $this->_queryBuilder->where($where);
        
        $sql = $this->_queryBuilder->getSQL();
        
        if ($limit)
            $sql .= " $limit";
        
        return $this->_select($sql);
    }
    
    /**
     * Get All Related Posts
     * 
     * @param string|array $where
     * @param string $limit
     * @return array
     */
    public function getAllRelated($where, $limit = null)
    {
        $this->_queryBuilder
                ->select('p.*', 'categories.name as category', 'providers.name as provider')
                ->from($this->_table, 'p')
                ->where($where)
                ->innerJoin('p', 'categories', '', 'p.category_id = categories.id')
                ->innerJoin('p', 'providers', '', 'p.provider_id = providers.id')
                ->orderBy('p.created', 'DESC');
        
        $sql = $this->_queryBuilder->getSQL();
        
        if ($limit)
            $sql .= " $limit";
        
        return $this->_select($sql);
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
     * Get Provider id
     * 
     * @return int
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * Get Category id
     * 
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get price
     * 
     * @return float
     */
    public function getPrice() 
    {
        return $this->price;
    }

    /**
     * Get sizes
     * 
     * @return string
     */
    public function getSizes()        
    {
        return $this->sizes;
    }

    /**
     * Get Image URL
     * 
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Set Id
     * 
     * @param int $id
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set Provider id
     * 
     * @param int $providerId
     * @return Product
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;
        
        return $this;
    }

    /**
     * Set Category id
     * 
     * @param int $categoryId
     * @return Product
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        
        return $this;
    }

    /**
     * Set Name
     * 
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Set price
     * 
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        
        return $this;
    }

    /**
     * Set sizes
     * 
     * @param string $sizes
     * @return Product
     */
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
        
        return $this;
    }

    /**
     * Set Image URL
     * 
     * @param string $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;
        
        return $this;
    }

}
