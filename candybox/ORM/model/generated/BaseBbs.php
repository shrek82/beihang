<?php

/**
 * BaseBbs
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $aa_id
 * @property integer $parent_id
 * @property integer $club_id
 * @property string $name
 * @property integer $order_num
 * @property string $intro
 * @property Aa $Aa
 * @property Club $Club
 * @property Doctrine_Collection $Units
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBbs extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('bbs');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('aa_id', 'integer', 5, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '5',
             ));
        $this->hasColumn('parent_id', 'integer', 5, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '5',
             ));
        $this->hasColumn('club_id', 'integer', 5, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '5',
             ));
        $this->hasColumn('name', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('order_num', 'integer', 6, array(
             'type' => 'integer',
             'length' => '6',
             ));
        $this->hasColumn('intro', 'string', 200, array(
             'type' => 'string',
             'length' => '200',
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

        $this->hasMany('BbsUnit as Units', array(
             'local' => 'id',
             'foreign' => 'bbs_id'));
    }
}