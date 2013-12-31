<?php

/**
 * BaseContentCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property boolean $is_system
 * @property integer $order_num
 * @property Doctrine_Collection $Contents
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseContentCategory extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('content_category');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('is_system', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('order_num', 'integer', 4, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '4',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Content as Contents', array(
             'local' => 'id',
             'foreign' => 'type'));
    }
}