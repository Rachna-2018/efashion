<?php
$method = $_SERVER['REQUEST_METHOD'];
//process only when method id post
if($method == 'POST')
{
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	$com = $json->queryResult->parameters->command;
	$com = strtolower($com);
	
		
	if ($com == 'listcity' or $com == 'liststates' or $com == 'amountsold' or $com == 'margin' or $com == 'qtysold' or $com=='shoplist' or $com=='listfamily' or $com=='listcategory' or $com=='listarticle') 
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
	
	
	     if(isset($json->queryResult->parameters->FAMILY))
		{	$FAMILY= $json->queryResult->parameters->FAMILY; } else {$FAMILY = '0';}
	
	     if(isset($json->queryResult->parameters->CATEGORY))
		{	$CATEGORY= $json->queryResult->parameters->CATEGORY; } else {$CATEGORY = '0';}
	
	      if(isset($json->queryResult->parameters->ARTICLE))
		{	$ARTICLE= $json->queryResult->parameters->ARTICLE; } else {$ARTICLE = '0';}
		//$SHOPNAME= $json->queryResult->parameters->SHOPNAME;
		$SHOPNAME= strtoupper($SHOPNAME);
		$SHOPNAME = str_replace(' ', '', $SHOPNAME);
		$CITY = str_replace(' ', '', $CITY);
		$STATE = str_replace(' ', '', $STATE);
		
		$FAMILY= strtoupper($FAMILY);
		$FAMILY = str_replace(' ', '', $FAMILY);
		$CATEGORY= strtoupper($CATEGORY);
		$CATEGORY = str_replace(' ', '', $CATEGORY);
		$ARTICLE= strtoupper($ARTICLE);
		$ARTICLE = str_replace(' ', '', $ARTICLE);
		
		
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
			$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($FAMILY, $userespnose))
		{
			$FAMILY = 'ALL';
		}
		
			$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($CATEGORY, $userespnose))
		{
			$CATEGORY = 'ALL';
		}
		
			$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($ARTICLE, $userespnose))
		{
			$ARTICLE = 'ALL';
		}
		$json_url = "http://74.201.240.43:8000/ChatBot/Sample_chatbot/EFASHION_DEV.xsjs?command=$com&STATE=$STATE&CITY=$CITY&SHOPNAME=$SHOPNAME&YR=$YR&QTR=$QTR&MTH=$MTH&FAMILY=$FAMILY&CATEGORY=$CATEGORY&ARTICLE=$ARTICLE";		
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
			if($CITY !='0')	{ $discity = " for city "; } else { $discity = ""; }
			if($STATE !='0'){ $disstate = " in state "; } else { $disstate = ""; }
			if($FAMILY !='0'){ $disfamily = " for family "; } else {$disfamily = ""; }
            		if($CATEGORY !='0'){ $discategory = " for category "; }	else { $discategory = ""; }
            		if($ARTICLE !='0'){$disarticle = " for article ";} else	{ $disarticle = ""; }
			if($SHOPNAME != '0') { $disshop = " of shop "; } else{	$disshop = "";	}
			if($YR != '0')	{      $disyear = " for year ";} else {$disyear = "";}
			foreach ($someobj["results"] as $value) 
			{
				$speech .= $distext. $value["AMOUNT"].$disshop.$value["SHOP_NAME"].$discity.$value["CITY"].$disstate.$value["STATE"].$disyear.$value["YR"].$disfamily.$value["FAMILY_NAME"].$discategory.$value["CATEGORY"].$disarticle.$value["ARTICLE_LABEL"];
				$speech .= "\r\n";
			 }
			if($speech != "") { $speech .= "I can drill down further\n";}
		}
		else if($com == 'shoplist')
		{
			foreach ($someobj["results"] as $value) 
			{
				$speech .= $value["SHOP_NAME"]." availabe in ".$value["CITY"]." in ".$value["STATE"];
				$speech .= "\r\n";
			 }
		}
		else if ($com == 'liststates')
		{
			$speech = "You can see values for following states";
			$speech .= "\r\n";
			foreach ($someobj["results"] as $value) 
			{
				
				$speech .= $value["STATE"]." - ".$value["SHORT_STATE"];
				$speech .= "\r\n";
			}
			$speech .= "Which would you prefer?";
			
		}
		else if ($com == 'listcity')
		{
			$speech = "You can see values for following cities";
			$speech .= "\r\n";
			foreach ($someobj["results"] as $value) 
			{
				
				$speech .= $value["CITY"];
				$speech .= "\r\n";
			}
			$speech .= "Which would you prefer?";
			
		}
		
			
	}
	
	//echo $FAMILY;
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
