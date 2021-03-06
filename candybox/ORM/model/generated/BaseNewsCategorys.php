<?php

/**
 * BaseNewsCategorys
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $news_id
 * @property integer $news_category_id
 * @property boolean $is_fixed
 * @property NewsCategory $NewsCategory
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNewsCategorys extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('news_categorys');
        $this->hasColumn('news_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('news_category_id', 'integer', 3, array(
             'type' => 'integer',
             'length' => '3',
             ));
        $this->hasColumn('is_fixed', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('NewsCategory', array(
             'local' => 'news_category_id',
             'foreign' => 'id'));
    }
}