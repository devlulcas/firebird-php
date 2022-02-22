<?php


require "../vendor/autoload.php";

use FirebdPHP\Firebird\FireBirdPHP;
use FirebdPHP\Firebird\TablesFirebird;

//Create new Table
// (new FirebirdPHP)->createTable('TESTE4', [

//     'name:varchar:255:notNull',
//     'age:integer:notNull',
//     'city:varchar:255:notNull',
//     'state:varchar:255'
    
// ] );


//Show All Tables
(new TablesFirebird)->getTablesInDatabase();