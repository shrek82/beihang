<?php

/**
 * BaseSysMessage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property timestamp $post_at
 * @property date $start_at
 * @property date $expire_at
 * @property string $object
 * @property clob $reader
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSysMessage extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('sys_message');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('title', 'string', 80, array(
             'type' => 'string',
             'length' => '80',
             ));
        $this->hasColumn('content', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('post_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('start_at', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('expire_at', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('object', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('reader', 'clob', null, array(
             'type' => 'clob',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}