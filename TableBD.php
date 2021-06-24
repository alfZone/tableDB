<?php

/**
 * The idea for this object is to provide a simple way to manage a databes table. With some configurations we can list a tables, add a new record, change and update a record, delete 
 * a record and insert several records using a csv file.
 * @author António Lira Fernandes
 * @version 8.3
 * @updated 21-06-2021 21:50:00
 */

// problems detected
// - Need more testing
// - When using a null value, the field content is not deleted. Probably not considered
// - Return errors



//news of version: 
//                  some comments in English
//                  corrections in jquery



namespace classes\db;
use classes\db\Database;
use classes\simplehtmldom\simple_html_dom;
use DOMDocument;
use DomXPath;



//echo "aquui";
class TableBD{
	// REQUIRES
	// Database.php
	
  // MISSION: generate a table to manage a query to the database. Considers 4 actions: view, new, edit and import
  
// METHODS
// __construct() - Class Constructor
// ativaCampos($ value, $ action) - Makes visible the fields to be displayed by passing the value = 1 (or not passing a value) and hides it by passing the value = 0. When value //                                  passed is 1 the field is active (visible) and when the field is 0 the field is disabled. Action defines the behavior of seeing, new, 
//                                  editing.
// consultaSQL($ sql) - given a sql it returns a list of data.
// determinaChave() - Analyzes the structure of the database table and determines which is the key
// devolveValorDaLista($ field, $ key) - Find the value of a field for a given key where the field is the name of the field to be consulted the list of values and key is the id //                                       of the value to be searched.
// encriptar($ text, $ cipher = "md5") - encrypts a text according to a past method where text is the text to be encoded and encryption is the type of cipher used
// fazHTML() - Do what is necessary to maintain the table in an html page. Lists the data and allows you to insert new ones, edit and delete records. Use a 'do' parameter to 
//             make decisions
// fazLista() - Makes an HTML table with the list of all records in the table. This table allows you to sort by column, search for texts and shows a
//              set of records (25 by default) and allows browsing pages
// formConfirmacaoApagar($ record) - Displays a confirmation to delete the record
// formulario($ record = "") - Displays an HTML form to edit or insert a record
// getCampoValor($ campo) - returns an appropriate string to construct an SQL field with quotes and without quotes in which field and an array with the information of a field, //                          includes: label, Field, Type, value and etc
// getCampos() - Returns the list of fields in the table
// getChave() - reads the key parameter of the record sent by the HTML form and which corresponds to the value identified as key in the analysis of the table
// getDados($ key) - given a key value it returns the results
// getDadosForm() - Search $ _REQUEST for the fields to be read. Those with value will be read.
// getDo() - read the 'do' parameter of the HTML form
// getTextos($ key) - returns a pre-entered text where key is the name of the field we want to get the value
// includes() - Set of includes necessary for the format of the list of records in the table
// inputHTML($ field, $ value) - returns the appropriate html to construct a field of the type passed in the Type attribute. field is an array with the information of a
//                               field, includes: label, Field, Type and etc and value is the default value to be included in the field
// preparaSQLGeral() - Prepare a string with the table's SQL statement (of type SELECT * FROM Table). Included all fields
// preparaSQLdelete() - Prepare an SQL string to delete the record
// preparaSQLinsert() - Prepare an SQL string to insert the fields with value
// preparaSQLparaAccao($ accao) - Prepare a string with the SQL statement of the table (of type <SELECT LIST OF FIELDS> FROM Table). Only included fields marked as visible 
//                                in the chosen action where in action We may want to see the fields in three types of action: New (novo), Edit (editar), List (ver) Import (csv) 
// preparaSQLupdate() - Prepare an SQL string to update fields with value
// preparaTabela($ table) - Prepare a table, creating the list of fields in the table, determining its key, preparing a general SQL for all fields define the tags - table is
//                          the name of the table in the database
// redirecciona($ url = "? do = l") - redirects to the page showing the list
// setAutenticacao($ value) - defines if by default the user has permissions to see, create new, delete and change where: a - all time the possibility to see, create new,
//                            delete and change, u - update Can only change data, r - read can only see
// setAtivaCampo($ campo, $ accao, $ valor) - Activates / deactivates (shows / hides) a field for an action where the field is the field we want to activate / deactivate
//                                            action is the type of action (list, edit and add) in which we want to activate / deactivate the field and value is 1 for
//                                            show and 0 to hide
// setAtivaCampos($ fields, $ action) - Activates (shows) a comma-separated list of fields for an action. Fields that are not listed are disabled fields is a list of fields 
//                                      in the sql table and action is the type of action (list, edit and add) in which we want enable / disable the field
// setCampoCalculado($ field, $ calculation) - Adds a new calculated field in which field is the name for the field we want to add and calculate is the sql formula that we are
//                                             going to apply
// setCampoLista($ field, $ mode, $ listSql) - Changes the field to the list type to have a description instead of the code and a combobox in the edition and introduction in 
//                                             which field is the field that we want to change to the list type, mode is the way in which the fields are passed: 1 - SQL; 2 - 
//                                             values ​​and listSql is the sql string or list of values ​​to be passed (the list has the format eg "1 => first, 2 => second, 
//                                             3 => useful, a => like this")
// setCampoPass($ field; $ mode = 0) - Change the field to the password type to have hidden text in the introduction, and to be encrypted before saving. It will include a mode
//                                     field to determine the way in which it will be introduced so that there are no mistakes (repeat the introduction or show) and a field 
//                                     with the number in which field is the field that we intend to change to the type and mode is to verify the correct writing of a new
//                                     password. 0 - off; 1 - repeat the introduction; 2 - show password and cifa is the way the text is encrypted. "" - off; "md5" - md5; 
//                                     "sha1" - sha1; "base64" - base64
// setCampoImagem($ field, $ path, $ percentage) - Change the field to the image type to be seen in the list in a special way where field is the field we want to change to the 
//                                                 image type path is the path to be added to the image to reach the file and percentage is the% of the height of the image
// setCriterio($ criterio) - defines a criterion for the viewing action, where criterion is an sql (where) criterion that equals fields with values
// setDefaultValue ($ field, $ value) - defines a default value to be considered in a new introduction where field is the field in which we want to define an initial value and 
//                                      value is the initial value to be considered
// setHTMLid($ id, $ value) - Writes in an HTML element of the page by default, which has the id. id is the id of an HTML tag and value is a string with the value to be loaded
//                            into the element
// setLabel($ field, $ value) - Assign a label to a field where the field is the field we want to change the label and the value is the text to be considered as a label
// setLabels() - Assign field names in the database as a field label. This function is only performed when preparing the table
// setLimites($ NumReg, $ LimInf = 0) - Sets the number of resistors in a select where $ NumReg is the number of records and $ LimInf is the initial record
// setPaginaVer($ page) - Stores the name of the page that should be opened to show the record where the page is the address for an html page for the record
// setTemplate($ path) * - Assign the template to the table. Where path is the path and the template file
// setTitulo($ value) - sets the title of the page / or form 
	
	

//########################################## Variaveis ###############################################################################	
	
	/**
	 * This array will receive all the output texts of the class.
	 */
  private $textos=array("titulo"=>"Lista de registios da tabela");
  /**
	 * array of id and value pairs for HTML tag
	 */
  private $id;
	

  private $camposLista;
  private $template="../templates/base/tables.php";
  private $tabela;
  private $sqlGeral;
  private $chave;
  private $PagaClose="?do=l";
  private $PagVer="";
  private $PagImp=0;
  private $criterio="(1=1)";
  private $autenticacao="a";  //defines if by default the user has permissions to:
                                  // a - all tempo the possibility to view, create new, delete and change
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
  * @param $valor    when the value passed is 1 the field is active (visible) and when the field is 0 the field is disabled
  * @param $accao    sets behavior in see, new, edit
	*
  * Makes the fields to be displayed visible by passing value=1 (or not passing value) and hides passing value=0
	*/
	private function ativaCampos($valor, $accao){
		$i=0;
		foreach($this->camposLista as $campo){
				$this->camposLista[$i][$accao]=$valor;
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
	public function consultaSQL($sql){
		$database = new Database(_BDUSER, _BDPASS, _BD);
        $database->query($sql);
		//$database->execute();
			
		return $database->resultset();
	
	}
  
  public function querySQL($sql){
    return $this->consultaSQL($sql);
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
				foreach($campo1['lista'] as $linha){
					$i=0;
					$proximo=0;
					foreach($linha as $x => $x_value) {
						//echo $x_value;
						if (($x_value==$chave) && ($i==0)){
							$proximo=1;
						}
						if (($proximo==1) && ($i==1)){
							$devolve=$x_value . " [" . $chave ."]";
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
	* Faz o que é necessaro para manter a tabela numa página html. Lista os dados e permite inserir novos, editar e apagar registos. Usa um parametro 'do' para tomar as decisões
	*/
  // TEM DE SER TODO REFORMULADO
	public function fazHTML(){
		
    //lê o parametro 'do' do form HTML
		$faz=$this->getDo();
    //echo "<br>Faz: $faz<br><br>";
    
		switch($faz){
				//lista de valores
			case "":
			case "l":
				$this->preparaSQLparaAccao('ver');
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
        $this->importarCSV();
       
        $this->redirecciona();
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
				$sql= $this->preparaSQLinsert();
				//echo $sql;
			  //$this->consultaSQL($sql);
        $this->ExecuteSQL($sql);
				$this->redirecciona();
				break;
			case "ce":
				//efectuar a edição
				$this->getDadosForm();
				$sql= $this->preparaSQLupdate();
				//echo $sql;
				$this->ExecuteSQL($sql);
				$this->redirecciona();
				break;
			case "cd":
				//efectuar o apagar
				$this->getDadosForm();
				$sql= $this->preparaSQLdelete();
				//echo $sql;
				$this->ExecuteSQL($sql);
				$this->redirecciona();
				break;
		}
	}


  
   //###################################################################################################################################
  /**
	* Faz uma tabela HTML com a lista de todos os registos da tabela. Essa tabela permite ordenar por coluna, procurar textos e mostra um
	* conjunto de registos (25 por defeito) e permite navegar em páginas
	*/
	public function fazLista(){
    
    $html = new simple_html_dom();
    $html->load_file($this->template);
     
    //preparar o form delete
    foreach($html->find('#deleteKey') as $e)
        $e->outertext = '<input type="hidden" id="deleteKey" name="txt' .$this->chave . '" value="">'; //tirei o id
    
    foreach($html->find('#importLst') as $e)
        $e->innertext = $this->fazListaCamposAccao("csv"); //tirei o id
    
    
     // table head line
    $text="<tr>". PHP_EOL;
    $i=0;
    $pi=0; //this varible is use to controlo a list os fields when the keyfield is not visible
		foreach($this->camposLista as $campo){
			//print_r($campo);
      if ($this->chave==$campo['Field'] && $campo['ver']==1){
        $pi=1;
      }
      if ($campo['ver']==1){
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
          $imgHTMLpre[$i]='<img src="' . $campo['Path'];
          $imgHTMLpos[$i]='" class="img-thumbnail" alt="'. $campo['Field'] .'" style="width:' . $campo['widthP'] . '%; height=20%">';
					//echo $carimbo;
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
              $text .="<th class='buttons'>" . $e->outertext .$k ."</th>";
              
          break;
      default:
          $text .="<th></th>";
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
									
		//$sql=$this->sqlGeral;
		$sql=$this->preparaSQLparaAccao("ver");
    //echo "<br>sql=" . $sql;
		$stmt=$this->consultaSQL($sql);
		//print_r($stmt);
  	foreach($stmt as $registo){
			$text .= "<tr>";
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
          $e->href=$this->PagVer . "?$chaveid=$chave";
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
            $campo=$imgHTMLpre[$i] . $campo . $imgHTMLpos[$i];
          }
          $i++;
				  $text .= "<td>$campo</td>";
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
                  $e->onClick="preUp(" . $chave . ")";
                  $text .="<td class='buttons'>" . $ver .  $e->outertext;
                }
                  
                foreach($html->find('.bdel') as $e){
                  $e->data=$chave;
                  $e->onClick="preDel(" . $chave . ",'" .$chave. " - ". $dois . "')";
                  $text .= $e->outertext ."</td></tr>";
                }
                  
                break;
        case "u":
                foreach($html->find('.bedit') as $e)
                  $e->onClick="preUp(" . $chave . ")";
                  $text .="<td class='buttons'>" . $ver. $e->outertext ."</td></tr>";
                break;
        default:
                $text .= "<td class='buttons'>$ver</td>
												  </tr>";
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
            $texto=$texto . $sep . $campo['Field'];
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
          <p>
            Os campos tem de ser importados pela seguinte ordem: <code><?php echo $this->fazListaCamposAccao("csv")?></code>
          </p>
				<form action="?do=csv" method="post" role="form" class="form"  id="Form1"> 
				<div class="row">
					<div class="col-sm-12" >
						<div class="form-group">
                <label for="comment">linhas a serem importadas:</label>
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
	*/
	public function formulario($toDo="e"){
		
    $html = new simple_html_dom();
    $html->load_file($this->template);
    
    
   
    
    // change h3
    foreach($html->find('.tbTitle') as $e)
        $e->innertext = $this->textos['titulo'];
    
    
    $accao="editar";
    if ($toDo="a"){
       $accao="novo";
    }
      
    
    $t=""; 
   //preparing fields  
   foreach($this->camposLista as $campo){
		//print_r($campo);
		//echo "<br>_____________________________<br>";
		if ($campo[$accao]==1){
			$aux="";
      if (isset($campo['default'])){
          $aux=$campo['default'];
       }
			$t.=$this->inputHTML($campo);
		}
		if (($campo['Field']==$this->chave) && ($campo[$accao]!=1) ){
			$t.= '<input type="hidden" id="editKey" name="txt' . $campo['Field'] . '" value="">';   //tirei o id
		}
	}
   
    foreach($html->find('#frmIOH3') as $e)
        $e->innertext= PHP_EOL. PHP_EOL.$t .PHP_EOL. PHP_EOL;
    
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
		return $this->consultaSQL($sql);
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
		return $this->consultaSQL($sql);
	} 

	 //###################################################################################################################################
	/*
	* Procura na $_REQUEST os campos a serem lidos. Serão lidos os que tiverem valor.
	*/
	public function getDadosForm(){
		$i=0;
		//print_r($_REQUEST);
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
			$nomeCampo="txt" . $campoaux['Field'];
			if (isset($_REQUEST[$nomeCampo])){
				if ($_REQUEST[$nomeCampo]!=""){
					$this->camposLista[$i]["valor"]=$_REQUEST[$nomeCampo];
				} else {
					$this->camposLista[$i]["valor"]="";
				}
					
				
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
	* importar uma caixa de texto
	*/ 
  public function importarCSV(){
    
    if (isset($_REQUEST["txtCSV"])){
       
				if ($_REQUEST["txtCSV"]!=""){
           //echo "recebi!";
					$txt=$_REQUEST["txtCSV"];
          $linhas=explode("\n", $txt);
          foreach($linhas as $linha){
            $registo=explode(";", $linha);
            $i=0;
            $j=0;
		        foreach($this->camposLista as $campoaux){
              if ($campoaux['csv']==1){
                    if ($campoaux['Type']=="pas"){
                      $registo[$j]=$this->encriptar($registo[$j], $campoaux['cifra']);;
                    }
                    $this->camposLista[$i]["valor"]=$registo[$j];
                    $j++;			
              }
              $i++;
		        }
             $sql= $this->preparaSQLinsert();
				    //echo $sql;
				    $this->consultaSQL($sql);
            //print_r($this->camposLista);    
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
    //$("input#deleteKey").attr("value", $(this).attr("data"));
  }
    
  async function preUp(id){
    //alert(id);
    document.getElementById("do").value="ce";
    document.getElementById("editKey").value=id;
    
    //alert(window.location.hostname +  window.location.pathname);
    
    //let url= document.location.href + "?do=e&id=" + id
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
            aux=`#txt${x} option[value=${evento[x]}]`
            //console.log(aux);
            $(aux).attr('selected','selected');
          }
          
        }
      }
    
  }
  </script>
    
   <script>
  
  $("#bnew").click(function() {
    var markupStr = "";
    <?php
    //print_r($this->camposLista);
      foreach($this->camposLista as $campoaux){
        if (($campoaux['Type']!="lst") && ($campoaux['Type']!="text")){
          ?>
          $("#txt<?php echo $campoaux['Field']?>").attr("value","<?php echo $campoaux['Default']?>")
        <?php
          
        }else{
          if (($campoaux['Type']=="lst") && ($campoaux['Default']!=null)){
            ?>
            $("#txt<?php echo $campoaux['Field']?> option[value=<?php echo $campoaux['Default']?>]").attr('selected','selected');
            <?php
          }else{
            if ($campoaux['Type']=="text"){
              ?>
              $('textarea#txt<?php echo $campoaux['Field']?>').summernote('destroy');
              $('textarea#txt<?php echo $campoaux['Field']?>').summernote('code', markupStr);
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
                  $e->innertext=$campo['label'] ;                
              
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
        
                //foreach($html->find('#txt'.$campo['Field']) as $e)
                //     echo $e->outertext .PHP_EOL;
        
        
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
                  $e->outertext=$campo['label'] ;
                foreach($html->find('.date') as $e)
                  $t=$e->outertext;
						break;
        case "int":
				case "var":	
				case "dec":
        case "tim":
        case "img":
                foreach($html->find('input[id]') as $e){
                  $e->id="txt" . $campo['Field'];
                  $e->name="txt" . $campo['Field'];
                }
                  
                foreach($html->find('#textL') as $e)
                  $e->innertext=$campo['label'] ;
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
                  $e->innertext=$campo['label'] ;
        
                //foreach($html->find('#textAreaS') as $e)
                  //$e->innertext="$(document).ready(function() { $('#" . "txt" . $campo['Field'] . "').summernote(); });";
        
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
                  $e->innertext=$campo['label'] ;
                foreach($html->find('.password') as $e)
                  $t=$e->outertext;
						break;
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
		 * Prepara uma string SQL com os campos escolhidos para edição
		 */
	  public function preparaSQLSelectToUpdate(){
      $campos="";
      $sep="";
			//echo "<br>chave=$this->chave";
			//$resto= ") VALUES (";
			$sep="";
			foreach($this->camposLista as $campo){
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
	  public function preparaSQLinsert(){
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
			$resposta= $resposta . $resto . "); ";
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
			$resposta= "SELECT " . $this->chave ;
			$sep=",";
      //$key=0;
			//print_r($this->camposLista);
			foreach($this->camposLista as $campo){
			    //echo "<br>Campo1 = ";
			    //print_r($campo);
				if ($campo[$accao]==1){
          if ($campo['Type']=="calc"){
            $resposta=$resposta . $sep . $campo['formula'] . " as ". $campo['Field']; 
          }else{
            $resposta=$resposta . $sep . $campo['Field']; 
          }
					$sep=",";
				} 
				
		}
			$resposta= $resposta . " FROM " . $this->tabela;
      
      $resposta = $resposta . " WHERE " . $this->criterio;
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
		 * Prepara uma string SQL para atualizar campos com valor
		 */
	  public function preparaSQLupdate(){
			$resposta= "UPDATE " . $this->tabela . " SET ";
			//echo "<br>chave=$this->chave";
			//$resto= ") VALUES (";
      //$criterio="";
			$sep="";
			foreach($this->camposLista as $campo){
				if (isset($campo["valor"])){
					if ($campo["valor"]!=""){
						if ($campo['Field'] != $this->chave){
							//print_r($campo);
							$resposta=$resposta . $sep . $campo['Field']; 
							$resposta=$resposta . " = " . $this->getCampoValor($campo);
							$sep=",";
						} else {
							$criterio=$this->getCampoValor($campo);
						}
					
					} 
				}
			}
			$resposta= $resposta .  " WHERE " . $this->chave . " = " . $criterio . ";";
      //echo "chave: " . $this->chave . " fim da chave";
      //echo $resposta;
			return $resposta;
		}    
        
        //###################################################################################################################################	
		/**
		* 
	 	* @param tabela    nome da tabela na base de dados
	 	*
		* Prepara uma tabela, criando a lista de campos da tabela, determinando a sua chave, prepara um SQL geral para todos os campos
		* define as etiquetas
		*/
	function preparaTabela($tabela){
		//prepara o html para gerir a tabela

		//prepara form de edição
		//prrara form de visualização
		$this->tabela=$tabela;
		$this->preparaSQLGeral();
		
		$sql="DESCRIBE  $tabela ";
		//echo $sql;
		$this->camposLista=$this->consultaSQL($sql);
		//$v=$this->camposLista;
		//print_r($this->camposLista);
		$this->determinaChave();
		$this->setLabels();
		$this->ativaCampos(1,'ver');
		$this->ativaCampos(1,'novo');
		$this->ativaCampos(1,'editar');
		
	}
 
  	 
 
 
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
     * @param campo    é o campo que pretendemos activar/desativar
     * @param accao    é o tipo de acção (listar, editar e adicionar) em que pretendemos activar/desativar o campo
     * @param valor    é 1 para mostrar e 0 para esconder
	* Activa/desativa (mostra/esconde) um campo para uma acção
	*/
	private function setAtivaCampo($campo, $accao, $valor){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
				if ($campoaux['Field']==$campo){
					$this->camposLista[$i][$accao]=$valor;
				}
				
				$i++;
		}
	}

//###################################################################################################################################	
	/**
     * 
     * @param campos    é uma lista de campos da tabela sql
     * @param accao    é o tipo de acção (listar, editar e adicionar) em que pretendemos activar/desativar o campo
	* Activa (mostra) uma lista de campos separados por virgula para uma acção. Os campos que não estão na lsita são desativados
	*/
	public function setAtivaCampos($campos, $accao){
		
		$this->ativaCampos(0, $accao);
		$campos=str_replace(" ","",$campos);
		$campo=explode(",", $campos);
		//echo "<br>campos = ";
		//print_r($campo);
		for($i = 0; $i < sizeof($campo);$i++) {
			$this->setAtivaCampo($campo[$i], $accao, 1);
		}
    if ($accao=="csv"){
      $this->PagImp=1;
    }
	}
  
  
  //###################################################################################################################################
	/**
	* 
	* @param valor    letra com tipo a permissão a ser considerado
	*                       a - all tempo a possibilidade de ver, criar novo, apagar e alterar
  *                       u - update Só pode alterar os dados
  *                       r - read só pode ver
	* define se por defeito o user tem permissões para ver, criar novo, apagar e alterar
  *                  
	*/
	public function setAutenticacao($valor){
		$this->autenticacao=$valor;
	}
   

  //###################################################################################################################################	
	/**
     * @param campo   é o nome para o campo que pretendemos adicionar
		 * @param calculo    é a formula sql que vamos aplicar
     * 
	* Adiciona um novo campo calculado
	*/
	public function setCampoCalculado($campo,$calculo){
    //print_r($this->camposLista);
		$i=sizeof($this->camposLista);
		$this->camposLista[$i]['Type']="calc";
		$this->camposLista[$i]['Field']=$campo;
		$this->camposLista[$i]['formula']=$calculo;
    $this->camposLista[$i]['label']=$campo;
    $this->camposLista[$i]['Key']="";
    //echo "<br><br>";
     //print_r($this->camposLista);
	}	
  
  	//###################################################################################################################################	
	/**
     * @param campo         é o campo que pretendemos alterar para o tipo imagem
		 * @param $caminho      é o camiho a ser acrescentado a imagem para chegar ao ficheiro
		 * @param percentagem   é a % da altura da imagem
     * 
	* Altera o campo para o tipo imagem para ser visto na lista de forma especial
	*/
	public function setCampoImagem($campo,$caminho,$percentagem){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
				if ($campoaux['Field']==$campo){
					//echo "entrie";
					$this->camposLista[$i]['Type']="img";
					$this->camposLista[$i]['Path']=$caminho;
					$this->camposLista[$i]['widthP']=$percentagem;
				}
				
				$i++;
		}
	}	
	//###################################################################################################################################	
	/**
     * @param campo    é o campo que pretendemos alterar para o tipo lista
		 * @param modo    modo em que são passados os campos. 1 - SQL; 2 - valores.
		 * @param listaSql    listaSql é a string sql ou lista de valores a serem passados ( a lista tem o formato por ex: "1=>primeiro,2=>segunto,3=>utilimo,a=>assim")
     * 
	* Altera o campo para o tipo lista para ter um descritivo em vez do código e uma combobox na edição e introdução
	*/
	public function setCampoLista($campo,$modo,$listaSql){
		$i=0;
		//echo "<br> campo=$campo accao=$modo e valor=$listaSql";
		foreach($this->camposLista as $campoaux){
				if ($campoaux['Field']==$campo){
					//echo "entrie";
					$this->camposLista[$i]['Type']="lst";
					if ($modo=="1"){
						// preenceh com sql
						$listanova=new TableBD();
						$lista=$listanova->consultaSQL($listaSql);
					} else {
            //echo "<br>listasql=$listaSql";
						$lista1=explode(",", $listaSql);
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
					}
					//print_r($lista);
					//echo "<br>";
          //arsort($lista);
					$this->camposLista[$i]['lista']=$lista;
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
	public function setCampoPass($campo,$modo,$cifra){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
				if ($campoaux['Field']==$campo){
					//echo "entrie";
					$this->camposLista[$i]['Type']="pass";
					$this->camposLista[$i]['modo']=$modo;
					$this->camposLista[$i]['cifra']=$cifra;
				}
				
				$i++;
		}
	}	

  
  		//###################################################################################################################################	
	/**
	* @param criterio    é um criterio sql que igual campos a valores
  * 
	* define um critério para a accão de ver
	*/
	public function setCriterio($criterio){
	  $this->criterio=$criterio;
	}	

  
    //###################################################################################################################################	
	/**
     * @param campo    é o campo em que pretendemos definir uma valor inicial
     * @param valor    é o o valor inicial a ser considerado
     * 
	* define um valor padrão a ser considerados numa nova introdução
	*/
	public function setDefaultValue($campo, $valor){
		$i=0;
		//echo "<br> campo=$campo accao=$accao e valor=$valor";
		foreach($this->camposLista as $campoaux){
				if ($campoaux['Field']==$campo){
					//echo "entrie";
					$this->camposLista[$i]['default']=$valor;
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
		foreach($this->camposLista as $campo){
				$this->camposLista[$i]['label']=$campo['Field'];
				$i++;
		}
	}

  
  //###################################################################################################################################	
	/**
     * @param $NumReg    número de registos
     * @param $LimInf    registo inicial
     * 
	* Define o número de resistos num select
	*/
	public function setLimites($NumReg, $LimInf=0){
    $this->limites[0]=$NumReg;
    $this->limites[1]=$LimInf;
		
	}	
 
 //###################################################################################################################################
	/**
	* 
	* @param pagina    pagina para ver uma ficha
	*
	* Guarda o nome da página que mostra o artigo
	*/
	public function setPaginaVer($pagina){
		$this->PagVer=$pagina;
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
	 * @param valor    é a string com o texto que queremos ter na lista da tabela
	 *
	 * define o título da página/ou form
	 */
  public function setTitulo($valor){
    
    $this->setTextos("titulo",$valor);      
  }
	  
}

//###################################################################################################################################
//###################################################################################################################################
//###################################################################################################################################

?>
