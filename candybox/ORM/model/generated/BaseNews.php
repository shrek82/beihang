<?php

/**
 * BaseNews
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $category_id
 * @property integer $user_id
 * @property integer $author_id
 * @property integer $special_id
 * @property string $author_name
 * @property string $short_title
 * @property string $title
 * @property string $img_path
 * @property string $images
 * @property string $small_img_path
 * @property string $recommended_path
 * @property string $vice_title
 * @property string $title_color
 * @property string $source
 * @property integer $font_size
 * @property string $intro
 * @property string $content
 * @property string $redirect
 * @property timestamp $update_at
 * @property timestamp $create_at
 * @property boolean $is_draft
 * @property boolean $is_release
 * @property boolean $is_pic
 * @property boolean $is_fixed
 * @property boolean $is_top
 * @property boolean $is_recommended
 * @property boolean $aa_is_top
 * @property boolean $is_focus
 * @property boolean $is_comment
 * @property integer $comments_num
 * @property integer $hit
 * @property integer $dig
 * @property NewsCategory $NewsCategory
 * @property NewsSpecial $Special
 * @property User $User
 * @property Doctrine_Collection $Comments
 * @property Doctrine_Collection $Tags
 * @property SysFilter $SysFilter
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNews extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('news');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('category_id', 'integer', 5, array(
             'type' => 'integer',
             'length' => '5',
             ));
        $this->hasColumn('user_id', 'integer', 5, array(
             'type' => 'integer',
             'length' => '5',
             ));
        $this->hasColumn('author_id', 'integer', 5, array(
             'type' => 'integer',
             'length' => '5',
             ));
        $this->hasColumn('special_id', 'integer', 5, array(
             'type' => 'integer',
             'length' => '5',
             ));
        $this->hasColumn('author_name', 'string', 16, array(
             'type' => 'string',
             'length' => '16',
             ));
        $this->hasColumn('short_title', 'string', 250, array(
             'type' => 'string',
             'length' => '250',
             ));
        $this->hasColumn('title', 'string', 250, array(
             'type' => 'string',
             'length' => '250',
             ));
        $this->hasColumn('img_path', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('images', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('small_img_path', 'string', 150, array(
             'type' => 'string',
             'length' => '150',
             ));
        $this->hasColumn('recommended_path', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('vice_title', 'string', 250, array(
             'type' => 'string',
             'length' => '250',
             ));
        $this->hasColumn('title_color', 'string', 7, array(
             'type' => 'string',
             'length' => '7',
             ));
        $this->hasColumn('source', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('font_size', 'integer', 2, array(
             'type' => 'integer',
             'length' => '2',
             ));
        $this->hasColumn('intro', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('content', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('redirect', 'string', 200, array(
             'type' => 'string',
             'length' => '200',
             ));
        $this->hasColumn('update_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('create_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('is_draft', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_release', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_pic', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_fixed', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_top', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_recommended', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('aa_is_top', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_focus', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_comment', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('comments_num', 'integer', 4, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '4',
             ));
        $this->hasColumn('hit', 'integer', 4, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '4',
             ));
        $this->hasColumn('dig', 'integer', 4, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '4',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('NewsCategory', array(
             'local' => 'category_id',
             'foreign' => 'id'));

        $this->hasOne('NewsSpecial as Special', array(
             'local' => 'special_id',
             'foreign' => 'id'));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasMany('Comment as Comments', array(
             'local' => 'id',
             'foreign' => 'news_id'));

        $this->hasMany('NewsTags as Tags', array(
             'local' => 'id',
             'foreign' => 'news_id'));

        $this->hasOne('SysFilter', array(
             'local' => 'id',
             'foreign' => 'news_id'));
    }
}