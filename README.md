# tabledb
 Generic class that allows managing a database table. Creates a page to perform basic operations with a table.
 
 The idea for this object is to provide a simple way to manage a databes table. With some configurations we can list a tables, add a new record, change and update a record, delete a record and insert several records using a csv file.
 
 // REQUIRES: Database.php
 
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
// setTexts($ text, $ value) - Load the class with the texts to be used in the graphical interface - it's an arrey [$ text] = $ value
// setTitulo($ value) - sets the title of the page / or form 
