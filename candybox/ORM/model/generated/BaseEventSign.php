<?php

/**
 * BaseEventSign
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $event_id
 * @property integer $user_id
 * @property timestamp $sign_at
 * @property integer $tickets
 * @property string $receive_address
 * @property string $remarks
 * @property string $is_present
 * @property boolean $is_anonymous
 * @property integer $category_id
 * @property string $clients
 * @property integer $vote
 * @property integer $num
 * @property Event $Event
 * @property User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEventSign extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('event_sign');
        $this->hasColumn('event_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('sign_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('tickets', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('receive_address', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('remarks', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('is_present', 'string', 20, array(
             'type' => 'string',
             'length' => '20',
             ));
        $this->hasColumn('is_anonymous', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('category_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('clients', 'string', 20, array(
             'type' => 'string',
             'length' => '20',
             ));
        $this->hasColumn('vote', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '1',
             ));
        $this->hasColumn('num', 'integer', 3, array(
             'type' => 'integer',
             'default' => 1,
             'length' => '3',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Event', array(
             'local' => 'event_id',
             'foreign' => 'id'));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}