<?php

/**
 * BaseWeiboTopics
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $topic
 * @property integer $num
 * @property integer $aa_id
 * @property integer $user_id
 * @property User $User
 * @property Aa $Aa
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseWeiboTopics extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('weibo_topics');
        $this->hasColumn('id', 'integer', 6, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '6',
             ));
        $this->hasColumn('topic', 'string', 200, array(
             'type' => 'string',
             'length' => '200',
             ));
        $this->hasColumn('num', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('aa_id', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('Aa', array(
             'local' => 'aa_id',
             'foreign' => 'id'));
    }
}