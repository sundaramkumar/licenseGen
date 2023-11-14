<?php
/** ***************
 * packlic.php
 * 11/03/2010
 * To package the License Key
 * 
 * @author Kumar S
 * @copyright 2010
 * eStrategies
 *
 *
 *****/


 /**
  * cRc4
  *
  * @package EzTest
  * @author Kumar S
  * @copyright 2009
  * @version 1.1
  * @access public
  * 
  * Encrypt and Decrypt a string using RC4 algorithm
  * Written to package the License Key 
  *  
  */
//Security Code.
// error_reporting(E_STRICT|E_ERROR|E_PARSE);
$SEC_CODE = 'iMy@sY$53m%m4$!on';
class cRc4{
    private $pStr;  //processed string
    public function cRc4($data,$pwd){
        $pwd = $keyfile;
        $pwd_length = strlen($pwd);
        
        //added to avoid the "devisible by zero" error
        if ($pwd_length > 0){ 
            for ($i = 0; $i < 255; $i++) {
                $key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
                $counter[$i] = $i;
            }
        }
        for ($i = 0; $i < 255; $i++) {
            $x = ($x + $counter[$i] + $key[$i]) % 256;
            $temp_swap = $counter[$i];
            $counter[$i] = $counter[$x];
            $counter[$x] = $temp_swap;
        }
        for ($i = 0; $i < strlen($data); $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $counter[$a]) % 256;
            $temp = $counter[$a];
            $counter[$a] = $counter[$j];
            $counter[$j] = $temp;
            $k = $counter[(($counter[$a] + $counter[$j]) % 256)];
            $Zcipher = ord(substr($data, $i, 1)) ^ $k;
            $Zcrypt .= chr($Zcipher);
        }
        $this->pStr = $Zcrypt;        
    }
    public function getProcessedStr(){
        return $this->pStr;
    }
    /**
     * Hex to Binary Converter
     * Used to get the password
     * after encoding
     */    
    public function hex2bin($hexdata){
        for ($i=0;$i<strlen($hexdata);$i+=2) {
            $bindata.=chr(hexdec(substr($hexdata,$i,2)));
        }
        $this->pStr = $bindata;
    }   
}

/**
 * Code to encrypt 
 * License the file
 * 
 */  
require_once("./proc.php");
if(file_exists("mansion.fpt")){
    //encrypt
    $contents = file_get_contents("mansion.fpt");
    $rc4 = new cRc4(bin2hex($contents),SEC_CODE);
    $enc_data = $rc4->getProcessedStr();
    $gzdata = gzcompress($enc_data,9);
    if(! file_put_contents("mansion.lic",$gzdata) ){
        echo "Error while creating the License File";        
    }else{
        echo "License File created Successfully";
    }
}else{
  echo "Error: FootPrint file not found. Generate the FootPrint file first";
}
?> 