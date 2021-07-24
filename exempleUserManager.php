<?php
@session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../bootstrap.php';

use classes\db\Database;
use classes\db\TableBD;

ini_set("error_reporting", E_ALL);


$table= new TableBD();
$table->setTemplate("templatePath/tables.html");
$table->setTitle("Users list");
$table->prepareTable("users");
$table->setFieldsAtive("id, nome, login, email, ativo", 'list');
$table->setFieldPass("senha",0, "md5");
$table->setFieldsAtive("nome, login, pass, email, tipo, ativo", 'new');
$table->setFieldsAtive("nome, login, pass, email, tipo, ativo", 'csv');
$table->setFieldsAtive("nome, login, pass, email, tipo, ativo", 'edit');
$table->setCampoLista("tipo",1,"SELECT `id`, `userType` FROM `userTypes`  order by `userType` ");
$table->setCampoLista("ativo",2,"0=>inativo,1=>ativo");
$table->setPaginaVer("viewRecordPage.php");
//$tabela->setLabel('title',"Título");
//$tabela->preparaSQLparaAccao('novo');
//$tabela->fazlista();
//$tabela->includes(); 
//$tabela->formulario();
$table->showHTML();
?>
