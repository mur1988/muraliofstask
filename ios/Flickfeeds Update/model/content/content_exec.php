<?php
	require_once("../db/dbcontroller.php");
	$db_handle = new DBController();
	$res = array();
	$category = "";
	$language = "";
	$fromDate  = Date("Y-m-d");
	$toDate  = Date("Y-m-d") ;
	$subcategory ="";
	$status = "";
	$message = "";
	$matchdata = array();
	$sessuserId = $_SESSION['FF_SESS_USER_ID'];
	$sessuserName =$_SESSION['FF_SESS_USER_NAME']; 
	$languageList = $db_handle->reteriveResult(array('status'=>'Active'),"languagemasters");
	$categoryList = array();
	$subcategorylist = array();
	$subCategorynameArray = array();
	$newFromDate = date("Y-m-d H:i:s", strtotime($fromDate." 00:00:00"));
	$dt = new DateTime($newFromDate, new DateTimeZone('UTC'));
	$ts = $dt->getTimestamp();
	$qfromDate = new MongoDate($ts);
	$newToDate = date("Y-m-d H:i:s", strtotime($toDate." 23:59:59"));
	$tdt = new DateTime($newToDate, new DateTimeZone('UTC'));
	$tds = $tdt->getTimestamp();
	$qtoDate = new MongoDate($tds);
    $matchSubQuery = [array('$project' => array('_id' => 1,'subcategory' => 1))];
	$finalQuery = 	[array('$match'=>array('CreatedDate'=>array('$gte'=> $qfromDate
					,'$lt'=> $qtoDate))),
					array('$lookup'=> array('from'=>'languagemasters','localField'=>'LanguageId','foreignField'=>'_id','as'=>'langname')),
					array('$unwind'=> '$langname'),
					array('$lookup'=> array('from'=>'categorymasters','localField'=>'CategoryId','foreignField'=>'_id','as'=>'catname')),
					array('$unwind'=> '$catname'),
					array('$sort' => array('CreatedDate'=>-1)),
					array('$project' => array('_id' => 1,'UserLikeTotalCount'=>1,'UserUnLikeTotalCount'=>1,'language' => '$langname.language','category' => '$catname.category','CreatedUser' => 1,'Status' => 1,'Content' => 1,'CreatedDate' => 1,"SubCategoryId"=>1,"isNotificationSent"=>1,"UpdatedUser"=>1,"regionalLanguage"=>'$langname.regionallanguage',"isRegional"=>1 )
					)];
		//print_r($finalQuery);
	$db_handle->audittrail("contnetSearch", $sessuserName,"audittrailcms");
	if(!empty($_POST["category"]) && !empty($_POST["status"]) && !empty($_POST["subcategory"]) && !empty($_POST["language"])
		&& !empty($_POST["fromDate"]) && !empty($_POST["toDate"])) 
	{
		$category = $_POST["category"];
		$status = $_POST["status"];
		$subcategory =  $_POST["subcategory"];
		$language=  $_POST["language"];
        $macthQuery = array();
		$fromDate = $_POST["fromDate"];
		$toDate = $_POST["toDate"];
		$newFromDate = date("Y-m-d H:i:s", strtotime($fromDate." 00:00:00"));
		$dt = new DateTime($newFromDate, new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		$qfromDate = new MongoDate($ts);
		$newToDate = date("Y-m-d H:i:s", strtotime($toDate." 23:59:59"));
		$tdt = new DateTime($newToDate, new DateTimeZone('UTC'));
		$tds = $tdt->getTimestamp();
		$qtoDate = new MongoDate($tds);
		 $dateFilterArray = array();
		 $dateFilterArray['$gte'] = $qfromDate;
		 $dateFilterArray['$lt'] = $qtoDate;
		 
		 $macthQuery["CreatedDate"] = $dateFilterArray ;
		 
        if($status != "All")
		  $macthQuery["Status"]= $status;	
		if($category != "All") {
			$macthQuery["CategoryId"]= new MongoId($category);	
			$subcategorylist = $db_handle->reteriveResult(array('categoryid'=>new MongoId($category)),"subcategorymasters");
			$matchSubQuery = [array('$match' => array('categoryid'=> new MongoId($category))),
							  array('$project' => array('_id' => 1,'subcategory' => 1))];
		}
		if($subcategory != "All")		
			$macthQuery["SubCategoryId"] = new MongoId($subcategory);
		if($language != "All")	{
			$macthQuery["LanguageId"] = new MongoId($language);
			$categoryList = $db_handle->reteriveResult(array('languageid'=>new MongoId($language)),"categorymasters");
		}
			
		
		if(count($macthQuery)>0)
		{
			$finalQuery = 	[array('$match' =>$macthQuery),
							array('$lookup'=> array('from'=>'languagemasters','localField'=>'LanguageId','foreignField'=>'_id','as'=>'langname')),
							array('$unwind'=> '$langname'),
							array('$lookup'=> array('from'=>'categorymasters','localField'=>'CategoryId','foreignField'=>'_id','as'=>'catname')),
							array('$unwind'=> '$catname'),
							array('$sort' => array('CreatedDate'=>-1)),
							array('$project' => array('_id' => 1,'UserLikeTotalCount'=>1,'UserUnLikeTotalCount'=>1,'language' => '$langname.language','category' => '$catname.category','CreatedUser' => 1,'Status' => 1,'Content' => 1,'CreatedDate' => 1,"SubCategoryId"=>1,"isNotificationSent"=>1,"UpdatedUser"=>1 )
							)];
		}
		
	
		
	}
	
	$subCategorynames = $db_handle->reteriveAgregateResult($matchSubQuery,"subcategorymasters");
	foreach($subCategorynames  as $doc)
	{
		$insArr =$doc['firstBatch'];
		for($j=0;$j<count($insArr);$j++)
			$subCategorynameArray[(String)$insArr[$j]['_id']]= $insArr[$j]['subcategory'];
	}
	$result = $db_handle->reteriveAgregateResult($finalQuery,"contentdetails");
	foreach($result as $doc)
	{
		if(!empty($doc['firstBatch'] ))
			$res = $doc['firstBatch'];
	}
	
	//print_r($finalQuery);
	


	
?>

