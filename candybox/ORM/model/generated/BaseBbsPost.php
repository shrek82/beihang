<?php

/**
 * BaseBbsPost
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $bbs_unit_id
 * @property clob $content
 * @property string $hidden
 * @property BbsUnit $BbsUnit
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBbsPost extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('bbs_post');
        $this->hasColumn('id', 'integer', 5, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '5',
             ));
        $this->hasColumn('bbs_unit_id', 'integer', 5, array(
             'type' => 'integer',
             'length' => '5',
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('hidden', 'string', null, array(
             'type' => 'string',
             ));

        $this->option('type', 'MYISAM');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('BbsUnit', array(
             'local' => 'bbs_unit_id',
             'foreign' => 'id'));
    }
}