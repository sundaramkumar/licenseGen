<?
#proc.php
$wmi = new COM('winmgmts:{impersonationLevel=impersonate}//./root/cimv2');

if (!is_object($wmi)) {
	echo 'This needs access to WMI. Please enable DCOM';
	exit;
}



function getCpu(){
	global $wmi;
	$object = $wmi->ExecQuery("SELECT * FROM Win32_Processor");
	$retStr = "";
	foreach($object as $cpu) {
		$retStr = $cpu->UniqueId;
		if($retStr == ""){
			$retStr = $cpu->ProcessorId;
			if($retStr == ""){
				$retStr = $cpu->Name;
				if($retStr == ""){
					$retStr = $cpu->Manufacturer;
				}
			}		
		}
		
		$retStr .= $cpu->MaxClockSpeed;
	}
	return $retStr;
}


function getBios(){
	global $wmi;
	$object = $wmi->ExecQuery("SELECT * FROM Win32_BIOS");
	$retStr = "";
	foreach($object as $bios) {
		$retStr .= $bios->Manufacturer;		
		$retStr .= $bios->SMBIOSBIOSVersion;
		$retStr .= $bios->IdentificationCode;
		$retStr .= $bios->SerialNumber;
		$retStr .= $bios->ReleaseDate;
		$retStr .= $bios->Version;
	}
	return $retStr;
}

function getBase(){
	global $wmi;
	$object = $wmi->ExecQuery("SELECT * FROM Win32_BaseBoard");
	$retStr = "";
	foreach($object as $base) {
		$retStr .= $base->Model;		
		$retStr .= $base->Manufacturer;
		$retStr .= $base->Name;
		$retStr .= $base->SerialNumber;
	}
	return $retStr;
}

function getVdo(){
	global $wmi;
	$object = $wmi->ExecQuery("SELECT * FROM Win32_VideoController");
	$retStr = "";
	foreach($object as $vdo) {
		$retStr .= $vdo->VideoProcessor;		
		$retStr .= $vdo->Name;
	}
	return $retStr;
}

function getFootPrint(){
	$fpData = substr(chunk_split( md5( getCpu().getBios().getBase().getVdo() ), 4,"-"),0,-1);

	if(! file_put_contents("mansion.fpt",$fpData) ){
        echo "Error while creating the FootPrint File";        
    }else{
        echo "FootPrint File created Successfully";
    }
}

// echo md5( getCpu().getBios().getBase().getVdo() );
// echo "<br/>";
// echo substr(chunk_split( md5( getCpu().getBios().getBase().getVdo() ), 4,"-"),0,-1);

?>