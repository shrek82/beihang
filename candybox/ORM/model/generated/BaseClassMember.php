<?php

/**
 * BaseClassMember
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $class_room_id
 * @property string $title
 * @property boolean $is_manager
 * @property timestamp $join_at
 * @property timestamp $visit_at
 * @property integer $is_verify
 * @property string $descript
 * @property User $User
 * @property ClassRoom $ClassRoom
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseClassMember extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('class_member');
        $this->hasColumn('id', 'integer', 5, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '5',
             ));
        $this->hasColumn('user_id', 'integer', 5, array(
             'type' => 'integer',
             'length' => '5',
             ));
        $this->hasColumn('class_room_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('title', 'string', 30, array(
             'type' => 'string',
             'length' => '30',
             ));
        $this->hasColumn('is_manager', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('join_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('visit_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('is_verify', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('descript', 'string', null, array(
             'type' => 'string',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('ClassRoom', array(
             'local' => 'class_room_id',
             'foreign' => 'id'));
    }
}