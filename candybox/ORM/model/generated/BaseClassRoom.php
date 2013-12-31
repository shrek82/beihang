<?php

/**
 * BaseClassRoom
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $start_year
 * @property integer $finish_year
 * @property string $name
 * @property integer $member_num
 * @property string $intro
 * @property string $notice
 * @property date $create_at
 * @property string $school
 * @property string $education
 * @property string $institute
 * @property string $institute_no
 * @property timestamp $update_at
 * @property string $speciality
 * @property integer $verify
 * @property Doctrine_Collection $Members
 * @property Doctrine_Collection $ClassBbses
 * @property Doctrine_Collection $Comments
 * @property Doctrine_Collection $Themes
 * @property Doctrine_Collection $Applys
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseClassRoom extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('class_room');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('start_year', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('finish_year', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('member_num', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('intro', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('notice', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('create_at', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('school', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('education', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('institute', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('institute_no', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('update_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('speciality', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('verify', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('ClassMember as Members', array(
             'local' => 'id',
             'foreign' => 'class_room_id'));

        $this->hasMany('ClassBbs as ClassBbses', array(
             'local' => 'id',
             'foreign' => 'classroom_id'));

        $this->hasMany('Comment as Comments', array(
             'local' => 'id',
             'foreign' => 'class_room_id'));

        $this->hasMany('Theme as Themes', array(
             'local' => 'id',
             'foreign' => 'classroom_id'));

        $this->hasMany('JoinApply as Applys', array(
             'local' => 'id',
             'foreign' => 'class_room_id'));
    }
}