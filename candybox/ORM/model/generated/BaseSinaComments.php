<?php

/**
 * BaseSinaComments
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $weibo_id
 * @property string $cmt_id
 * @property string $cmt_idstr
 * @property string $text
 * @property string $source
 * @property integer $cmt_uid
 * @property string $cmt_mid
 * @property timestamp $created_at
 * @property string $cmt_name
 * @property string $cmt_screen_name
 * @property string $profile_image_url
 * @property string $avatar_large
 * @property timestamp $colletion_at
 * @property boolean $is_verify
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSinaComments extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('sina_comments');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('weibo_id', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('cmt_id', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('cmt_idstr', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('text', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('source', 'string', 200, array(
             'type' => 'string',
             'length' => '200',
             ));
        $this->hasColumn('cmt_uid', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('cmt_mid', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('created_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('cmt_name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('cmt_screen_name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('profile_image_url', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('avatar_large', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('colletion_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('is_verify', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}