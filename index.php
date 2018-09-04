<?php

$method = $_SERVER['REQUEST_METHOD'];
//process only when method id post
if($method == 'POST')
{
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	$com = $json->queryResult->parameters->command;
	$com = strtolower($com);
	
		
	if ($com == 'amountsold' or $com == 'margin' or $com == 'qtysold' or $com=='shoplist') 
	{
		if(isset($json->queryResult->parameters->STATE))
		{	$STATE= $json->queryResult->parameters->STATE; } else {$STATE = '0';}
		$STATE= strtoupper($STATE);
		if(isset($json->queryResult->parameters->CITY))
		{	$CITY= $json->queryResult->parameters->CITY; } else {$CITY = '0';}
		//$CITY= $json->queryResult->parameters->CITY;
		$CITY= strtoupper($CITY);
		if(isset($json->queryResult->parameters->SHOPNAME))
		{	$SHOPNAME= $json->queryResult->parameters->SHOPNAME; } else {$SHOPNAME = '0';}
		if(isset($json->queryResult->parameters->YR))
		{	$YR= $json->queryResult->parameters->YR; } else {$YR = '0';}
		
		if(isset($json->queryResult->parameters->QTR))
		{	$QTR= $json->queryResult->parameters->QTR; } else {$QTR = '0';}
		
		if(isset($json->queryResult->parameters->MTH))
		{	$MTH= $json->queryResult->parameters->MTH; } else {$MTH = '0';}
		//$SHOPNAME= $json->queryResult->parameters->SHOPNAME;
		$SHOPNAME= strtoupper($SHOPNAME);
		$SHOPNAME = str_replace(' ', '', $SHOPNAME);
		$CITY = str_replace(' ', '', $CITY);
		$STATE = str_replace(' ', '', $STATE);
		if($CITY=="" )
		{
			$CITY='0';
			
		}
		$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($STATE, $userespnose))
		{
			$STATE = 'ALL';
		}
		$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($SHOPNAME, $userespnose))
		{
			$SHOPNAME = 'ALL';
		}
		$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($CITY, $userespnose))
		{
			$CITY = 'ALL';
		}
		$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($YR, $userespnose))
		{
			$YR = 'ALL';
		}
		
		$json_url = "http://74.201.240.43:8000/ChatBot/Sample_chatbot/EFASHION_DEV.xsjs?command=$com&STATE=$STATE&CITY=$CITY&SHOPNAME=$SHOPNAME&YR=$YR&QTR=$QTR&MTH=$MTH";		
		//echo $json_url;
		$username    = "SANYAM_K";
    		$password    = "Welcome@123";
		$ch      = curl_init( $json_url );
    		$options = array(
        	CURLOPT_SSL_VERIFYPEER => false,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_USERPWD        => "{$username}:{$password}",
        	CURLOPT_HTTPHEADER     => array( "Accept: application/json" ),
    		);
    		curl_setopt_array( $ch, $options );
		$json = curl_exec( $ch );
		$someobj = json_decode($json,true);
		if($com == 'amountsold' or $com == 'margin' or $com == 'qtysold')
		{
			if ($com == 'amountsold')
				$distext = "Total sale value is of worth $";
			else if($com == 'margin')
				$distext = "Total profit value is of worth $";
			else if ($com == 'qtysold')
				$distext = "Total quantity sold of worth $";
			if($CITY !='0')
			{
				$discity = " for city ";
			}
			else
			{
				$discity = "";
			}
			if($SHOPNAME != '0')
			{
				$disshop = " of shop ";
			}
			else
			{	$disshop = "";	}
			
			if($YR != '0')
			{
				$disyear = " for year ";} else {$disyear = "";}
			foreach ($someobj["results"] as $value) 
			{
				$speech .= $distext. $value["AMOUNT"].$disshop.$value["SHOP_NAME"].$discity.$value["CITY"]." in ".$value["STATE"].$disyear.$value["YR"];
				$speech .= "\r\n";
			 }
		}
		else if($com == 'shoplist')
		{
			foreach ($someobj["results"] as $value) 
			{
				$speech .= $value["SHOP_NAME"]." availabe in ".$value["CITY"]." in ".$value["STATE"];
				$speech .= "\r\n";
			 }
		}
		
			
	}
	
	
	$response = new \stdClass();
    	$response->fulfillmentText = $speech;
    	$response->source = "webhook";
	echo json_encode($response);

}
else
{
	echo "Method not allowed";
}

?>
