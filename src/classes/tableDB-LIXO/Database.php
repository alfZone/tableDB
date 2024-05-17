<?php
namespace classes\db;
use PDO;

/**
 * the idea of this class is provide a interface for a mysql database.
 * @author António Lira Fernandes
 * @version 1.3
 * @updated 2022-05-19
 * https://github.com/alfZone/DataBase/blob/main/Database.php
 */

// problems detected
// 

// roadmap
// 

//news of version: 
//   - List database tables


// REQUIRES
	
// MISSION: provide a connection to a database, passing user, pass, and database name
  
// METHODS
// __construct($user, $pass, $dbname, $host="localhost") - Class Constructor. $user is the database username, $pass is the database password, $dbname is the database name, 
//                                                         and $host is an optional parameter for the location of the database server (usually this is localhost)
// listTables()  - prepare a list of existing tables in the database.
// resultset() -  return an array with the last result for an action


class Database{
    private $_dbh;
    private $_stmt;
    private $_queryCounter = 0;
    private $_errors;

  
  
    //class constructor 
    public function __construct($user, $pass, $dbname, $host="localhost"){
      
      //echo "aqui";
        $dsn = 'mysql:host='. $host .';dbname=' . $dbname;
        //$dsn = 'sqlite:myDatabase.sq3';
        //$dsn = 'sqlite::memory:';
        $options = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_PERSISTENT => true
                    );
        try {
            $this->_dbh = new PDO($dsn, $user, $pass, $options);
        }
        catch (PDOException $e) {
            $this->_errors=$e->getMessage();
            //exit();
        }
    }

    //#########################################################################################################################################
    // begin transaction // must be innoDatabase table
    public function beginTransaction(){
        return $this->_dbh->beginTransaction();
    }
    
    //#########################################################################################################################################
    // assing values to a sql paramteter
    public function bind($pos, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->_stmt->bindValue($pos, $value, $type);
      //echo $this->debugDumpParams();
    }
  
    //#########################################################################################################################################
    // cancel transaction
    public function cancelTransaction(){
        return $this->_dbh->rollBack();
    }
  
    //#########################################################################################################################################
    // cancel transaction
    public function rollBack(){
        return $this->cancelTransaction();
    }
  
    //#########################################################################################################################################
    // show informations
    public function debugDumpParams(){
        return $this->_stmt->debugDumpParams();
    }
  
    //#########################################################################################################################################
    // end transaction
    public function endTransaction(){
        return $this->_dbh->commit();
    }

    //#########################################################################################################################################
    public function execute(){
        $this->_queryCounter++;
       
      //print_r($this->getError());
       return $this->_stmt->execute();
       
    }
  
    //#########################################################################################################################################
    public function getError(){
      return $this->_errors;
    }
  
    //#########################################################################################################################################
    // returns last insert ID
    //!!!! if called inside a transaction, must call it before closing the transaction!!!!!!
    public function lastInsertId(){
        return $this->_dbh->lastInsertId();
    }
  
    //#########################################################################################################################################
    public function query($query){
        $this->_stmt = $this->_dbh->prepare($query);
        //echo "<br><br>$sql<br><bR>";
        $this->_errors=$this->_dbh->errorInfo();
        //print_r($this->_dbh->errorInfo());
    }

    //#########################################################################################################################################
    // returns number of queries executed
    public function queryCounter(){
        return $this->_queryCounter;
    }

    //#########################################################################################################################################
    // return an array with the last result for an action
    public function resultset(){
        $this->execute();
        $this->_errors=$this->_dbh->errorInfo();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      
    //#########################################################################################################################################
    // returns number of rows updated, deleted, or inserted
    public function rowCount(){
        return $this->_stmt->rowCount();
    }
  
    //#########################################################################################################################################
    public function single(){
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    //#########################################################################################################################################
    // prepare a list of existing tables in the database.
    public function listTables(){
        $sql="Show Tables";
        $this->query($sql);
        $this->_errors=$this->_dbh->errorInfo();
    }
  
}
