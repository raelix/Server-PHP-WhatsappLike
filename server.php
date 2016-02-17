<?php
//ricordarsi di sostituire il +2 con +1 col cambio del fuso orario
		$insert="INSERT INTO devices (user, idtelefono, password) VALUES ('raelix', 'idphonenumber','mypass')";
$remove="DELETE FROM devices WHERE idtelefono='Griffin' AND user='carmelo'";
$search="SELECT * FROM Persons WHERE FirstName='Peter'";
$getxml = "";
$mode=$_GET['mode'];

switch ($mode) {
case 1:
	//registrazione
	$usr = $_GET['usr'];
	$idkey = $_GET['idkey'];
	$tel = $_GET['tel'];
	$imei = $_GET['imei'];
	controllaRegistra($usr,$idkey,$tel,$imei);
	break;
case 2:
	//Invio Sms
	$sender= $_GET['snd'];
	$reciver= $_GET['rcv'];
	$msg = $_GET['msg'];
	sendPush($sender,$reciver,$msg);
	break;
case 3:
	//ControlloRegistrazione
	$number = $_GET['tel'];
	isRegistered($number);
	break;
case 4:
	//Notifica Messaggio Ricevuto
	$sender= $_GET['snd'];
	$reciver= $_GET['rcv'];
	$idkey = $_GET['idkey'];
	notificaRicevuta($sender,$reciver,$idkey);
	break;
case 5:
	//Setta status
	$status= $_GET['status'];
	$idkey = $_GET['idkey'];
	setStatus($status,$idkey);
	break;
case 6:
	//Ritorna status
	$tel = $_GET['tel'];
	getStatus($tel);
	break;
case 7:
	//Ups Valori
	$TN	= $_GET['TN'];
	$PB    =  $_GET['PB'];
	$DS    =  $_GET['DS'];
	$IV     =  $_GET['IV'];
	$UC    =  $_GET['UC'];
	$W     =  $_GET['W'];
	$kWh     =  $_GET['kWh'];
	UPSdata(  "A".$TN,$PB,$DS,$IV,$UC,$W,$kWh);
	remove1MonthAgo("A".$TN);
	break;
case 8:
	//return value ups
	$TN  = $_GET['TN'];
	$RET = $_GET['RET'];
	remove1MonthAgo("A".$TN);
	returnValueUPS("A".$TN,$RET);
	break;
	case 9:
	//Invio Sms
	$reciver= $_GET['rcv'];
	$msg = $_GET['msg'];
	send($reciver,$msg);
	break;
	case 10:
	//return value ups
	$TN  = $_GET['TN'];
	$RET = $_GET['RET'];
	remove1MonthAgo("A".$TN);
	returnValuesUPS("A".$TN,$RET);
	break;
	case 11:
	$TN  = $_GET['TN'];
	$RET = $_GET['RET'];
	remove1MonthAgo("A".$TN);
	returnValuesUPShours("A".$TN,$RET);
	break;
	case 12:
	$TN  = $_GET['TN'];
	$RET = $_GET['RET'];
	remove1MonthAgo("A".$TN);
	returnValuesUPSdays("A".$TN,$RET);
	break;
	case 13:
	$TN  = $_GET['TN'];
	remove1MonthAgo("A".$TN);
	getEnergyToday("A".$TN);
	break;
	case 14:
	$TN  = $_GET['TN'];
	remove1MonthAgo("A".$TN);
	getEnergyLastDay("A".$TN);
	break;
	case 15:
	$TN  = $_GET['TN'];
	remove1MonthAgo("A".$TN);
	getEnergyMonth("A".$TN);
	break;
		case 16:
	$TN  = $_GET['TN'];
	remove1MonthAgo("A".$TN);
	getEnergyLastMonth("A".$TN);
	break;
}



function getEnergyToday($TN){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime("today midnight");
	$dat = date('Y-m-d H:i:s',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w0 = $row['energy'];
		$date = $row['date'];
		//echo "&".$id."&".$w0."&".$date;
		$id++;

	}
		$time_ago = strtotime("-12 seconds");
	$dat = date('Y-m-d H:i:s',  $time_ago);
			$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id ASC LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w1 = $row['energy'];
		$date = $row['date'];
		//echo "&".$id."&".$w1."&".$date;
		$id++;

	}
	$res = $w1 - $w0;
	echo "&EG&".$res;
	}


function getEnergyMonth($TN){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime("first day of this month midnight");
	$dat = date('Y-m-d H:i:s',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w0 = $row['energy'];
		$date = $row['date'];
		//echo "mesee".$dat;
		$id++;

	}
		$time_ago = strtotime("-12 seconds");
	$dat = date('Y-m-d H:i:s',  $time_ago);
			$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id ASC LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w1 = $row['energy'];
		$date = $row['date'];
		//echo "&".$id."&".$w1."&".$date;
		$id++;

	}
	$res = $w1 - $w0;
	echo "&EGThisMonth&".$res;
	}

function getEnergyLastDay($TN){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime("yesterday midnight");
	$dat = date('Y-m-d H:i:s',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w0 = $row['energy'];
		$date = $row['date'];
		//echo "&".$id."&".$w0."&".$date;
		$id++;

	}
		$time_ago = strtotime("today midnight");
	$dat = date('Y-m-d H:i:s',  $time_ago);
			$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id ASC LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w1 = $row['energy'];
		$date = $row['date'];
		//echo "&".$id."&".$w1."&".$date;
		$id++;

	}
	//echo $w1." ".$w0;
	$res = $w1 - $w0;
	echo "&EGLastDay&".$res;
	}
	
	
function getEnergyLastMonth($TN){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime("first day of last month midnight");
	$dat = date('Y-m-d H:i:s',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w0 = $row['energy'];
		$date = $row['date'];
	//	echo "mesee".$dat;
		$id++;

	}
		$time_ago = strtotime("first day of this month midnight");
	$dat = date('Y-m-d H:i:s',  $time_ago);
			$exist="SELECT * FROM ".$TN."  WHERE date >= '".$dat."'  ORDER BY id ASC LIMIT 0,1"; 
	$c = false;
	$result = mysqli_query($con,$exist);
$id = 0;
	while($row = mysqli_fetch_array($result)){  
			$w1 = $row['energy'];
		$date = $row['date'];
		//echo "&".$id."&".$w1."&".$date;
		$id++;

	}
	$res = $w1 - $w0;
	echo "&EGLastMonth&".$res;
	}
	
function UPSdata($TN,$PB,$DS,$IV,$UC,$W,$kWh){
	$con=mysqli_connect("localhost","root","","ups");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Create table
	$sql="CREATE TABLE IF NOT EXISTS ".$TN." (ID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID),pb INT,ds FLOAT,iv INT,uc INT,w INT,date TEXT,energy DOUBLE)";

	// Execute query
	if (mysqli_query($con,$sql))
	{
		echo "Table ".$TN." created successfully";
		$time_ago = strtotime("now");
		$date = date("Y-m-d H:i:s",$time_ago);   
		$insert="INSERT INTO  ".$TN." (pb, ds, iv, uc, w,date,energy) VALUES (' ".$PB." ',' ".$DS." ',' ".$IV." ',' ".$UC." ' ,' ".$W." ','".$date."', ' ".$kWh. " ' )";
		$results = mysqli_query($con,$insert);
	}
	else
	{
		echo "Error creating table: " . mysqli_error($con);
	}
}
function returnValuesUPS($TN,$RET){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime(" -".$RET." minutes ");
	$dat = date('Y-m-d H:i:s ',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date > '".$dat."'  ORDER BY id DESC"; 
	$c = false;
	$result = mysqli_query($con,$exist);

	while($row = mysqli_fetch_array($result)){  
			$w = $row['w'];
		if($c == false){
		$id = $row_cnt = $result->num_rows;
		$c = true;
		echo $id;
	} 
	$dat = date('i',  strtotime($row['date']));
		echo "&".$w."&".$dat;
		

	}
	}

function returnValuesUPShours($TN,$RET){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime("-".$RET." hours ");
	$dat = date('Y-m-d H:i:s ',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date > '".$dat."'  ORDER BY id DESC"; 
	$c = false;
	$result = mysqli_query($con,$exist);

	while($row = mysqli_fetch_array($result)){  
			$w = $row['w'];
		if($c == false){
		$id = $row_cnt = $result->num_rows;
		$c = true;
		echo $id;
	} 
	$dat = date('H',  strtotime($row['date']));
		echo "&".$w."&".$dat;
		

	}
	}

function returnValuesUPSdays($TN,$RET){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/


				$time_ago = strtotime("-".$RET." days  ");
	$dat = date('Y-m-d H:i:s ',  $time_ago);
		$exist="SELECT * FROM ".$TN."  WHERE date > '".$dat."'  ORDER BY id DESC"; 
	$c = false;
	$result = mysqli_query($con,$exist);

	while($row = mysqli_fetch_array($result)){  
			$w = $row['w'];
		if($c == false){
		$id = $row_cnt = $result->num_rows;
		$c = true;
		echo $id;
	} 
	$dat = date('d',  strtotime($row['date']));
		echo "&".$w."&".$dat;
		

	}
	}


function returnValueUPS($TN,$RET){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	/*ORDER BY `id` DESC*/
	if($RET == -1)
		$exist="SELECT * FROM ".$TN."  ORDER BY id DESC LIMIT 1 ";
	else if ($RET == -2)
		$exist="SELECT * FROM ".$TN."  ORDER BY id DESC ";
		else{ 
			$time_ago = strtotime(" -".$RET." days ");
	$dat = date('Y-m-d H:i:s ',  $time_ago);
		$exist="SELECT * FROM ".$TN." WHERE date > '".$dat."'  ORDER BY id DESC";
	}
	$result = mysqli_query($con,$exist);
	while($row = mysqli_fetch_array($result))
	{  
		$pb = $row['pb'];
		$ds = $row['ds'];
		$iv = $row['iv'];
		$uc = $row['uc'];
		$w = $row['w'];
		$date = $row['date'];
		$kWh = $row['energy'];
		$dat = date('d-m-Y H:i:s',  strtotime($date));
		echo $pb."&".$ds."&".$iv."&".$uc."&".$w."&".$dat."&".$kWh."&";
		echo '<br/>';

	}

}

function isRegistered($number){
	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	$exist="SELECT * FROM devices ";
	$result = mysqli_query($con,$exist);
	while($row = mysqli_fetch_array($result))
	{  
		$value = $row['tel'];
		if($value == $number){
			$value = $row['name'];
			echo $value;
			return;
		}
		if($row['idkey'] == $number){
			$value = $row['tel'];
			echo $value;
			return;
		}
	}
	echo "empty";
}

function remove1MonthAgo($TN){
	$con=mysqli_connect("localhost","root","","ups");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	$time_ago = strtotime("-33 days");
	$dat = date('Y-m-d H:i:s ',  $time_ago);
	mysqli_query($con,"DELETE FROM ".$TN." WHERE date <= '".$dat."'");
	//mysqli_query($con,"ALTER TABLE '".$TN."' DROP 'id'; ALTER TABLE '".$TN."' AUTO_INCREMENT = 1; ALTER TABLE '".$TN."' ADD 'id' int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;")
	//mysqli_query($con,"DELETE FROM ".$TN." WHERE id > '205000'  ");//circa 8 al minuto
}

function sendPush($sender,$reciver,$msg){


	if($sender == "" || $sender == "%20" || strlen($sender) < 5){
		echo 'numero sender valida';
		return;
	}
	if($reciver == "" || $reciver == "%20" || strlen($reciver) < 1){
		echo 'numero reciver non valida';
		return;
	}


	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	$exist="SELECT * FROM devices ";
	$result = mysqli_query($con,$exist);
	while($row = mysqli_fetch_array($result))
	{  
		$value = $row['tel'];

		if($value == $sender){

			$con=mysqli_connect("localhost","root","","testing");
			if (mysqli_connect_errno()){
				echo "Failed to connect to MySQL: ";
			}
			$exist="SELECT * FROM devices ";
			$result = mysqli_query($con,$exist);
			while($row = mysqli_fetch_array($result))
			{  
				$rcvphone = $row['tel'];
				if($rcvphone == $reciver){
					$rcvphone = $row['idkey'];
					sendmessage("ack&2&".$sender."&".$msg,$rcvphone);
					$ack = "spedito&".$row['tel'];
					echo $ack;
					return;
				}
			}
		}
	}
}


function send($reciver,$msg){



	if($reciver == "" || $reciver == "%20" || strlen($reciver) < 1){
		echo 'numero reciver non valida';
		return;
	}


	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	$exist="SELECT * FROM devices ";
	$result = mysqli_query($con,$exist);
$con=mysqli_connect("localhost","root","","testing");
			if (mysqli_connect_errno()){
				echo "Failed to connect to MySQL: ";
			}
			$exist="SELECT * FROM devices ";
			$result = mysqli_query($con,$exist);
			while($row = mysqli_fetch_array($result))
			{  
				$rcvphone = $row['tel'];
				if($rcvphone == $reciver){
					$rcvphone = $row['idkey'];
					sendmessage($msg,$rcvphone);
					$ack = "spedito&".$row['tel'];
					echo $ack;
					return;
				}
			}
		}
	


function notificaRicevuta($sender,$reciver,$idkey){


	if($sender == "" || $sender == "%20" || strlen($sender) < 5){
		echo 'numero sender valida';
		return;
	}
	if($reciver == "" || $reciver == "%20" || strlen($reciver) < 1){
		echo 'numero reciver non valida';
		return;
	}
	if($idkey == "" || $idkey == "%20" || strlen($idkey) < 1){
		echo 'chiave non valida';
		return;
	}

	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	$exist="SELECT * FROM devices ";
	$result = mysqli_query($con,$exist);
	while($row = mysqli_fetch_array($result))
	{  
		$value = $row['idkey'];
		if($value == $idkey){

			if($row['tel'] == $sender){
				echo "la tua chiave e' corretta, spedisco il messaggio al: ".$reciver;
				$con=mysqli_connect("localhost","root","","testing");
				if (mysqli_connect_errno()){
					echo "Failed to connect to MySQL: ";
				}
				$exist="SELECT * FROM devices ";
				$result = mysqli_query($con,$exist);
				while($row = mysqli_fetch_array($result))
				{  
					$rcvphone = $row['tel'];
					if($rcvphone == $sender){
						$rcvphone = $row['idkey'];
						sendmessage("ack&4&".$sender,$rcvphone);
						echo "Notificato correttamente";
						return;
					}
				}
			}
		}
	}
}


function getStatus($tel){
	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}
	$exist="SELECT * FROM devices ";
	$result = mysqli_query($con,$exist);
	while($row = mysqli_fetch_array($result))
	{  
		$value = $row['tel'];
		if($value == $tel){
			echo $row['status'];
			return;}}
	echo "error";
}


function controllaRegistra($usr,$idkey,$tel,$imei){

	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: ";
	}

	if($idkey == "" || $idkey == "%20" || strlen($idkey) < 10){
		echo 'chiave non valida';
		return;
	}
	if($usr == "" || $usr == "%20" || strlen($usr) < 1){
		echo 'nome utente non valido';
		return;
	}
	if($tel == "" || $tel == "%20" || strlen($tel) < 2){
		echo 'numero di telefono non valido';
		return;
	}
	if($imei == "" || $imei == "%20" || strlen($imei) < 10){
		echo 'imei utente non valido';
		return;
	}

	$exist="SELECT * FROM devices ";
	$result = mysqli_query($con,$exist);
	while($row = mysqli_fetch_array($result))
	{  
		$value = $row['idkey'];
		if($value == $idkey){
			echo 'chiave già memorizzata utente registrato come: '.$row['name'];
			mysqli_close($con);
			return;
		}
	}
	registra($usr,$idkey,$tel,$imei);
	echo "Sei stato registrato correttamente!";
	return;
}

function registra($usr,$idkey,$tel,$imei){
	$con=mysqli_connect("localhost","root","","testing");
	$idKey = $idkey;
	$insert="INSERT INTO devices (name, idkey, imei, tel, status) VALUES ('".$usr."', '".$idkey."','".$imei."','".$tel."','Online')";
	$results = mysqli_query($con,$insert);
	mysqli_close($con);
	$msg = 'ack&1&'.$usr."&".$idKey;
	sendmessage($msg,$idKey);
}

function setStatus($status,$idkey){
	$con=mysqli_connect("localhost","root","","testing");
	$idKey = $idkey;
	$insert="UPDATE devices SET status='".$status."' WHERE idkey='".$idkey."'";
	$results = mysqli_query($con,$insert);
	echo "fatto";	
	mysqli_close($con);
}
function sendmessage($msg,$idkey){
	$registatoin_ids=array($idkey);
	$msg=array("message"=>$msg);
	$url='https://android.googleapis.com/gcm/send';
	$fields=array
			(
					'registration_ids'=>$registatoin_ids,
					'data'=>$msg
					);
	$headers=array
			(
					'Authorization: key=AIzaSyDEZDpgDCEIcTX7O3WxOFEtpgi2p5iUDrg',
					'Content-Type: application/json'
					);
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
	$result=curl_exec($ch);
	curl_close($ch);	
}






function doQuery($query){
	$con=mysqli_connect("localhost","root","","testing");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$result = mysqli_query($con,$query);
	//echo $result;
	mysqli_close($con);
}



$rootEl="Devices";			//nome tabella
$childEl="User";				//nome primo figlio
$query="SELECT * FROM dispositivi WHERE user='Peter'" ; //query da restituire in xml
//getXML($rootEl,$childEl,$query);

function getXML($rootElementName,$childElementName,$query){
	$Result = "<?xml version='1.0' encoding='utf-8'?>\n";
	mysql_connect('localhost', 'root', '');
	mysql_select_db('testing');
	$queryResult = mysql_query($query);

	$xmlData = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n"; 
	$xmlData .= "<" . $rootElementName . ">\n";

	while($record = mysql_fetch_object($queryResult))
	{ 
		$xmlData .= "<" . $childElementName . " ";

		for ($i = 0; $i < mysql_num_fields($queryResult); $i++)
		{ 
			$fieldName = mysql_field_name($queryResult, $i); 
			$xmlData .=  " ".$fieldName . "='";
			if(!empty($record->$fieldName))
				$xmlData .= $record->$fieldName."'"; 
				else
					$xmlData .= "vuoto' " ; 

		} 
		$xmlData .= " />\n"; 
	} 
	$xmlData .= "</" . $rootElementName . ">"; 

	echo $xmlData; 
}
?>
