<?php

namespace __MODULE__\Model;

use Application\BaseModel;
use Doctrine\DBAL\Connection;

/**
 * Description of __CLASSNAME__
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class __CLASSNAME__ extends BaseModel
{

	/**
     * Category model construct
     * 
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        parent::__construct($connection, '__TABLENAME__');
    }

	/**
     * Get Instance like singleton
     * 
     * @param Connection $connection
     * @return __CLASSNAME__
     */
    static function getInstance(Connection $connection)
    {
        if (!(self::$instance instanceof __CLASSNAME__)) {
            self::$instance = new __CLASSNAME__($connection);
        }
        
        return self::$instance;
    }

	/**
     * Delete 
     * 
     * @param int $id
     * @return int
     * @throws \LogicException
     */
    public function delete($id = null)
    {
        if (!$id) {
            throw new \LogicException('Can\'t delete without param __TABLE_PRIMARYKEY__.');
        }
        
        return $this->_delete(array('__TABLE_PRIMARYKEY__' => $id));
    }

}