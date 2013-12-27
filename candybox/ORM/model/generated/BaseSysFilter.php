<?php

/**
 * BaseSysFilter
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $news_id
 * @property integer $event_id
 * @property integer $pic_id
 * @property integer $bbs_unit_id
 * @property integer $order_num
 * @property News $News
 * @property Event $Event
 * @property Pic $Pic
 * @property BbsUnit $BbsUnit
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSysFilter extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('sys_filter');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('news_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('event_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('pic_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('bbs_unit_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('order_num', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('News', array(
             'local' => 'news_id',
             'foreign' => 'id'));

        $this->hasOne('Event', array(
             'local' => 'event_id',
             'foreign' => 'id'));

        $this->hasOne('Pic', array(
             'local' => 'pic_id',
             'foreign' => 'id'));

        $this->hasOne('BbsUnit', array(
             'local' => 'bbs_unit_id',
             'foreign' => 'id'));
    }
}