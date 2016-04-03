<?php

namespace Application\Model;

use Application\BaseModel;
use Doctrine\DBAL\Connection;

/**
 * Description of Image
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Image extends BaseModel
{
    
    /**
     * Name
     *
     * @var string
     */
    protected $name;
    
    /**
     * URL
     * 
     * @var string
     */
    protected $link;
    
    /**
     * Image blob browser
     * 
     * @var string
     */
    protected $image;
    
    /**
     * Static Category Instance like singleton
     * 
     * @var Image
     */
    static $instance;
    
    /**
     * Image model construct
     * 
     * @param Connection $connection
     * @param int $id
     * @param string $name
     */
    public function __construct(Connection $connection, $id = null, $name = "")
    {
        parent::__construct($connection, 'image');
        
        $this->setId($id)->setName($name);
    }
    
    /**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return Image
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof Category)) {
            self::$instance = new Category($connection);
        }
        
        return self::$instance;
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
     * Get link
     * 
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * Get image
     * 
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set id
     * 
     * @param int $id
     * @return Image
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
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Set name
     * 
     * @param string $link
     * @return Image
     */
    public function setLink($link)
    {
        $this->link = $link;
        
        return $this;
    }
    
    /**
     * Set name
     * 
     * @param string $image
     * @return Image
     */
    public function setImage($image)
    {
        $this->image = $image;
        
        return $this;
    }
    
}
