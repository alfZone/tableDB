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
$table->preparaTabela("users");
$table->setAtivaCampos("id, nome, login, email, ativo", 'ver');
$table->setCampoPass("senha",0, "md5");
$table->setAtivaCampos("nome, login, pass, email, tipo, ativo", 'novo');
$table->setAtivaCampos("nome, login, pass, email, tipo, ativo", 'csv');
$table->setAtivaCampos("nome, login, pass, email, tipo, ativo", 'editar');
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
