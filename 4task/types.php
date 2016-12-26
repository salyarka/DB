<?php 
$types = [
    'Numbers' => [
        'tinyint', 'smallint', 'mediumint', 'int',
        'bigint', 'decimal', 'float', 'double'
    ],
    'Date and time' => [
        'date', 'datetime', 'timestamp',
        'time', 'year'
    ],
    'Strings' => [
        'char', 'varchar', 'tinytext', 'text',
        'mediumtext', 'longtext'
    ],
    'Lists' => [
        'enum', 'set'
    ],
    'Binary' => [
        'bit', 'binary', 'varbinary', 'tinyblob',
        'blob', 'mediumblob', 'longblob'
    ],
    'Geometry' => [
        'geometry', 'point', 'linestring', 'poligon',
        'multipoint', 'multilinestring', 'multipolygon',
        'geometrycollection'
    ]
];
?>