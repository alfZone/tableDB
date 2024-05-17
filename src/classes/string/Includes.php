<?php
namespace classes\string;

/**
 * the idea of this class is to manipulate string
 * @author António Lira Fernandes
 * @version 1.1
 * @updated 2022-03-12
 */


class Includes{

    //class constructor 
    public function __construct(){
        
    }

    public function thereAreIncludes($text){
        $result=false;
        if ($this->str_contains($text, 'include(')) {
            $result=true;
        }
        return $result;
    }

    public function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }

    public function loadIncludes($text){

        $text= str_replace('include_once(' , 'include(', $text);
        while($this->thereAreIncludes($text)) {
            //get path
            $path=$this->getIncludePath($text);
            //echo $path;
            //read file
            $newText=$this->readFileI($path[1]);
            //echo "<br>" . $path[1]. " - " . $newText;  
            //replace
            $text= str_replace('<?php include(' . $path[0] . ');?>', $newText, $text);
            
          }
        return $text;
    }

    public function getIncludePath($text){
        $st=new Strings();
        $path[0]=$st->between('include(',');',$text);
        $path[1]= str_replace("'", '', $path[0]);
        $path[1]= str_replace('"', '', $path[1]);

        return $path;
    }

    public function readFileI($file,$safe=1){
        $result="File no found: " . $file;
        //echo "############################read file################################";
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$file)) {
            //echo $file;
            $result=file_get_contents($_SERVER['DOCUMENT_ROOT'].$file);
            //echo "<br>" . $result;
            //echo $safe;
            //echo "############################end of result################################";
            if ($safe==1){
                //echo "############################safe mode################################";
                $result=$this->cleanPHP($result);
                //echo $result;
                //echo "############################end of safe mode################################";
            }
            
        }
        return $result;
    }

    public function thereArePHP($text){
        $result=false;
        if ($this->str_contains($text, '<?php')) {
            $result=true;
        }
        return $result;
    }

    public function cleanPHP($text){
        //echo "#######################################<br>";
        while($this->thereArePHP($text)) {
            //get part
            //echo "#######################################<br>";
            $part=$this->getPHPpart($text);
            //echo $part;
            //read file 
            //replace
            $text= str_replace('<?php' . $part . '?>', '', $text);
            
          }
        return $text;
    }

    public function getPHPpart($text){
        $st=new Strings();
        $part=$st->between('<?php','?>',$text);


        return $part;
    }

}
