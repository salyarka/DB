<?php 
include 'config.php';
include 'types.php';
include 'functions.php';
spl_autoload_register('autoLoadClass');	

$db = new DataBase();
$tables = $db->getTables();
if (isset($_GET['table'])) {
    $info = $db->getInfo(cleanData($_GET['table']));
}
if (isset($_POST['type'])) {
    $db->changeType(
        cleanData($_POST['edit_table']),
        cleanData($_POST['edit_field']),
        cleanData($_POST['type']),
        cleanData($_POST['lengh'])
    );
    header('Location: ' . $_SERVER['PHP_SELF'] . '?table=' . cleanData($_GET['table']));
}
if (isset($_POST['delete_from_table'])) {
    $db->delField(
        cleanData($_POST['delete_from_table']),
        cleanData($_POST['delete_field'])
    );
    header('Location: ' . $_SERVER['PHP_SELF'] . '?table=' . cleanData($_GET['table']));
}
if (isset($_POST['new_field'])) {
    $db->addField(
        cleanData($_POST['new_field_table']),
        cleanData($_POST['new_field']),
        cleanData($_POST['new_type']),
        cleanData($_POST['new_lengh'])
    );
    header('Location: ' . $_SERVER['PHP_SELF'] . '?table=' . cleanData($_GET['table']));
}
include 'templates/index.html';
?>
