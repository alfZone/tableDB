<?php
//@session_start();

require_once 'config.php';
//require_once __DIR__ . '/../bootstrap.php';

//use classes\db\Database;
//use classes\db\TableBD;

//include_once("Database.php");
include_once("TableBD.php");


ini_set("error_reporting", E_ALL);


$table= new TableBD();
$table->setTemplate("TableBD.html");
$table->setTitle("Users list");
$table->prepareTable("users");
$table->setFieldsAtive("id, nome, login, email, ativo", 'list');
$table->setFieldPass("senha",0, "md5");
$table->setFieldsAtive("nome, login, pass, email, tipo, ativo", 'new');
$table->setFieldsAtive("nome, login, pass, email, tipo, ativo", 'csv');
$table->setFieldsAtive("nome, login, pass, email, tipo, ativo", 'edit');
$table->setFieldList("tipo",1,"SELECT `id`, `userType` FROM `userTypes`  order by `userType` ");
$table->setFieldList("ativo",2,"0=>inativo,1=>ativo");
$table->setLinkPage("viewRecordPage.php");
//$table->setLabel('title',"Título");
//$table->preparaSQLparaAccao('novo');
//$table->fazlista();
//$table->includes(); 
//$table->formulario();
$table->showHTML();
?>
