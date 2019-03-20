<?php
	require_once("../db/dbcontroller.php");
	$db_handle = new DBController();
	$language = "";
	$status = "";
	$message = "";
	//session_start();
	$findQuery = "";

	$findQuery = array();
	$screens = array();
	$sessuserId = $_SESSION['FF_SESS_USER_ID'];
	$sessuserName =$_SESSION['FF_SESS_USER_NAME']; 
	$languageList = $db_handle->reteriveResult($findQuery,"languagemasters");
	$result = $languageList;
	$db_handle->audittrail("language", $sessuserName,"audittrailcms");
	if(!empty($_POST["language"]) && !empty($_POST["status"])) 
	{
		
		$language = $_POST["language"];
		$status = $_POST["status"];
		
			if($status == "All" && $language == "All")
				$result = $languageList;
			else 
			{
				
				if($status == "All" && $language != "All") 
					$findQuery = array("_id" => new MongoId($language));
				else if($language == "All" && $status != "All") 
					$findQuery = array("status" => $status);
				else
					$findQuery = array("status" => $status,"_id" => new MongoId($language));
				
				$result = $db_handle->reteriveResult($findQuery,"languagemasters");
			}
		
	
	}


	
?>
