<?php

namespace Application\Model;

use Application\BaseModel;
use Doctrine\DBAL\Connection;

/**
 * Description of Size
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class Size extends BaseModel
{

    /**
     * Static Size Instance like singleton
     * 
     * @var Size
     */
    static $instance;

    /**
     * Size model construct
     * 
     * @param Connection $connection
     * @param int $id
     * @param string $name
     */
    public function __construct(Connection $connection, $id = null, $name = "")
    {
        parent::__construct($connection, 'sizes');
        
//        $this->setId($id)->setName($name);
    }
    
    /**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return Size
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof Size)) {
            self::$instance = new Size($connection);
        }
        
        return self::$instance;
    }

}
