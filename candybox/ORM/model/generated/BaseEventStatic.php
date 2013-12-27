<?php

/**
 * BaseEventStatic
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $redirect
 * @property integer $order_num
 * @property string $img_path
 * @property clob $content
 * @property boolean $is_closed
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEventStatic extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('event_static');
        $this->hasColumn('title', 'string', 80, array(
             'type' => 'string',
             'length' => '80',
             ));
        $this->hasColumn('redirect', 'string', 200, array(
             'type' => 'string',
             'length' => '200',
             ));
        $this->hasColumn('order_num', 'integer', 2, array(
             'type' => 'integer',
             'length' => '2',
             ));
        $this->hasColumn('img_path', 'string', 200, array(
             'type' => 'string',
             'length' => '200',
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('is_closed', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}