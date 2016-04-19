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

    static $instance;

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
     * @param int $__TABLE_PRIMARYKEY__
     * @return int
     * @throws \LogicException
     */
    public function delete($__TABLE_PRIMARYKEY__ = null)
    {
        if (!$__TABLE_PRIMARYKEY__) {
            throw new \LogicException('Can\'t delete without param __TABLE_PRIMARYKEY__.');
        }
        
        return $this->_delete(array('__TABLE_PRIMARYKEY__' => $__TABLE_PRIMARYKEY__));
    }

    /**
     * {@inheritdoc}
     */
    public function insert(array $data)
    {
        return $this->_insert($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update($__TABLE_PRIMARYKEY__ = null, array $data = array())
    {
        if (!$__TABLE_PRIMARYKEY__) {
            throw new \LogicException('Can\'t update without param __TABLE_PRIMARYKEY__.');
        }

        $criteria = array('__TABLE_PRIMARYKEY__' => $__TABLE_PRIMARYKEY__);

        return $this->_update($data, $criteria);
    }

}