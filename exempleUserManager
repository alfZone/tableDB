<?php
@session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../bootstrap.php';

use classes\db\Database;
use classes\db\TableBD;
use classes\authentication\Authentication;
//use DOMDocument;
//use DomXPath;

ini_set("error_reporting", E_ALL);


$aut= new Authentication();
if (!$aut->isLoged()){
  header('Location: http://galeria.esmonserrate.org/public/login');
}

  //echo "aqui";

$tabela= new TableBD();
$tabela->setTemplate("../templates/matrixAdmin/tables.html");
$tabela->setTitulo("Lista de Utilizadores");
$tabela->preparaTabela("users");
$tabela->setAtivaCampos("id, nome, login, email, ativo", 'ver');
$tabela->setCampoPass("senha",0, "md5");
$tabela->setAtivaCampos("nome, login, pass, email, tipo, ativo", 'novo');
$tabela->setAtivaCampos("nome, login, pass, email, tipo, ativo", 'csv');
$tabela->setAtivaCampos("nome, login, pass, email, tipo, ativo", 'editar');
$tabela->setCampoLista("tipo",1,"SELECT `id`, `userType` FROM `userTypes`  order by `userType` ");
$tabela->setCampoLista("ativo",2,"0=>inativo,1=>ativo");
$tabela->setPaginaVer("viewRecordPage.php");
//$tabela->setLabel('title',"Título");
//$tabela->preparaSQLparaAccao('novo');
//$tabela->fazlista();
//$tabela->includes(); 
//$tabela->formulario();
$tabela->fazHTML();
?>
