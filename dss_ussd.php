<?php
error_reporting(E_ALL);
$msisdn=$_REQUEST['msisdn'];
$sessionid=$_REQUEST['sessionid'];
$type=$_REQUEST['type'];
$choice=$_REQUEST['msg'];
$final_choice="";

function menuCreator($type,$sessionid,$msisdn,$choice) {
	
	if($type==1) {	
		$xml=new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><ussd></ussd>");
        	$xml->addChild('type',$type);
		$xml->addChild('msg',"UNATAKA KUTOA TAARIFA KUHUSU AINA GANI YA TUKIO\r\n1. KIFO\r\n2. KUZALIWA\r\n3. UJAUZITO\r\n4. MTU KUHAMIA\r\n5. MTU KUONDOKA\r\n");	       
	}
	else if($type==2 && $choice!=33 && $choice!=99) {
		$xml=new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><ussd></ussd>");
		$xml->addChild('type',$type);
                $xml->addChild('msg',"Umechagua ".$choice.".\r\n Ingiza 99 kukubali au 33 kurudi nyuma\r\n");

		EventChoice::setStaticValue($choice);
		//print("static value: ".EventChoice::getStaticValue());

                
	}
	else if($type==2 && $choice==33) {
		$xml=new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><ussd></ussd>");
        	$xml->addChild('type',$type);
		$xml->addChild('msg',"UNATAKA KUTOA TAARIFA KUHUSU AINA GANI YA TUKIO\r\n1. KIFO\r\n2. KUZALIWA\r\n3. UJAUZITO\r\n4. MTU KUHAMIA\r\n5. MTU KUONDOKA\r\n");
	}

	else if($type==2 && $choice==99) {
                $xml=new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><ussd></ussd>");
        	$xml->addChild('type',$type);
		$xml->addChild('msg',"Taarifa zako zimefika. Tutakutembelea ndani ya siku 7 kupata taarifa zaidi. Ahsante.");

		// insert into postgres db
		$db = pg_connect("host=localhost port=5432 dbname=ussd_app user=ussd password=ussd1234"); 
		$date = date('Y-m-d');
		$final_choice = EventChoice::getStaticValue();
		$query = "INSERT INTO household_event VALUES ('$msisdn','$final_choice','$date','0')";  
		$result = pg_query($query);
	}

	print($xml->asXML());

	 

}

class EventChoice{

	public static $final_choice = '';

	public function getStaticValue() {
        	return self::$final_choice;
    	}

	public function setStaticValue($value) {				
        	self::$final_choice = $value;		
    	}
}

if ($type==1 || $type==2) {

    //call the menu as per parameter
    menuCreator($type,$sessionid,$msisdn,$choice);
}
else {
    //do some magic
}

?>
