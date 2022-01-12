<?php


require "../vendor/autoload.php";

use FirebdPHP\FireBirdPHP;

//Create new Table
(new FirebirdPHP)->createTable('TESTE4', [

    'name:varchar:255:notNull',
    'age:integer:notNull',
    'city:varchar:255:notNull',
    'state:varchar:255'
    
] );


//Show All Tables
(new FireBirdPHP)->getTablesInDatabase();