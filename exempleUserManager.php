<?php
//@session_start();
ini_set("error_reporting", E_ALL);

//require __DIR__ . '/../config.php';
//require __DIR__ . '/../autoload.php';
//require __DIR__ . '/../bootstrap.php';
//use classes\authentication\Authentication;
//use classes\db\Database;
use classes\db\TableBD;


//Create an object 
$table= new TableBD();

//Set the path for the html template
$table->setTemplate(_CAMINHO_MANUTENCAO . "tables.html");

//Set title of the list
$table->setTitle("Users List");

//select the table in the datebase
$table->prepareTable("tabUsers");

//list of fields for list, new, edit and import records
$table->setFieldsAtive("userID, name, email, type, active",'list');
$table->setFieldsAtive("name, email, type, passw, active", 'new');
$table->setFieldsAtive("name, email, type, passw, active", 'edit');
$table->setFieldsAtive("name, email, type, passw, active", 'csv');

//define field name passw as a password, hidding the file 
$table->setCampoPass("passw",0, "md5");

//define lists of values to supplay to a field
$table->setCampoLista("type",1," SELECT `id`,`type` FROM `tabUsersTypes` ORDER BY `type`");
$table->setCampoLista("active",2,"1=>Active,0=>Inactive");

//$tabela->setCampoImagem("relative_path_cache","../fotos/thumbs/",30);

//Link each record on the listo to external page passing the key value
$table->setLinkPage("/public/perfil.php");

//$tabela->setLabel('id_votacao',"#ID");
//$tabela->setLabel('nome',"Nome");
//$tabela->setLabel('data',"Data de Inicio");
//$tabela->setLabel('Encerrada',"Data de Encerramento"); 
//$tabela->setHTMLid("votacaot", $texto);
//$tabela->setLabel('Presidente',"Presidente Eleitoral");
//$tabela->setLabel('Encerrado',"Estado");  
//$tabela->setCriterio("type='video'");
//$tabela->setOrdem("title");
//$tabela->preparaSQLparaAccao('novo');
//$tabela->fazlista();
//$tabela->includes(); 
//$tabela->formulario();
$tabela->fazHTML();
 
 
 //echo "aqui";  

?>
