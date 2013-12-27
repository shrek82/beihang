<?php

/**
 * BaseNewsSpecial
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $weibo_topic
 * @property integer $weibo_pagesize
 * @property boolean $is_displayweibo_on_home
 * @property boolean $is_displaycomment_on_home
 * @property Doctrine_Collection $Albums
 * @property Doctrine_Collection $Newses
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNewsSpecial extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('news_special');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 16, array(
             'type' => 'string',
             'length' => '16',
             ));
        $this->hasColumn('intro', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('weibo_topic', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('weibo_pagesize', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('is_displayweibo_on_home', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('is_displaycomment_on_home', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Album as Albums', array(
             'local' => 'id',
             'foreign' => 'special_id'));

        $this->hasMany('News as Newses', array(
             'local' => 'id',
             'foreign' => 'special_id'));
    }
}