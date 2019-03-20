<?php
	require_once("../db/dbcontroller.php");
	$db_handle = new DBController();
	$userId = "";
	$status = "";
	$message = "";
	//session_start();
	$findQuery = "";
	$findQuery = array();
	$screens = array();
	$sessuserId = $_SESSION['FF_SESS_USER_ID'];
	$sessuserName =$_SESSION['FF_SESS_USER_NAME']; 
	if(!empty($_POST["language"]) && !empty($_POST["description"]) && !empty($_POST["regionallanguage"])) 
	{
		
		$db_handle->audittrail("languageadd", $sessuserName,"audittrailcms");
		$languge = $_POST["language"];
		$descriptions = $_POST["description"];
		$regionallanguage = $_POST["regionallanguage"];
		date_default_timezone_set('Asia/Kolkata');
		$dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		$today = new MongoDate($ts);
		$query = array('language'=>$languge);
		$res = $db_handle->reteriveResult($query,'languagemasters');
		if(!empty($res))
		{
			if($res->count()>0)
				$message = "Language ".$languge." Already Exist";
			else
			{
				$db_handle->insert(array('regionallanguage'=>$regionallanguage,'language'=>$languge,'description'=>$descriptions,'status'=>'Active','createddate'=>$today,'suspendeddate'=>null,'createdusername'=>$sessuserName),'languagemasters');
				$message = "Language ".$languge." Success fully created";
				$db_handle->audittrail("langugeadd", $sessuserName,"audittrailcms");
			}
		}
		
			
	}

	
?>
