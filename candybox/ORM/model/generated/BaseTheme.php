<?php

/**
 * BaseTheme
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $aa_id
 * @property integer $club_id
 * @property integer $classroom_id
 * @property string $theme
 * @property string $background_image
 * @property string $background_color
 * @property boolean $usercustom
 * @property integer $banner_limit
 * @property integer $news_limit
 * @property integer $weibo_limit
 * @property integer $event_limit
 * @property integer $bbsunit_limit
 * @property boolean $allow_post_weibo
 * @property string $weibo_topic
 * @property Aa $Aa
 * @property Club $Club
 * @property ClassRoom $ClassRoom
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTheme extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('theme');
        $this->hasColumn('id', 'integer', 6, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '6',
             ));
        $this->hasColumn('aa_id', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('club_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('classroom_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('theme', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('background_image', 'string', 150, array(
             'type' => 'string',
             'length' => '150',
             ));
        $this->hasColumn('background_color', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('usercustom', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('banner_limit', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('news_limit', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('weibo_limit', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('event_limit', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('bbsunit_limit', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('allow_post_weibo', 'boolean', null, array(
             'type' => 'boolean',
             'default' => true,
             ));
        $this->hasColumn('weibo_topic', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Aa', array(
             'local' => 'aa_id',
             'foreign' => 'id'));

        $this->hasOne('Club', array(
             'local' => 'club_id',
             'foreign' => 'id'));

        $this->hasOne('ClassRoom', array(
             'local' => 'classroom_id',
             'foreign' => 'id'));
    }
}