<?php

/**
 * BaseUserWork
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property string $industry
 * @property string $company
 * @property string $job
 * @property date $start_at
 * @property date $leave_at
 * @property User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUserWork extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user_work');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('industry', 'string', 20, array(
             'type' => 'string',
             'length' => '20',
             ));
        $this->hasColumn('company', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('job', 'string', 30, array(
             'type' => 'string',
             'length' => '30',
             ));
        $this->hasColumn('start_at', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('leave_at', 'date', null, array(
             'type' => 'date',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}