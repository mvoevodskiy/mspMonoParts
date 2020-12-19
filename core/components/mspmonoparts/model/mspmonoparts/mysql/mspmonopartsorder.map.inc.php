<?php
$xpdo_meta_map['mspMonoPartsOrder']= array (
  'package' => 'mspmonoparts',
  'version' => '1.1',
  'table' => 'ms2_private_parts_orders',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'order_id' => NULL,
    'token' => NULL,
  ),
  'fieldMeta' => 
  array (
    'order_id' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'string',
      'precision' => '255',
      'null' => false,
    ),
    'token' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'string',
      'precision' => '255',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'token' => 
    array (
      'alias' => 'token',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'token' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Order' => 
    array (
      'class' => 'msOrder',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
