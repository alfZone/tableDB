<?php
namespace classes\string;

/**
 * the idea of this class is to manipulate string
 * @author António Lira Fernandes
 * @version 1.3
 * @updated 2022-03-12
 */


class Strings{
    
    //class constructor 
    public function __construct(){
    
    }

    //#########################################################################################################################################
    // return the rest after a substring
    public function after($start, $inthat){
        if (!is_bool(strpos($inthat, $start)))
            return substr($inthat, strpos($inthat,$start)+strlen($start));
    }
    
    //#########################################################################################################################################
    // return the rest after the last ocurrency of a substring
    function after_last ($start, $inthat){
        if (!is_bool($this->strrevpos($inthat, $start)))
        return substr($inthat, $this->strrevpos($inthat, $start)+strlen($start));
    }
  
    //#########################################################################################################################################
    // return a substring before a substring
    function before ($start, $inthat){
        return substr($inthat, 0, strpos($inthat, $start));
    }
  
    //#########################################################################################################################################
    // ...
    function before_last ($start, $inthat){
        return substr($inthat, 0, $this->strrevpos($inthat, $start));
    }
  
    //#########################################################################################################################################
    // show informations
    function between($start, $that, $inthat){
        return $this->before ($that, $this->after($start, $inthat));
    }
  
    //#########################################################################################################################################
    // end transaction
    function between_last ($start, $that, $inthat){
        return $this->after_last($start, $this->before_last($that, $inthat));
    }

    //#########################################################################################################################################
    function strrevpos($instr, $needle){
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    }
  
    //#########################################################################################################################################
    function get_between($input, $start, $end){
        $substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1));
        return $substr;
    }
  
    //#########################################################################################################################################
    // returns last insert ID
    //!!!! if called inside a transaction, must call it before closing the transaction!!!!!!
    function left($str, $length) {
        return substr($str, 0, $length);
    }
  
    //#########################################################################################################################################
    function right($str, $length) {
        return substr($str, -$length);
    }

    //#########################################################################################################################################
    // Clears special characters in filenames. $string is the text where we intend to replace accented characters and with the same unaccented 
    // letters, the ç with the c and the spaces with _. Returns a "clean" string.
	function cleanPoop($string) {

    // input Array
    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

    // Output Array
    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_' );

    // return string
    return str_replace($what, $by, $string);
}

	//#########################################################################################################################################
	// Clears special characters in filenames. $string is the text where we intend to replace accented characters and with the same unaccented 
	// letters, the ç with the c and the spaces with _. Returns a "clean" string.
	function strtolowerPT($string) {
	
	    // input Array
	    $what = array( 'À','Á','É','Í','Ó','Ú','Ñ','Ç','Ã','Õ','Î','Ô','Û','Â','Ê','ì' );
	
	    // Output Array
	    $by   = array( 'à','á','é','í','ó','ú','ñ','ç','ã','õ','î','ô','û','â','ê','ì');
	
	    // return string
	    return str_replace($what, $by, $string);
	}
  
  //#########################################################################################################################################
	// change weekdays to pt.
	function weekDaysPt($string) {
	
	    // input Array
	    $what = array( 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	
	    // Output Array
	    $by   = array( 'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo');
	
	    // return string
	    return str_replace($what, $by, $string);
	}
}
