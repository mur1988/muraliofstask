<?php
	require_once("../db/dbcontroller.php");
	$db_handle = new DBController();
	$status = "";
	$message = "";
	//session_start();
	$findQuery = "";
	$findQuery = array();
	$screens = array();
	$sessuserId = $_SESSION['FF_SESS_USER_ID'];
	$sessuserName =$_SESSION['FF_SESS_USER_NAME']; 
	$screenList = $db_handle->reteriveResult(array(),'screens');
	$languageId =$_GET["id"];
	$resultLanguage ="";
	$resultDescription ="";
	$resultStatus ="";
	if(!empty($_POST["language"]) && !empty($_POST["status"]) &&  !empty($_POST["description"]) && !empty($_POST["languageId"])) 
	{
		
		//echo "inside";
		
		
		
		date_default_timezone_set('Asia/Kolkata');
		$dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		$today = new MongoDate($ts);
		$newdata = array();
		$language = $_POST["language"];
		$status  = $_POST["status"];
		$description = $_POST["description"];
		
		$locresult = $db_handle->reteriveResult(array('language'=>$language,'_id'=>array('$ne'=>new MongoId($languageId))),'languagemasters');
		if(!empty($locresult))
		{
			if($locresult->count()>0)
			{
				$message = "Language ".$language." Name  Already exsist";
			}
			else
			{
				$newdata = array('language'=>$language,'description'=>$description,'status'=>$status);
		
				if($status == "Inactive")
					$newdata['suspendeddate']=$today;
				else
					$newdata['suspendeddate']=null;
				$newvaldata =  array('$set' => (array)$newdata);
				$db_handle->update(array("_id" => new MongoId($languageId)),$newvaldata,"languagemasters");
				$message = "Language Succesfully updated";
			}
		}
		
	}
	
	$result = $db_handle->reteriveResult(array('_id'=>new MongoId($_GET["id"])),'languagemasters');
	$db_handle->audittrail("languageedit", $sessuserName,"audittrailcms");
	foreach ($result as $doc)
	{
		$resultLanguage =$doc["language"];
		$resultDescription =$doc["description"];
		$resultRegionalLanguage= $doc["regionallanguage"];
		$resultStatus =$doc["status"];
	}
	
	
	
	
?>
