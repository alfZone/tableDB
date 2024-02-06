<?php
/**
 * The idea for this object is to provide a simple way to manage a database table. With some configurations we can list a tables, add a new record, change and update a record, delete 
 * a record and insert several records using a csv file.
 * @author António Lira Fernandes
 * @version 10.0
 * @updated 01-08-2023 21:50:00
 https://github.com/alfZone/tabledb
 https://github.com/alfZone/tabledb/wiki
 https://console.developers.google.com/apis/dashboard
 
 */

// problems detected
// The second field should be text because it is used in the delete confirmation window. An unknown problem occurs if the second field is an image.

// roadmap 


//news of version: 
//         Introduce an enhanced debugging mechanism with the method setDebugShow($value).




namespace classes\db;
use classes\db\Database;
use classes\simplehtmldom\simple_html_dom;
use classes\errors\Log;
use DOMDocument;
use DomXPath;



//echo "aquui";
class TableBD{
	// REQUIRES
	// Database.php
	
  // MISSION: generate a table to manage a query to the database. Considers 4 actions: view, new, edit and import
  
// METHODS
// __construct() - Class Constructor
// fieldsActive($value, $action) - Makes the fields visible by passing the value = 1 (or without passing a value) and hides it by passing the value = 0. The 
//																 fields are active (visible) when the value passed is 1 and disabled when the value is 0. "Action" defines the behavior of 
//																 viewing, creating, editing.
// querySQL($sql) - Returns a list of data from a given SQL.
// determinaChave() - Analyzes the database table structure and determines the key.
// devolveValorDaLista($field, $key) - Finds the value of a field for a given key. $field is the name of the field to be consulted and $key is the id of the 
//                                     value to be searched.
// encriptar($text, $cipher = "md5") - Encrypts text using a specified cipher method. $text is the text to be encrypted and $cipher is the type of cipher used.
// fazLista() - Creates an HTML table with all the records, allowing sorting by column, text search, and pagination (25 records per page by default).
// formulario($record = "") - Displays an HTML form for editing or inserting a record.
// getCampoValor($campo) - Returns an appropriate string for constructing an SQL field, including quotes and without quotes. $campo is an array with field 
//                         information, including label, field, type, value, etc.
// getCampos() - Returns the list of fields in the table.
// getChave() - Reads the key parameter from the HTML form, which corresponds to the key value determined from the table analysis.
// getDados($key) - Returns results for a given key value.
// getDadosForm() - Searches using $_REQUEST for fields to be read, only reading fields with values.
// getDo() - Reads the 'do' parameter from the HTML form.
// getTextos($key) - Returns a pre-entered text. $key is the name of the field to get the value for.
// includes() - Sets of includes necessary for the record list table format.
// inputHTML($field, $value) - Returns appropriate HTML for constructing a field of the specified type. $field is an array with field information, including 
//                             label, field, type, etc., and $value is the default value to be included in the field.
// preparaSQLGeral() - Prepares a string with the table's SELECT * FROM Table SQL statement, including all fields.
// preparaSQLdelete() - Prepares an SQL string for deleting a record.
// prepareSQLinsert() - Prepares an SQL string for inserting fields with values.
// prepareSQLtoAction($action) | preparaSQLparaAccao($accao) - Prepares a string with the SELECT LIST OF FIELDS FROM Table SQL statement, only including fields 
//                                                             marked as visible in the specified action (new, edit, list, import).
// preparaSQLupdate() - Prepares an SQL string for updating fields with values.
// prepareTable($table) - Prepare a table by creating a list of fields, determining its key, and preparing a general SQL for all fields. 
// redirecciona($ url = "? do = l") - Redirect to the page displaying the list:
// setAutentication($ value) - Defines user's default permissions: a - all permissions, u - update only, r - read only, e - edit only, n - new only.
// setAllFieldAtive($action,$value) - Set visibility of all table fields for a given action (list, edit, add). 1 for visible, 0 for hidden.
// + setCalculatedField($nameField,$sqlCalcFormula) - Add a new calculated field with the name of $nameField, the result of the SQL operation $sqlCalcFormula.
// setDebugShow($value) - Enable debug mode to view the SQL queries and better comprehend errors. The value is a flag that can take a value of 1 to display SQL strings or 0 to 
//						  hide them.
// + setDefaultValue ($ field, $ value) - Set default value for a new entry. Field is the field to set, value is the initial value.
// setFieldAtive($field, $action, $value) - Show/hide a field for a specific action (list, edit, add). 1 for show, 0 for hide.
// setFieldsAtive($fields, $action) - Show a list of fields for a specific action (list, csv, edt, new). Fields not listed are hidden.
// setJSAction($field, $action) - Set JavaScript action for a field ($field). $Field is the name of field to add a javascript action, and $action is the action you want to call
// setFieldList($field,$mode,$listOrSql, $hideCode=0) - Change field to list type for better description and combo box during editing. Mode: 1-SQL, 2-values, 
//																											3-SQL+values. $field is the SQL field we want to change to the list type, $mode is the way the values  
//																											are loaded: 1 - SQL; 2 - values; 3 - SQL + values and $listOrSql is the sql string or list of values  
//																									    to be passed (the list has the format eg "1 => first, 2 => second, 3 => useful, a => like this") or
//																											SQL instroction|list of values. example "select code,description form table order by descripton|1 => 
//																											first, 2 => second"
// setFieldPass($field,$mode,$cipher) - Change field to password type for hidden text and encryption before saving. Mode: 0-off, 1-repeated input, 2-show. 
//																		  Cipher: "", "md5", "sha1", "base64".
// setImageField($field,$path,$percentage='100%',$defaultImage="") - Change $field to image type for special display in the list. $path is the image location, 
//                                                                   $percentage is image height, $defaultImage is default image.
// setCriterio($ criterio) - Define view criteria using an SQL "where" clause.
// setOrder($order) -Set SQL string to order data in the table.
// setHTMLid($ id, $ value) - Write to an HTML element on the page with the specified id. The id is the id of the HTML tag and the value is the string to be loaded into the element.
// setLabel($ field, $ value) - Assign a label to a field, where the field is the field you want to change the label for and the value is the text to be used as the label.
// setLabels() - Assign the field names in the database as field labels, this function is only executed when preparing the table.
// setLimites($ NumReg, $ LimInf = 0) - Set the number of records in a select statement, where NumReg is the number of records and LimInf is the starting record.
// setLinkPage($page, $style=0) | setPaginaVer($page, $style=0) - Store the name (URL) of the page to be opened to view a record. The page parameter is the URL 
//                                                                of the HTML page to show a record, and the style parameter is how the key value is passed. 
//                                                                If style=0, then the URL is URL?id=keyValue, if style=1, then the URL is URL/keyValue.
// setTemplate($path) * - Assign a template to the table, where the path is the path to the template file.
// setTitle($value) | setTitulo($ value) - Set the title of the page or form, where the value is the text for the title.
// showHTML() - Creates an HTML table with the data, allowing for record insertion, editing, and deletion. Uses a 'do' parameter to make decisions.
	
	

//########################################## Variaveis ###############################################################################	
	
	/**
	 * This array will receive all the output texts of the class.
	 */
  private $textos=array("titulo"=>"Lista de registios da tabela", "import"=>"Os campos tem de ser importados pela seguinte ordem", 
  											"importline"=>"linhas a serem importadas");
  /**
	 * array of id and value pairs for HTML tag
	 */
  private $id;
	
  private $debugS=0;
  private $camposLista;
  private $template="../templates/gestor2/tables.php";
  private $tabela;
  private $sqlGeral;
  private $chave;
  private $PagaClose="?do=l";
  private $PagVer="";
  private $linkStyle="";
  private $PagImp=0;
  private $criterio="(1=1)";
  private $order="1=1";
  private $autenticacao="a";  //defines if by default the user has permissions to:
                                  // a - all have the possibility to view, create new, delete and change
                                  // u - update Can only change data
                                  // r - read can only see
  private $limites=array(0,0);

  //###################################################################################################################################

	/**
	 * Construtor de Classe
	 */
  public function __construct(){  

  }
  
//###################################################################################################################################	
	/**
  *
  * @param $value    when the value passed is 1 the field is active (visible) and when the field is 0 the field is disabled
  * @param $action   sets behavior in see, new, edit
	*
  * Makes the fields to be displayed visible by passing value=1 (or not passing value) and hides passing value=0
	*/
	private function fieldsActive($value, $action){
    
		$action=str_replace("list","ver",$action);
		$action=str_replace("see","ver",$action);
		$action=str_replace("new","novo",$action);
		$action=str_replace("edt","editar",$action);
		$action=str_replace("editarar","editar",$action);
		$i=0;
		foreach($this->camposLista as $campo){
			$this->camposLista[$i][$action]=$value;
			$i++;
		}
	}
  
  //###################################################################################################################################
	/**
	 * 
	 * @param sql    SQL Instruction
	 *
	 * given a sql returns a list of data.
	 */
	public function querySQL($sql){
		$database = new Database(_BDUSER, _BDPASS, _BD);
        $database->query($sql);
			
		return $database->resultset();	
	}
  
  	//###################################################################################################################################	
	
	/**
	* Analiza a estrutura da tabela da base de dados e determina qual é a chave
	*/
	private function determinaChave(){
		//$chave="";
		//print_r($this->camposLista);
        //echo "<br>";
		foreach($this->camposLista as $campo){
            //print_r($campo);
            //echo "<br>______________________<br>";
			if ($campo['Key']=="PRI"){
				$this->chave=$campo['Field'];	
			}
		}
	}
	
	
	//###################################################################################################################################	
	
	/**
	 * 
	 * @param campo   nome do campo a ser consultada a lista de valores
	 * @param chave   id do valor a ser procurado
	 *
	 * procura o valor de um campo para uma determinada chave.
	 */
	private function devolveValorDaLista($campo, $chave){
		//$chave="";
		//print_r($this->camposLista);
		$devolve=$chave;
		foreach($this->camposLista as $campo1){
			if ($campo1['Field']==$campo){
			//print_r($campo1);
				foreach($campo1['lista'] as $linha){
					$i=0;
					$proximo=0;
					foreach($linha as $x => $x_value) {
						//echo $x_value;
						if (($x_value==$chave) && ($i==0)){
							$proximo=1;
						}
						if (($proximo==1) && ($i==1)){
							if ($campo1['hideCode']==1){
								$devolve=$x_value;
							}else{
								$devolve=$x_value . " [" . $chave ."]";
							}
						}
						$i++;
					}
				}
		
			}
		}
		return $devolve;
	}
	
	 	//###################################################################################################################################	
	
	/**
   * 
   * @param string $campo   name of the field to search in the list of values
   * @param string $chave   id of the value to be searched for
   *
   * search for the value of a field for a given key.
   */
  private function devolveValorDaListaNew($campo, $chave){
    $devolve = $chave;
    foreach($this->camposLista as $campo1){
      if ($campo1['Field'] == $campo){
        foreach($campo1['lista'] as $linha){
          if ($linha[0] == $chave){
            $devolve = ($campo1['hideCode'] == 1) ? $linha[1] : $linha[1] . " [" . $chave ."]";
            break 2;
          }
        }
      }
    }
    return $devolve;
  }
	//###################################################################################################################################
	/**
	 * 
	 * @param texto    texto a ser enciptado
	 * @param crifra   tipo de cifra usada
	 *
	 * encripta um texto segundo um método passado.
	 */
	function encriptar($texto, $cifra="md5"){
		$resposta=$texto;
		switch ($cifra){
			case "md5":
				$resposta=md5(trim($texto));
				break;
			case "sha1":
				$resposta=sha1(trim($texto));
				break;
			case "base4":
				$resposta=base64_encode(trim($texto));
				break;
			}	
		return $resposta;
	}
  
  
 //###################################################################################################################################
	/**
	 * 
	 * @param sql    instrução SQL
	 *
	 * dado um sql devolve uma lista de dados.
	 */
	function ExecuteSQL($sql){
		$database = new Database(_BDUSER, _BDPASS, _BD);
        $database->query($sql);
		  $database->execute();
		//return $database->resultset();
	}
	
	//###################################################################################################################################
	/**
		* It does what is necessary to keep the table in an html page. Lists data and allows you to insert new, edit and delete records. Use a 'do' parameter to make decisions
		*/
	// TEM DE SER TODO REFORMULADO
	
	public function showHTML(){	
		$linkContinue= '<br><a href="">Continue</a>';
    	//lê o parametro 'do' do form HTML
		$action=$this->getDo();
    	//echo "<br>Faz: $faz<br><br>";
		switch($action){
				//options
			case "":
			case "l":
				//echo $this->prepareSQLtoAction('ver');
				$this->fazlista();
        		$this->includes();
				break;
				//prepara a importação
			case "pcsv":
			case "pimp":
				$this->includes();
				$this->formImporta();
				break;
			case "csv":
			case "imp":
				$this->importCSV();
				if ($this->debugS!=1){
					$this->redirecciona();
				}else{
					//echo $sql . "<br>";
					echo $linkContinue;
				}
				break;
				//formulario para editar
			case "e":
			case "edit":
				//echo "recebi";
				//$chave=$this->getChave();
				$chave=$this->getId();
				//echo $chave;
				$registo=$this->getDadosUpdate($chave);
				//print_r($registo);
				//return $registo;
				echo $myJSON = json_encode($registo);
				//$this->includes(); 
				//$this->formulario($registo);
				break;
				//formulário para introduzir os valores
			case "ci":
				//efectuar a inserção
        		//echo "ci";
				$this->getDadosForm();
				$sql= $this->prepareSQLinsert();
				//echo $sql;
			  	//$this->consultaSQL($sql);
        		$this->ExecuteSQL($sql);
				if ($this->debugS!=1){
					$this->redirecciona();
				}else{
					echo $sql . "<br>";
					echo $linkContinue;
				}
				break;
			case "ce":
				//efectuar a edição
				$this->getDadosForm();
				$sql= $this->preparaSQLupdate();
				//echo $sql;
				$this->ExecuteSQL($sql);
				if ($this->debugS!=1){
					$this->redirecciona();
				}else{
					echo $sql . "<br>";
					echo $linkContinue;
				}
				break;
			case "cd":
				//efectuar o apagar
				$this->getDadosForm();
				$sql= $this->preparaSQLdelete();
				//echo $sql;
				$this->ExecuteSQL($sql);
				if ($this->debugS!=1){
					$this->redirecciona();
				}else{
					echo $sql . "<br>";
					echo $linkContinue;
				}
				break;
		}
	}


   //###################################################################################################################################
  	/**
	* Makes an HTML table with the list of all records in the table. This table allows you to sort by column, search for texts and shows 
  	* a set of records (25 by default) and allows browsing pages
	* conjunto de registos (25 por defeito) e permite navegar em páginas
	*/
	public function fazLista(){
    
    	$html = new simple_html_dom();
    	$html->load_file($this->template);
     
		//prepare a form delete
		foreach($html->find('#deleteKey') as $e)
			$e->outertext = '<input type="hidden" id="deleteKey" name="txt' .$this->chave . '" value="">'; //tirei o id
		
		//prepare a csv import form
		foreach($html->find('#importLst') as $e)
			$e->innertext = $this->fazListaCamposAccao("csv"); //tirei o id
		
		//print_r($this->camposLista);
		//list of values
		// table head line
		$text=PHP_EOL."<tr>". PHP_EOL;
		$i=0;
		$pi=0; //this varible is use to controlo a list os fields when the keyfield is not visible
		foreach($this->camposLista as $campo){
			//print_r($campo);
			//echo "<hr>";
			if ($this->chave==$campo['Field'] && $campo['ver']==1){
				$pi=1;
			}
			if ($campo['ver']==1){
				//echo $campo['label'];
				$text .= "<th>" . $campo['label']. "</th>" . PHP_EOL;
				if ($campo['Type']=="lst"){
					$carimbo=$campo['Field'];
					//echo $carimbo;
				}else {
					$carimbo=0;
				}
				$elista[$i]=$carimbo;
				if ($campo['Type']=="img"){
					$carimbo=$campo['Field'];
					$imgHTMLpre[$i]= '<img src="' . $campo['Path'];
					$ia=$campo['Field'];
					//if (($carimbo=="")||$carimbo=null){
						//$carimbo=$campo['defaultImage'];
					//}
					$imgHTMLpos[$i]='" class="img-thumbnail" alt="'. $campo['Field'] .'" style="width:' . $campo['widthP'] . '%; height=20%">'.PHP_EOL;
					//echo $carimbo;
					$imgDefault[$i]=$campo['defaultImage'];
				}else {
					$carimbo=0;
				}
				$eImagem[$i]=$carimbo;
				$i++;
			}
    	}  
        
		switch ($this->autenticacao){
			case "a":
				// get csv buttom
				if ($this->PagImp==1){
					foreach($html->find('#bcsv') as $e)
					$k=$e->outertext;           
				}else{
					$k="";
				}
				// get add buttom
				//echo $k;
				foreach($html->find('#bnew') as $e)
					$text .="<th class='buttons'>"  . PHP_EOL . $e->outertext .$k ."</th>" . PHP_EOL;			
				break;
				case "r":
					$text .="";
					break;
			default:
				$text .="<th></th>" . PHP_EOL;
				break;
		}
    
    foreach($html->find('.titleTable') as $e)
        $e ->innertext=$text . "</tr>". PHP_EOL;
    //___ End of table head
    
    
    //--- begin of table  
    $text="";
    $bEdit="";
    $bSee="";
    $bDelete="";
    // ver que página estamos a ver
    
    $pagina = (isset($_REQUEST["p"])?($_REQUEST["p"]):1);		

	$sql=$this->prepareSQLtoAction("ver");
    //echo "<br>sql=" . $sql;
	$stmt=$this->querySQL($sql);
	//print_r($stmt);
  	foreach($stmt as $registo){
		$text .= "<tr>" . PHP_EOL;
		//print_r($registo);
		//if ($this->chave==$registo['Fie'])
      	//echo "<br>chave=" . $this->chave;
      	$chave=$registo[$this->chave];
		$chaveid=$this->chave;
		$i=0;
      	//verifica se é para mostrar um link para ver um registo usando uma página externa
      	$ver="";
      	if ($this->PagVer<>""){
        	foreach($html->find('.bsee[href]') as $e){
          		//echo $e;
		  		if ($this->linkStyle==0){
					$e->href=$this->PagVer . "?$chaveid=$chave";
		  		}else{
					$e->href=$this->PagVer . "/$chave";
		  		}
        	}
        	foreach($html->find('.bsee') as $e){
          		$ver =  $e->outertext;
        	}
        	//echo $ver;      
        	//$ver="<a href='" . $this->PagVer . "?$chaveid=$chave' title='ver' \'> <i class='fa fa-eye' aria-hidden='true'></i></a>";
      	}
		//print_r($elista);
      	$p=$pi;
		foreach($registo as $campo){
			//print_r($campo);
        	if ($p!=0){
          		if ($elista[$i] !== 0){
					$campo=$this->devolveValorDaLista($elista[$i], $campo);
            		//print_r($campo);
					 //echo "aqui";
				}
          		if ($eImagem[$i] !== 0){
            		//$campo="isto é uma imagem";
					if (($campo=="")||($campo==null)){
						$campo=$imgDefault[$i];
						//$campo="aaa.img";
					}
            		$campo=$imgHTMLpre[$i] . $campo . $imgHTMLpos[$i];
          		}
          		$i++;
				$text .= "<td>$campo</td>" . PHP_EOL;
          		//$p=1;    
        	}else{
          		$p=1;
        	}
        
        	if ($i==2){
          		$dois=$campo;
        	}		
		}
      	switch ($this->autenticacao){
        	case "a":
                foreach($html->find('.bedit') as $e){
                	$e->data=$chave;
                  	$e->onClick="preUp('" . $chave . "')";
                  	$text .="<td class='buttons'>" . $ver .  $e->outertext;
                }        
                foreach($html->find('.bdel') as $e){
                  	$e->data=$chave;
                  	$e->onClick="preDel('" . $chave . "','" .$chave. " - ". $dois . "')";
                  	$text .= $e->outertext ."</td>" . PHP_EOL. "</tr>" . PHP_EOL;
                } 
                break;
        	case "u":
        	case "e":
                foreach($html->find('.bedit') as $e)
                	$e->onClick="preUp('" . $chave . "')";
                  	$text .="<td class='buttons'>" . $ver. $e->outertext ."</td>" . PHP_EOL . "</tr>" . PHP_EOL;
                break;
			case "r":
				$text .= "";
                break;
        	default:
                $text .= "<td class='buttons'>$ver</td>" . PHP_EOL ."</tr>" . PHP_EOL;
                break;
        }
    }
    
    foreach($html->find('#bodyTable') as $e)
    	$e ->innertext=$text . PHP_EOL;  
    //--- end of table

    $formAU=$this->formulario();    
    foreach($html->find('#frmIU') as $e)
        $e ->outertext= PHP_EOL. PHP_EOL. PHP_EOL . $formAU . PHP_EOL. PHP_EOL. PHP_EOL;  
    
    // change te title
    foreach($html->find('.tbTitle') as $e)
        $e->innertext = $this->textos['titulo'];
    //echo "aaaaaa";
    echo $html;
    
  }
  
  
//####################################################################################################################################
  /*
  *
  * @param accao    cada campo da tabela pode estar associado a um a acção (ver, editar, apagar, inserir, importar)
	*
  * Faz uma lista seprada por virgulas de campos por acção
  */
  private function fazListaCamposAccao($accao="csv"){
    $texto="";
    $sep="";
    foreach ($this->camposLista as $campo){
      //print_r($campo);
      if (isset($campo[$accao])){
         if ($campo[$accao]==1){
			$aux="";
			if ($campo['Null']=="NO"){
				$aux="*";
			}
            $texto=$texto . $sep . $campo['Field'] . $aux;
            $sep=";";
         }
      }  
    }
    return $texto;
  }
  

  
  //###################################################################################################################################
	/*
	* Apresenta um formulário HTML para importação de ficheiro csv
	*/
  
  //ISTO É PARA SER ADICIONA AO MODELO COM MODAL
  
	public function formImporta(){		
				?>
	
 			<div class="container">
				<section>
          <h3>Importar         
          </h3>
          <p><?php echo $this->getTextos('import');?> <code><?php echo $this->fazListaCamposAccao("csv")?></code>
          </p>
				<form action="?do=csv" method="post" role="form" class="form"  id="Form1"> 
				<div class="row">
					<div class="col-sm-12" >
						<div class="form-group">
                <label for="comment"><?php echo $this->getTextos('importLine');?>:</label>
               <textarea class="form-control" rows="10" id="txtCSV" name="txtCSV"></textarea>
            </div>
	        	
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<input type="button" class="btn btn-info" value="<?php echo $this->getTextos("fechar")?>" onclick="window.location='<?php echo $this->PagaClose?>';">  
            <button class="btn btn-primary" aria-hidden="true" type="submit"><?php echo $this->getTextos("importa")?></button>
            </div>
				</div>
        </form>
				</section>
			</div>
		<?php
			//$pag->postFormJavascript();		
	} 

//###################################################################################################################################
	/*
	* Apresenta um formulário HTML para editar ou inserir um registo
	* Prepare form to edit or new
	*/
	public function formulario($toDo="e"){		
		$html = new simple_html_dom();
		$html->load_file($this->template);
    
    // change h3
    foreach($html->find('.tbTitle') as $e)
        $e->innertext = $this->textos['titulo'];
    
	$accao="editar";
    if ($toDo=="a"){
       $accao="novo";
    }
    
    $t=""; 
   	//preparing fields 
    //print_r($this->camposLista);
   	foreach($this->camposLista as $campo){
		//print_r($campo);
     
		//echo "<br>_____________________________<br>";
     	//echo "<br>accao:" . $accao ;
		if (!isset($campo[$accao])){
			$campo[$accao]=0;
		}
		if ($campo[$accao]==1){
			$aux="";
			if (isset($campo['Default'])){
				$aux=$campo['Default'];
			}
			$t.=$this->inputHTML($campo);
		}
		if ($campo['Field']==$this->chave){
			if ($campo[$accao]!=1) {
				$t.= '<input type="hidden" id="editKey" name="txt' . $campo['Field'] . '" value="">';   //tirei o id
			}else{
				$sub='id="txt' . $this->chave . '"'; 
				$t=str_replace($sub, 'id="editKey"', $t);
			}
    	} 
	}
   
    foreach($html->find('#frmIOH3') as $e)
        $e->innertext= PHP_EOL. PHP_EOL.$t. PHP_EOL. PHP_EOL;
    
   	$modalAU="";
   	foreach($html->find('#frmIU') as $e)
        $modalAU=$e->outertext;
        
    return $modalAU;
	} 

	//###################################################################################################################################	
	/**
	* 
	* @param campo    array com a informação de um campo, inclui: label, Field, Type, valor e etc
	*
	* devolve uma string adequada para construir uma SQL campos com aspas e sem aspas
	*/
	public function getCampoValor($campo){
		$aux=substr($campo['Type'], 0, 3);
		
		//echo "aux: " . $aux;
		
		switch ($aux) {
			case "int":
			case "blo":
			case "enu":
			case "tin":
			case "sma":
			//case "med":
			case "big":
			case "flo":
			case "dou":
			//case "dec":
			case "bit":
			case "num":
			case "mon":
			case "rea":
				$resp=$campo['valor'];
          		//$resp="'" . $resp. "'";
				$resp=str_replace(",",".",$resp);
				//$resp=str_replace(".",",",$resp);
				//$resp=str_replace("x",".",$resp);
				break;
			case "var":	
			case "dat":
			case "tex":
			case "cha":
			case "lon":
			case "tim":
			case "yea":
			case "nva":
			case "dec":
			case "nte":
			case "lst":
			case "med":
			case "tim":
			case "img":
				$resp=$campo['valor'];
				$resp=str_replace('"',"'",$resp);
				$resp='"' . $resp. '"';
				break;
			case "pas":
				//incluir encriptação
				$resp=$this->encriptar($campo['valor'], $campo['cifra']);
				$resp="'" . $resp. "'";
				break;
			case "tin":
				break;
		} 
		return $resp;
	}
	
//###################################################################################################################################
	/**
	* Devolve a lista de campos da tabela
	*/
	public function getCampos(){
		print_r($this->camposLista);
	}
	
  
	//###################################################################################################################################	
	/**
	 *
	 * lê o parametro chave do registo enviado pelo form HTML e que corresponde ao valor identificado como chave na análise da tabela
	 */
  	public function getChave(){
    
    	$chave=utf8_encode($_REQUEST[$this->chave]);
		//echo "<br><br><br><br><br><br><br><br><br><br>$chave<br>";
		return $chave;		
  	}
  
 //###################################################################################################################################	
	/**
	 *
	 * lê o parametro 'do' do form HTML
	 */
	public function getId(){
		$id="";
		//echo $_REQUEST['id'];
		if (isset($_REQUEST['id'])){
			$id=utf8_encode($_REQUEST['id']);
		}
		//echo "<br>do=$do";
		return $id;
	} 
  
 //###################################################################################################################################
	/*
	* dado um valor chave devolve os resultados
	*/
	public function getDados($chave){
		$this->determinaChave();
		$this->preparaSQLGeral();
		$sql=$this->sqlGeral . " WHERE " . $this->chave . " = '" . $chave . "'";
		return $this->querySQL($sql);
	} 
  
   //###################################################################################################################################
	/*
	* dado um valor chave devolve os resultados
	*/
	public function getDadosUpdate($chave){
		$this->determinaChave();
		$sql=$this->preparaSQLSelectToUpdate();
		//$sql .= " WHERE " . $this->chave . " = '" . $chave . "' AND " . $this->criterio . ";";
    	$sql .= " WHERE " . $this->chave . " = '" . $chave . "'; ";
    	//echo $sql;
		return $this->querySQL($sql);
	} 

	 //###################################################################################################################################
	/*
	* Procura na $_REQUEST os campos a serem lidos. Serão lidos os que tiverem valor.
	*/
	public function getDadosForm(){
		$i=0;
		//print_r($_REQUEST);
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		//$t= json_encode($_REQUEST);
		//$l=new Log($_REQUEST);
		foreach($this->camposLista as $campoaux){
			$nomeCampo="txt" . $campoaux['Field'];
			if (isset($_REQUEST[$nomeCampo])){
				$this->camposLista[$i]["valor"]=$_REQUEST[$nomeCampo];
				$this->camposLista[$i]["change"]=1;
			}
			$i++;
		}
		//print_r($this->camposLista);
	} 
	 
	//###################################################################################################################################	
	/**
	 *
	 * lê o parametro 'do' do form HTML
	 */
	public function getDo(){
		$do="";
		if (isset($_REQUEST['do'])){
			$do=$_REQUEST['do'];
		}
		//echo "<br>do=$do";
		return $do;
	}
  
  //###################################################################################################################################	
	/**
	 * 
	 *
	 * devolve o nome do ficheiro de template
	 */
	public function getTemplate(){
		return $this->template;
	}

  
   //###################################################################################################################################	
	/**
	 * 
	 * @param chave    é o nome do campo que pretendemos obter o valor
	 *
	 * devolve um texto previmente adicionado
	 */
	public function getTextos($chave){
		return $this->textos[$chave];
	}

  
 //###################################################################################################################################
	/**
	* Import a string with field separeted by ;
	*/ 
  public function importCSV(){
    if (isset($_REQUEST["txtCSV"])){       
		if ($_REQUEST["txtCSV"]!=""){
        	//a txt is passed
			$upd=0;
			if (isset($_REQUEST['doUpdate'])){
				if ($_REQUEST['doUpdate']==true){
					$upd=1;
				}
			}
			//echo "update?=$upd; doUpadate:".$_REQUEST['doUpdate'];
			$txt=$_REQUEST["txtCSV"];
          	$linhas=explode("\n", $txt);
			//print_r($linhas);
			//$k=0;
          	foreach($linhas as $linha){
				//echo "<br>K=". $k. "<br>";
            	$registo=explode(";", $linha);
            	$i=0;
            	$j=0;
				$keyValue="";
		        foreach($this->camposLista as $campoaux){
					//print_r($campoaux);
              		if ($campoaux['csv']==1){
						
                    	if ($campoaux['Type']=="pas"){
                      		$registo[$j]=$this->encriptar($registo[$j], $campoaux['cifra']);;
                    	}
						
						if ($campoaux['Key']=="PRI"){
							$keyValue=$registo[$j];
					  	}
                    	$this->camposLista[$i]["valor"]=$registo[$j];
						if ($registo[$j]!=""){
							$this->camposLista[$i]["change"]=1;
							//echo $campoaux['Type'] . " - " . $registo[$j];
							if ($campoaux['Type']=="date"){
								$date=$registo[$j]; 
								$date=str_replace("/","-",$date);
								$date=str_replace(".","-",$date);
								$parts=explode("-",$date);
								if (sizeof($part[0])<4){
									$date=implode('-', array_reverse(explode('-', $date))); 
								}
								//echo "date = $date <br>";
								$this->camposLista[$i]["valor"]=$date;
							}
						}
						
                    	$j++;			
              		}
              		$i++;
		        }
				if ($keyValue!="" and $upd==1){
					$sql= $this->prepareSQLInsertIfNotExisteUpdateIfExiste($keyValue);
					
				}else{
					$sql= $this->prepareSQLinsert();		
				}
             	$sql.=";";
				//echo "<br>linha j=". $j."<bR>";
				if ($this->debugS==1){
					echo $sql. "<br>";
				}
				$this->querySQL($sql);
            	//print_r($this->camposLista);  
				//return  $sql; 
          	}
		} 
	}
    //echo "ole";
  }
  
 //###################################################################################################################################
	/**
	* Conjunto de includes necessários ao formato da lista de registos da tabela
	*/
    //TROCAR PELO AJAX
	public function includes($path=""){
		?>
  <script>

  function preDel(id,texto){
    //alert(id);
    document.getElementById("delText").innerHTML=texto;
    document.getElementById("deleteKey").value=id;
  }
    
  async function preUp(id){
    //alert(id);
    document.getElementById("do").value="ce";
    document.getElementById("editKey").value=id;
    let url= window.location.protocol +"//"+ window.location.hostname +  window.location.pathname + "?do=e&id=" + id
    //alert("url: " + url);
    const response = await fetch(url)
    const eventos = await response.json()
    //alert(response);
    //alert(eventos);
    for (const evento of eventos) {
      for (x in evento) {
          //alert(x);
          if ($('textarea').length >1){
             var markupStr = evento[x];
            $('textarea#txt'+ x).summernote('code', markupStr);
          }
          $("#txt" + x).attr("value", evento[x] )
          var aux=`select#txt${x}`;
          //console.log(aux);
          //console.log($(`select#txt${x}`).length)
          if ($(`select#txt${x}`).length){
             $(`#txt${x} option:selected`).attr('selected',false);
            aux=`#txt${x} option[value='${evento[x]}']`
            //console.log(aux);
            $(aux).attr('selected','selected');
          }
          
        }
      }
  }
  </script>
    
   <script>
  
  $("#bnew").click(function() {
    var markupStr = "...";
    $("#editKey").attr("value","")
    $('.summernote').summernote('reset');
    <?php
    //print_r($this->camposLista);
    foreach($this->camposLista as $campoaux){
        if (($campoaux['Type']!="lst") && ($campoaux['Type']!="text") && ($campoaux['Type']!="calc") ){
    ?>
    	$("#txt<?php echo $campoaux['Field']?>").attr("value","<?php echo $campoaux['Default']?>")
        <?php  
        }else{
			if (!isset($campoaux['Default'])){
				$campoaux['Default']=null;
			}
          	if (($campoaux['Type']=="lst") && ($campoaux['Default']!=null)){
        ?>
        $("#txt<?php echo $campoaux['Field']?> option[value=<?php echo $campoaux['Default']?>]").attr('selected','selected');
        <?php
          	}else{
            	if ($campoaux['Type']=="text"){
        ?>
					$('textarea#txt<?php echo $campoaux['Field']?>').summernote('code', "...");
             	<?php
            	}
          	}
        }
    }
    ?>
  });
     
	</script>
	<?php      
	}  
  
     //###################################################################################################################################	
	/**
	* 
	* @param campo    array com a informação de um campo, inclui: label, Field, Type e etc
	* @param valor    valor por defeito a ser incluido do campo
	*
	* devolve o html adequado a construir um campo do tipo passado no atributo Type
	*/
	public function inputHTML($campo, $valor=""){
		$aux=substr($campo['Type'], 0, 3);
		
    	$html = new simple_html_dom();
    	$html->load_file($this->template);
    
    	$t="";
		$ast="";
		if ($campo['Null']=='NO'){
			$ast="*";
		}
		//echo "aux: " . $aux;
		//print_r($campo['lista']);
		switch ($aux) {
      		case "lst":
                //echo "aux: " . $aux;
                foreach($html->find('select[id]') as $e){
                  $e->id="txt" . $campo['Field'];
                  $e->name="txt" . $campo['Field'];
                }			
                foreach($html->find('#selectL') as $e)
                	$e->innertext=$campo['label'] . $ast;                
              
                $linhaElemento="";
				foreach($campo['lista'] as $linha){
					$i=0;
					$proximo=0;
					$aux="";
					//print_r($linha);
					foreach($linha as $x => $x_value) {
				  		//echo "<br><br><br><br><br><br>linha";
                    	//echo $x_value;
						//echo " - " . $valor . "<br>";
						if (($x_value==$valor) && ($i==0)){
							$proximo=1;
						}
						if (($proximo==1) && ($i==1)){
                      		foreach($html->find('#selectLst.option') as $e)
                         		$e->selected= "selected";
							//$aux=" selected ";
						}
						if ($i==0){
							$valorZ=$x_value;
						}
						if ($i==1){
							$texto=$x_value;
						}
						$i++;
                  	}  
                	//echo PHP_EOL . 'select#txt'.$campo['Field'] .' option' .PHP_EOL;
                	foreach($html->find('.select#txt'.$campo['Field'] .' option') as $e){
                    	$e->value="$valorZ";                    //tirei o txt
                    	$e->innertext=$texto . "[$valorZ]";
                    	//echo "aaaa";
                	}
                                    
                	//$f='#txt'.$campo['Field']
                	foreach($html->find('#txt'.$campo['Field']) as $e)
                    	$linhaElemento.=$e->innertext .PHP_EOL; 
				}
				//echo $html;
                //echo $linhaElemento;
                foreach($html->find('#txt'.$campo['Field']) as $e)
                  	$e->innertext = $linhaElemento;
                foreach($html->find('.select') as $e)
                  	$t=$e->outertext;
				break;     
			case "dat":
                foreach($html->find('input[id]') as $e){
                  	$e->id="txt" . $campo['Field'];
                  	$e->name="txt" . $campo['Field'];
                } 
                foreach($html->find('#dateL') as $e)
                  	$e->outertext=$campo['label']  . $ast ;
                foreach($html->find('.date') as $e)
                  	$t=$e->outertext;
				break;
        	case "int":
			case "var":	
			case "dec":
			case "dou":
			case "tim":
			case "img":
                foreach($html->find('input[id]') as $e){
                  	$e->id="txt" . $campo['Field'];
                  	$e->name="txt" . $campo['Field'];
                }
                foreach($html->find('#textL') as $e)
                  	$e->innertext=$campo['label']  . $ast ;
                foreach($html->find('.text') as $e)
                  	$t=$e->outertext;        
				break;
        	case "tex":
        	case "med":
                foreach($html->find('textarea[id]') as $e){
                  	$e->id="txt" . $campo['Field'];
                  	$e->name="txt" . $campo['Field'];
                }
                  
                foreach($html->find('#textAreaL') as $e)
                  	$e->innertext=$campo['label']  . $ast;
        
                foreach($html->find('.textArea') as $e)
                  $t=$e->outertext;
                  //echo "<br><br>aqui<br>" . $t .  "<br><br>aqui<br>";
				break;
        	case "pas":
				// falta tratar o modo para verificar a password
            	// se o campo lido for cifrado não pode ser considerada a password lida da base dados e por isso não se considera nenhuma password e desta forma só é actualizada se o utilizador voltar a 
            	// escrever uma password
				foreach($html->find('input[id]') as $e){
					$e->id="txt" . $campo['Field'];
					$e->name="txt" . $campo['Field'];
				}
                foreach($html->find('#passwordL') as $e)
                  $e->innertext=$campo['label'] . $ast ;
                foreach($html->find('.password') as $e)
                  $t=$e->outertext;
				break;
		} 
		if (isset($campo['action'])){
			$t=str_replace( '"txt' . $campo['Field'] . '">', '"txt' . $campo['Field'] . '"' . $campo['action'] . ">", $t);
			//$t.= "<!-- ssssss-->";
		}
		return $t;
	}
  
  
  
    
  	//###################################################################################################################################	
	/**
	* Prepara uma string com a instrução SQL da tabela (do tipo SELECT * FROM Tabela). Incluiu todos os campos
	*/
	public function preparaSQLGeral(){
		$this->sqlGeral= "SELECT * FROM " . $this->tabela;
		if ($this->limites[0]>0){
			$this->sqlGeral.= " Limit ". $this->limites[0];
		}
		if ($this->limites[1]>0){
			$this->sqlGeral.= " Step ". $this->limites[1];
		}
    	//print_r($this->limites);
      	//echo "sql: " . $this->sqlGeral;
		return 	$this->sqlGeral;
	}
 
  	//###################################################################################################################################	
	/**
    *  
	* Prepare a string SQL to insert if not exist and update if exist
	*/
	public function prepareSQLInsertIfNotExisteUpdateIfExiste($key){
		//$resposta=$this->prepareSQLSelect($key) . " If ResultCount==0 ";
		$resposta=$this->prepareSQLinsert() . " ON DUPLICATE KEY ";
		$resposta.=$this->preparaSQLupdate(1) ;
		return $resposta;
	}

	//###################################################################################################################################	
	/**
    *  
	* Prepare a string SQL to select a especific record
	*/
	public function prepareSQLSelect($key){
		$resposta= "SELECT " . $this->chave  . " FROM " . $this->tabela . " WHERE " . $this->chave . "='$key'" ;
		return $resposta;
	}

  	//###################################################################################################################################	
	/**
    *  
	* Prepara uma string SQL com os campos escolhidos para edição
	*/
	public function preparaSQLSelectToUpdate(){
    	$campos="";
      	$sep="";
		//echo "<br>chave=$this->chave";
		//$resto= ") VALUES (";
		$sep="";
		foreach($this->camposLista as $campo){
        	//print_r($campo['Type']);
			if ($campo["editar"]==1){
				$campos.=$sep . $campo['Field'];
				$sep=",";
			} 
		}
		$resposta= "SELECT " . $campos  . " FROM " . $this->tabela ;
		return $resposta;
	}     
  
	//###################################################################################################################################	
	/**
    *  
	* Prepara uma string SQL para apagar registo
	*/
	public function preparaSQLdelete(){
		$resposta= "DELETE FROM " . $this->tabela;
		foreach($this->camposLista as $campo){
			if (isset($campo["valor"])){
				if ($campo["valor"]!=""){
					if ($campo['Field'] == $this->chave){
						$criterio=$this->getCampoValor($campo);
					} 
				} 
			}
		}
		$resposta= $resposta .  " WHERE " . $this->chave . " = " . $criterio . ";";
		return $resposta;
	}    
		
	//###################################################################################################################################	
	/**
    *  
	* Prepara uma string SQL para inserir os campos com valor
	*/
	public function prepareSQLinsert(){
		$resposta= "INSERT INTO " . $this->tabela . " ( ";
		$resto= ") VALUES (";
		$sep="";
		foreach($this->camposLista as $campo){
			if (isset($campo["valor"])){
				if ($campo["valor"]!=""){
					$resposta=$resposta . $sep . $campo['Field']; 
					$resto=$resto . $sep . $this->getCampoValor($campo);
					$sep=",";
				} 
			}	
		}
		$resposta= $resposta . $resto . ") ";
		return $resposta;
	}
	
  	//###################################################################################################################################	
	/**
    * 
    * @param $action    We might want to see the fields in three action types: New(new), Edit(edit) or List(view)
    * 
	* Prepares a string with the SQL statement of the table (of type <SELECT LIST OF FIELDS> FROM Table). Only included the
    * fields marked as visible in the chosen action
	*/
	public function prepareSQLtoAction($action){

		//echo "<br>". $this->chave;
		if ($this->chave!=""){
			$sep=",";
		}else{
			$sep="";
		}
		$resposta= "SELECT " . $this->chave ;
		

		//echo "<br>". $sep;
      	//$key=0;
		//print_r($this->camposLista);
		foreach($this->camposLista as $campo){
		    //echo "<br>Campo1 = ";
		    //print_r($campo);
			if ($campo[$action]==1){
          		if ($campo['Type']=="calc"){
            		$resposta=$resposta . $sep . $campo['formula'] . " as ". $campo['Field']; 
          		}else{
            		$resposta=$resposta . $sep . $campo['Field']; 
          		}
				$sep=",";
			} 		
		}
		$resposta= $resposta . " FROM " . $this->tabela;
        $resposta = $resposta . " WHERE " . $this->criterio . " order by " . $this->order;
      	//echo "<br> $resposta <br>";
		if ($this->limites[0]>0){
			$resposta.= " Limit ". $this->limites[0];
		}
		if ($this->limites[1]>0){
			$resposta.= " Step ". $this->limites[1];
		}

      	return $resposta;			
	}
  
  
 	//###################################################################################################################################	
	/**
    * 
    * @param accao    Podemos querer ver os campos em três tipos de ação: Novo(novo), Editar(editar) ou Listar(ver)
    * 
	* Prepara uma string com a instrução SQL da tabela (do tipo <SELECT LISTA DE CAMPOS> FROM Tabela). Só Incluiu os 
    * campos marcados como visíveis na acção escolhida
	*/
	public function preparaSQLparaAccao($accao){
    	return repareSQLtoAction($accao);
	}
        
    //###################################################################################################################################	
	/**
    *  
	* Prepara uma string SQL para atualizar campos com valor
	* $notable - control if the update need or do not reed the table name. If we are to try to construct a sql introction with a test for duplicate key then we don't neet the table name
	*/
	public function preparaSQLupdate($noTable=0){
		if($noTable==1){
			$resposta= "UPDATE ";
		}else{
			$resposta= "UPDATE " . $this->tabela . " SET ";
		}		
		//echo "<br>chave=$this->chave";
		//$resto= ") VALUES (";
      	//$criterio="";
		$sep="";
		foreach($this->camposLista as $campo){
			if (isset($campo["valor"])){
				if ($campo["change"]==1){
					if ($campo['Field'] != $this->chave){
						//print_r($campo);
						$resposta=$resposta . $sep . $campo['Field']; 
						$resposta=$resposta . " = " . $this->getCampoValor($campo);
						$sep=",";
						$campo["change"]==0;
					} else {
						$criterio=$this->getCampoValor($campo);
					}		
				} 
			}
		}
		if($noTable==1){
			$resposta.= ";";
		}else{
			$resposta= $resposta .  " WHERE " . $this->chave . " = " .$criterio . ";";
		}
		//$resposta= $resposta .  " WHERE " . $this->chave . " = " .$criterio . ";";
      	//echo "chave: " . $this->chave . " fim da chave";
      	//echo $resposta; 
		return $resposta;
	}       
  
    //###################################################################################################################################	
	/**
	* 
	* @param $table    the name of the database table you want to use
	*
	* Prepare a table, create the table's field list, determine its key, prepare a general SQL for all fields
	*/
	function prepareTable($table){
		//prepara o html para gerir a tabela
		//prepara form de edição
		//prrara form de visualização
		$this->tabela=$table;
		$this->preparaSQLGeral();		
		//$sql="DESCRIBE  $table ";
		$sql="show full columns from  $table ";
		//echo $sql;
		$this->camposLista=$this->querySQL($sql);
		//$v=$this->camposLista;
		//print_r($this->camposLista);
		$this->determinaChave();
		$this->setLabels();
		$this->fieldsActive(1,'ver');
		$this->fieldsActive(1,'novo');  
		$this->fieldsActive(1,'editar');
	}
    //###################################################################################################################################	
	/**
	* 
	* @param tabela    nome da tabela na base de dados
	*
	* Prepara uma tabela, criando a lista de campos da tabela, determinando a sua chave, prepara um SQL geral para todos os campos
	* define as etiquetas
	*/
	//function preparaTabela($tabela){
		//prepara o html para gerir a tabela
  //  	$this->prepareTable($tabela);
	//}	 
 
 
	//###################################################################################################################################	
	/**
	* 
	* redirecciona para a pagina mostrando a lista
	*
	*/
	function redirecciona($url=""){
		?>
		<meta http-equiv="refresh" content="0;url=<?php echo $url?>">	
		<?php	
		//header("Location: " .  $url);
	}
  
		//###################################################################################################################################	
	/**
     * 
     * @param action   is the type of action (list, edit and add) in which we want to enable/disable the field
     * @param value    is 1 to show and 0 to hide
	  * Activate/deactivate (show/hide) a field for an action
	*/
	private function setAllFieldAtive($action, $value){	
		$action=str_replace("list","ver",$action);
		$action=str_replace("see","ver",$action);
		$action=str_replace("new","novo",$action);
		$action=str_replace("add","novo",$action);
		$action=str_replace("edt","editar",$action);
		//$action=str_replace("edit","editar",$action);
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
				$this->camposLista[$i][$action]=$value;
			$i++;
		}
	}



  	//###################################################################################################################################	
	/**
     * 
     * @param field    is the field we want to enable/disable
     * @param action   is the type of action (list, edit and add) in which we want to enable/disable the field
     * @param value    is 1 to show and 0 to hide
	  * Activate/deactivate (show/hide) a field for an action
	*/
	private function setFieldAtive($field, $action, $value){	
		$action=str_replace("list","ver",$action);
		$action=str_replace("see","ver",$action);
		$action=str_replace("new","novo",$action);
		$action=str_replace("add","novo",$action);
		$action=str_replace("edt","editar",$action);
		//$action=str_replace("edit","editar",$action);
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
			if ($campoaux['Field']==$field){
				$this->camposLista[$i][$action]=$value;
			}
			$i++;
		}
	}

	
 	//###################################################################################################################################	
	/**
     * 
     * @param campo    é o campo que pretendemos activar/desativar
     * @param accao    é o tipo de acção (listar, editar e adicionar) em que pretendemos activar/desativar o campo
     * @param valor    é 1 para mostrar e 0 para esconder
	* Activa/desativa (mostra/esconde) um campo para uma acção
  	* Este nome é para manter acompatibilidade com o Pt
	*/
	//private function setAtivaCampo($campo, $accao, $valor){
  // 		$this->setFieldAtive($campo, $accao, $valor);
	//}

  	//###################################################################################################################################	
	/**
     * 
     * @param fields    is a list of sql table fields separated by ; and may or may not have to delimit them
     * @param action    is the type of action (list, edit and add) in which we want to enable/disable the field
	* Activates (displays) a comma-separated list of fields for an action. Fields not on the list are disabled.
	*/
	public function setFieldsAtive($fields, $action){		
		$action=str_replace("list","ver",$action);
		$action=str_replace("see","ver",$action);
		$action=str_replace("new","novo",$action);
		//$action=str_replace("edit","editar",$action);
		$action=str_replace("edt","editar",$action);
		$action=str_replace("editarar","editar",$action);
    	$action=str_replace("edit","editar",$action);
		$this->fieldsActive(0, $action);
		$fields=str_replace("`","",$fields);
		$fields=str_replace(" ","",$fields);
		if ($fields!="*"){
			$campo=explode(",", $fields);
			//echo "<br>campos = ";
			//print_r($campo);
			for($i = 0; $i < sizeof($campo);$i++) {
				$this->setFieldAtive($campo[$i], $action, 1);
			}
		}else{
			$this->setAllFieldAtive($action,1);
		}
		
		if ($action=="csv"){
			$this->PagImp=1;
		}
	}
  
  
	//###################################################################################################################################	
	/**
     * 
     * @param campos    é uma lista de campos da tabela sql separados por ; e podem ou não ter ` a delimita-los
     * @param accao    é o tipo de acção (listar, editar e adicionar) em que pretendemos activar/desativar o campo
	* Activa (mostra) uma lista de campos separados por virgula para uma acção. Os campos que não estão na lsita são desativados
	*/
	//public function setAtivaCampos($campos, $accao){
	//    $this->setFieldsAtive($campos, $accao);
	//}
  
  	//###################################################################################################################################
	/**
	* 
	* @param value    letter with permission to be considered
	*                       a - all has the ability to view, create new, delete and change
  	*                       u - update You can only change the data
  	*                       e - edit It only allows edition
  	*                       n - new It only allows creating new records                         
  	*                       r - read Can only see
	* defines if by default the user has permissions to view, create new, delete and change
  	*	                  
	*/
	public function setAutentication($value){
		$this->autenticacao=$value;
	}
  
  
  	//###################################################################################################################################
	/**
	* 
	* @param valor    letra com tipo a permissão a ser considerado
	*                       a - all tempo a possibilidade de ver, criar novo, apagar e alterar
  	*                       u - update Só pode alterar os dados
    *                      e - edit 
  	*                       r - read só pode ver
	* define se por defeito o user tem permissões para ver, criar novo, apagar e alterar
  	*                  
	*/
	//public function setAutenticacao($valor){
	//	$this->setAutentication($valor);
	//}
   

  	//###################################################################################################################################	
	/**
     * @param nameField  is the name for the new field we want to add and that will result from a sql operation
	 * @param sqlCalcFormula    a sql operation that can involve other fields in the table
     * 
	* Add a new calculated field
	*/
	public function setCalculatedField($nameField,$sqlCalcFormula){
    	//print_r($this->camposLista);
		$i=sizeof($this->camposLista);
		$this->camposLista[$i]['Type']="calc";
		$this->camposLista[$i]['Field']=$nameField;
		$this->camposLista[$i]['formula']=$sqlCalcFormula;
    	$this->camposLista[$i]['label']=$nameField;
    	$this->camposLista[$i]['Key']="";
    	$this->camposLista[$i]['ver']=1;
    	$this->camposLista[$i]['editar']=0;
    	//echo "<br><br>";
     	//print_r($this->camposLista);
	}	
  
 	//###################################################################################################################################	
	/**
     * @param campo   é o nome para o campo que pretendemos adicionar
	 * @param calculo    é a formula sql que vamos aplicar
     * 
	* Adiciona um novo campo calculado
	*/
	//public function setCampoCalculado($campo,$calculo){
	//	$this->setCalculatedField($campo,$calculo);
	//}	
  
  	//###################################################################################################################################	
	/**
     * @param $field    	is the field that we want to change to the image type
     * @param $path      	is the path to be added to the url of an image to get to the file
     * @param $percentage 	is the % of the height of the image
     * 
	* Change the field to the image type to be seen in the list in as an image
	*/
	public function setImageField($field,$path,$percentage='100%',$defaultImage=""){
		$i=0;
		foreach($this->camposLista as $campoaux){
			if ($campoaux['Field']==$field){
				//echo "entrie";
				$this->camposLista[$i]['Type']="img";
				$this->camposLista[$i]['Path']=$path;
				$this->camposLista[$i]['widthP']=$percentage;
				$this->camposLista[$i]['defaultImage']=$defaultImage;
			}		
			$i++;
		}
	}	
  
  	//###################################################################################################################################	
	/**
     * @param campo         é o campo que pretendemos alterar para o tipo imagem
     * @param $caminho      é o camiho a ser acrescentado a imagem para chegar ao ficheiro
     * @param percentagem   é a % da altura da imagem
     * 
	* Altera o campo para o tipo imagem para ser visto na lista de forma especial
	*/
	//public function setCampoImagem($campo,$caminho,$percentagem='100%'){
	//	$this->setImageField($campo,$caminho,$percentagem);
	//}	
  
  	//###################################################################################################################################	
	/**
     * @param $field      is the field we want to change to list type.
	 * @param $mode       how the field list should be constructed. 1 - SQL; 2 - values; 3 - SQL + values.
	 * @param $listOrSql  listOrSql is the sql string or list of values to be passed (the list has the format, for example: "1=>first,2=>second,3=>last,a=>like this") or
	 * 						both with the structure example. "Select a,b from C|0=>none,1=>not defined"
     * @param $hideCode   by default (0) then in the text that replaced the code, the code between [] is added. example: Show [1]
     * 
	* Change the field to list type to have a descriptive instead of the code and a combobox for editing and input and a text for view
	*/
	public function setFieldList($field,$mode,$listOrSql, $hideCode=0){
		$i=0;
		//echo "<br> campo=$campo accao=$modo e valor=$listaSql";
		foreach($this->camposLista as $campoaux){
			if ($campoaux['Field']==$field){
				//echo "entrie";
          		$this->camposLista[$i]['hideCode']=$hideCode;
				$this->camposLista[$i]['Type']="lst";
				switch($mode){
					case "1":
						// preenceh com sql
						$listanova=new TableBD();
						$lista=$listanova->querySQL($listOrSql);
						break;
					case "2":
            			//echo "<br>listasql=$listaSql";
						$lista1=explode(",", $listOrSql);
						$j=0;
						//echo "<br><br><br><br><br><br><br><br><br>";
						//print_r($lista1);
						foreach ($lista1 as $ls){
							$par=explode("=>", $ls);
							$aux['id']=$par[0];
							$aux['tx']=$par[1];
							$lista[$j]= $aux;
							$j++;
						}

						//$lista= array($listaSql);
            			//echo "<br><br>";
						//print_r($lista);
						break;
					case "3":
						$par=explode("|", $listOrSql);
						$listanova=new TableBD();
						$lista=$listanova->querySQL($par[0]);

						$lista1=explode(",", $par[1]);
						$j=count($lista);
						foreach ($lista1 as $ls){
							$par=explode("=>", $ls);
							$aux['id']=$par[0];
							$aux['tx']=$par[1];
							$lista[$j]= $aux;
							$j++;
						}
						break;
				}
				//print_r($lista);
				//echo "<br>";
          		//arsort($lista);
				$this->camposLista[$i]['lista']=$lista;
			}			
			$i++;
		}
	//echo "passei";
	} 
  
  
	//###################################################################################################################################	
	/**
     * @param campo    é o campo que pretendemos alterar para o tipo lista
	 * @param modo    modo em que são passados os campos. 1 - SQL; 2 - valores.
	 * @param listaSql    listaSql é a string sql ou lista de valores a serem passados ( a lista tem o formato por ex: "1=>primeiro,2=>segunto,3=>utilimo,a=>assim")
     * 
	* Altera o campo para o tipo lista para ter um descritivo em vez do código e uma combobox na edição e introdução
	*/
	//public function setCampoLista($campo,$modo,$listaSql){
  //      $this->setFieldList($campo,$modo,$listaSql);	
	//}
  
  	//###################################################################################################################################	
	/**
    * @param campo   is the field we want to change to type password
	* @param mode    mode of verification of correct writing of new password. 0 - off; 1 - repeat the introduction; 2 - show password
	* @param cipher  Change the field to type password to have hidden text in the intro, and be encrypted before recording. It will include 
     *                a mode field to determine the way it will be entered so that there are no mistakes (repeat the entry or show) and a field 
     *                with the cipher
     * 
	* Change the field to type password to have hidden text in the intro, and be encrypted before recording. It will include a mode field to 
  	* determine the way it will be entered so that there are no mistakes (repeat the entry or show) and a field with the cipher
	*/
	public function setFieldPass($field,$mode,$cipher){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
			if ($campoaux['Field']==$field){
				//echo "entrie";
				$this->camposLista[$i]['Type']="pass";
				$this->camposLista[$i]['modo']=$mode;
				$this->camposLista[$i]['cifra']=$cipher;
			}
			$i++;
		}
	}	  
	
	//###################################################################################################################################	
	/**
     * @param campo    é o campo que pretendemos alterar para o tipo password
	 * @param modo    modo de verificação da escrita correcta de nova password. 0 - desligado; 1 - repetir a intodução; 2 - mostrar a password
	 * @param cifa    modo como o texto é cifrado. "" - desligado; "md5" - md5; "sha1" - sha1; "base64" - base64
     * 
	* Altera o campo para o tipo password para ter texto escondido na introdução, e ser encripado antes de gravar. Vai incluir um campo modo
	* para determinar a forma com será introduzido para na haver enganos (repetir a introdução ou mostrar) e um campo com a cifra
	*/
	//public function setCampoPass($campo,$modo,$cifra){
  //  	$this->setFieldPass($campo,$modo,$cifra);
	//}	

  	//###################################################################################################################################	
	/**
	* @param criterio    It's an SQL criterion that equals fields to values.
  	* 
	* define um critério para a accão de ver
	*/
	public function setCriterio($criterio){
		$this->criterio=$criterio;
	}	

  	//###################################################################################################################################	
	/**
	* @param order    is a sql string to order tha table
  	* 
	* define um critério para a accão de ver
	*/
	public function setOrder($order){
		$this->order=$order;
	}	
  
	//###################################################################################################################################	
	/**
	* @param value     is a flag that can take a value of 1 to display SQL strings or 0 to hide them.
  	* 
	* Enable debug mode to view the SQL queries and better comprehend errors.
	*/
	public function setDebugShow($value){
		$this->debugS=$value;
	}	
  	
    //###################################################################################################################################	
	/**
     * @param campo    is the name of the field in which we want to define an initial value 
     * @param valor    is the initial value to be considered
     * 
	*  defines a default value to be considered in a new introduction where $field is the name of the field in which we want to define an initial value 
    * and $value is the initial value to be considered
	*/
	public function setDefaultValue($field, $valor){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
			if ($campoaux['Field']==$field){
				//echo "entrie";
				$this->camposLista[$i]['Default']=$valor;
			}	
			$i++;
		}
	}	
  
	//###################################################################################################################################	
	/**
     * @param field    is the field to add a javascript action
     * @param action   is the action you want to call
     * 
	* ste a js action to a field
	*/
	public function setJSAction($field, $action){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
				if ($campoaux['Field']==$field){
					//echo "entrie";
					$this->camposLista[$i]['action']=$action;
				}			
				$i++;
		}
	}	  
	
	//###################################################################################################################################	
	/**
     * @param campo    é o campo que pretendemos alterar a etiqueta
     * @param valor    é o texto a ser considerado como etiqueta
     * 
	* Atribui um label a um campo
	*/
	public function setLabel($campo, $valor){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
			if ($campoaux['Field']==$campo){
				//echo "entrie";
				$this->camposLista[$i]['label']=$valor;
			}
			$i++;
		}
	}	

	//###################################################################################################################################	
	/**
	* Atribui como label do campo o nomes dos campos na base de dados. Esta função só é executada na preparação da tabela
	*/
	private function setLabels(){
		$i=0;
		//print_r($this->camposLista);
		foreach($this->camposLista as $campo){
			//$aux="";
			if ($this->camposLista[$i]['Comment']!=""){
				$this->camposLista[$i]['label']=$this->camposLista[$i]['Comment'];
			}else{
				$this->camposLista[$i]['label']=$campo['Field'];
			}
			$i++;
		}
	}

  
  	//###################################################################################################################################	
	/**
     * @param $NumReg    número de registos
     * @param $LimInf    registo inicial
     * 
	* Define o número de registos num select
	*/
	public function setLimites($NumReg, $LimInf=0){
    	$this->limites[0]=$NumReg;
    	$this->limites[1]=$LimInf;
	}	
 
  	//###################################################################################################################################
	/**
	* Stores the name (url) of the page that should be opened to show une record.
	*
	* @param $page    is the address (url) for a html page to show a record
	* @param $style    is the way that the key value are passed. if style=0 then the url to see is url?id=keyValue. if style=1 then the url is url/keyValue
	*/
	public function setLinkPage($page,$style=0){
		$this->PagVer=$page;
		$this->linkStyle=$style;
	}
  
 	//###################################################################################################################################
	/**
	* 
	* @param pagina    pagina para ver uma ficha
	*
	* Guarda o nome da página que mostra o artigo
	*/
	public function setPaginaVer($pagina,$style=0){
		$this->setLinkPage($pagina,$style);
	}

  	//###################################################################################################################################
	/**
	* 
	* @param id       é o id de um tag HTML
	* @param valor    é uma  string com o valor a ser carregado no elemento
	*
	* Escreve num elemento HTML da página por defeito, que tenha o id.  
	*/
	public function setHTMLid($id,$valor){
		$this->id[$id]=$valor;
	}
 
  	//###################################################################################################################################	
	/**
	 * 
   * @param page    name and path of the page with the template for a table
	 *
	 * set a page for tamplate
	 */
  	public function setTemplate($page){
	    $this->template=$page;
  	}
  
 	//###################################################################################################################################
	/**
	* 
	* @param texto    é o nome do campo que pretendemos guardar o valor
	* @param valor    string com o texto que deve ser apresentado
	*
	* Guarda um tipo de texto e o seu valor
	*/
	public function setTextos($texto,$valor){
		$this->textos[$texto]=$valor;
	} 
  
 	//###################################################################################################################################
	/**
	 * 
	 * @param value   is the string with the text we want to have in the table list
	 *
	 * define the title of the page/or form
	 */
  	public function setTitle($value){
    	$this->setTextos("titulo",$value);      
  	} 
  
	//###################################################################################################################################
	/**
	 * 
	 * @param valor    é a string com o texto que queremos ter na lista da tabela
	 *
	 * define o título da página/ou form
	 */
  	public function setTitulo($valor){
    	$this->setTitle($valor);      
  	}  
}

//###################################################################################################################################
?>
