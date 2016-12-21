<?php 
include 'config.php';
include 'functions.php';
spl_autoload_register('autoLoadClass');	

$db = new DataBase();
$tables = $db->getTables();
if (isset($_GET['table'])) {
    $info = $db->getInfo(cleanData($_GET['table']));
}

include 'templates/index.html';
?>
