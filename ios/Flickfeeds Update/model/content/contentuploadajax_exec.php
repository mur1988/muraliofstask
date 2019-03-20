<?php

$rootfolder = "../../content/";
require_once("../../s3.php");
$findQuery = array();
$message = "";
require_once("../../db/dbcontroller.php");
$db_handle = new DBController();
session_start();
$sessuserId = $_SESSION['FF_SESS_USER_ID'];
$sessuserName =$_SESSION['FF_SESS_USER_NAME']; 
$query=array();
$contentArray = array();
$excludeFileArray = array();
if(!empty($_POST['validateParameters'])  && !empty($_POST['batchId']) && !empty($_POST['category']) && !empty($_POST['language']) && !empty($_POST["mandParameters"]) && !empty($_POST["remParameters"]))
{
	$isDuplicate = "true";
	$language = $_POST['language'];
	$category=$_POST['category'];
	$isRegional=$_POST['isRegional'];
	$catRes = $db_handle->reteriveResult(array('_id'=>new MongoId($category)),'categorymasters'); 
	$viewType = "";
	foreach($catRes as $docres)
			$viewType = $docres['viewtype'];
			
	$viewRes = $db_handle->reteriveResult(array('viewtype'=>new MongoId($viewType)),'inputblueprints'); 
	
	
	if(!empty($_POST['subcategory']))
		$subcategory=$_POST['subcategory'];
	else
		$subcategory=null;
	
	if(!empty($_POST["isDuplicateCheck"]))
       $isDuplicateCheck = $_POST["isDuplicateCheck"];
   else
	   $isDuplicateCheck = "NA";
   
   echo $isDuplicateCheck;
   
   if($isDuplicateCheck !="NA")
   {
	   $duplicateArr = explode(",",$isDuplicateCheck);
	   $dupfindQuery =  array();
		$dupfindQuery["LanguageId"] =  new MongoId($language);
		$dupfindQuery["CategoryId"] = new MongoId($category);
        if($subcategory != null)		
			$dupfindQuery["SubCategoryId"] = new MongoId($subcategory);
		
		foreach($duplicateArr as $dupval)
		     $dupfindQuery["Content.".$dupval] = $_POST[$dupval];
		$res = $db_handle->reteriveResult($dupfindQuery,"contentdetails");
		
	    if($res->count()>0)
		{
			$isDuplicate = "false";
			echo "fails";
		}
	   
   }
   
   if($isDuplicate == "true")
   {
	$validateParameters = $_POST['validateParameters'];
	$mandParameters = $_POST['mandParameters'];
	$batchId =$_POST['batchId'];
	$remParameters = $_POST['remParameters'];

	$query["CategoryId"] = new MongoId($category);
	$query["LanguageId"] = new MongoId($language);
	$query["isRegional"] = $isRegional;
	if($subcategory!=null)
		$query["SubCategoryId"] = new MongoId($subcategory);
	else
		$query["SubCategoryId"] = $subcategory;
	
	
		
		if($batchId =="NA")
				$mainBatchId=date("dmy")."".time();
		else
				$mainBatchId=$batchId;
	$validateParamArray	 = explode("~",$validateParameters);
		foreach($validateParamArray as $parm)
		{
			//echo $parm;
			if($remParameters !="NA")
				$remParmArr =  explode("~",$remParameters);
			else
				$remParmArr = array();
			if(!empty($_POST[$parm])) {
				if(!(in_array($parm,$remParmArr))){
						$contentArray[$parm] = $_POST[$parm];
				}
			}
			else if(!empty($_POST['base64ValueOf'.$parm]))
			{
				
				$image_array_1 = explode(";", $_POST['base64ValueOf'.$parm]);
				$image_array_2 = explode(",", $image_array_1[1]);
				$value = base64_decode($image_array_2[1]);
				$imageName = date("dmY")."".time().'.png';
				$obj->base64_to_s3($_POST['base64ValueOf'.$parm],$imageName,$imageName);
				$contentArray[$parm] = $imageName;
				array_push($excludeFileArray,$parm);
				//echo $imageName;
				
			}
			else  if(!empty($_POST['base64ValueArrayOf'.$parm]))
			{
				$imageNameArr = array();
						$namecounter = 0;
						//echo $parm+"test";
						//var_dump($_POST['base64ValueArrayOf'.$parm]);
						$jsonarrayImg = explode("~",$_POST['base64ValueArrayOf'.$parm]);
						
						foreach($jsonarrayImg as $base64ImgeArr){
							$imageName = $namecounter.date("dmY")."".time().'.png';
							$obj->base64_to_s3($base64ImgeArr,$imageName,$imageName);
							//$obj->uploadToS3Backet($value,$imageName);
							//file_put_contents($rootfolder."/".$imageName, $value);		
							array_push($imageNameArr,$imageName);
							$namecounter++;
						}
						array_push($excludeFileArray,$parm);
						$contentArray[$parm] = $imageNameArr;
			}
			else if(!empty($_POST[$parm."imgDifSizAdd"]))
			{
				$namecounter = 0;
				$imageNameArr =  array();
				$fullImgArr = explode('<<SP=ST>>',$_POST[$parm."imgDifSizAdd"]);
				foreach($fullImgArr as $intArr){
					$interImgArr = explode("~",$intArr); 
					$fileName  = $namecounter.date("dmY")."".time();
					$imageNameStored = $fileName.".png";
					$imageName = $fileName.".png";
					$i = 0;
					foreach($interImgArr as $baspngArr) {
						if($i === 1) $imageNameStored = $fileName."_inside.png";
						else if($i === 2) $imageNameStored = $fileName."_front.png";
						//echo $baspngArr;
						$obj->base64_to_s3(trim($baspngArr),$imageNameStored,$imageNameStored);
						$i++;
					}
					array_push($imageNameArr,$imageName);
					$namecounter++;
					
				}
				array_push($excludeFileArray,$parm);
				$contentArray[$parm] = $imageNameArr;
				
				
			}
			else if(!empty($_FILES[$parm])){
							//echo "FILE";
							$file_name= $_FILES[$parm]['name'];
							$temp_name=$_FILES[$parm]["tmp_name"];
							$ext= pathinfo($file_name, PATHINFO_EXTENSION);
							$tempimgname = date("dmY")."".time();
							$imagename=$tempimgname.".png";
							$obj->uploadToS3Backet($_FILES[$parm]["tmp_name"],$imagename);
							//$target_path = $rootfolder."/".$imagename;
							//if(move_uploaded_file($temp_name, $target_path))
								//			$imageFilePath =$target_path;
							if(!(in_array($parm,$remParmArr)))
									$contentArray[$parm] = $imagename;
								
						  if(!empty($_FILES[$parm."_front"])) {
						    $file_name=$_FILES[$parm."_front"]['name'];
							$temp_name=$_FILES[$parm."_front"]["tmp_name"];
							$ext= pathinfo($file_name, PATHINFO_EXTENSION);
							$imagename=$tempimgname."_front.png";
							$obj->uploadToS3Backet($_FILES[$parm."_front"]["tmp_name"],$imagename);
						  }
						  
						  if(!empty($_FILES[$parm."_inside"])){
						    $file_name=$_FILES[$parm."_inside"]['name'];
							$temp_name=$_FILES[$parm."_inside"]["tmp_name"];
							$ext= pathinfo($file_name, PATHINFO_EXTENSION);
							$imagename=$tempimgname."_inside.png";
							$obj->uploadToS3Backet($_FILES[$parm."_inside"]["tmp_name"],$imagename);
						  }
				
			}
			else{
				if(!(in_array($parm,$remParmArr))){
					$contentArray[$parm] = "";
				}
			}
			
			/*else {
				if(isset($_FILES[$parm]) ) {
					$imageArr = array();
					$initnum = 0;
					if(is_array($_FILES[$parm]['tmp_name']))
					{
							foreach($_FILES[$parm]['tmp_name'] as $key => $tmp_name )
							{
								  if(!empty($_FILES[$parm]['tmp_name'][$key])){
									  $initnum ++;
										$file_name=$_FILES[$parm]['name'][$key];
										$temp_name=$_FILES[$parm]["tmp_name"][$key];
										$ext= pathinfo($file_name, PATHINFO_EXTENSION);
										$imagename=$initnum."".date("dmY")."".time()."."."png";
										//$imageName = date("dmY")."".time().'.png';
										$obj->uploadToS3Backet($value,$imageName);
										//$target_path = $rootfolder."/".$imagename;
										//if(move_uploaded_file($temp_name, $target_path))
											$imageFilePath =$imagename;
										array_push($imageArr,$imagename);
									}
						   }
						   if(!(in_array($parm,$remParmArr)))
								$contentArray[$parm] = $imageArr;
					}
					else
					{
						if(!in_array($parm,$excludeFileArray)){
							$file_name=$_FILES[$parm]['name'];
							$temp_name=$_FILES[$parm]["tmp_name"];
							$ext= pathinfo($file_name, PATHINFO_EXTENSION);
							$imagename=$initnum."".date("dmY")."".time()."."."png";
							$obj->uploadToS3Backet($value,$imageName);
							$imageFilePath =$imagename;
							if(!(in_array($parm,$remParmArr)))
									$contentArray[$parm] = $imagename;
						}
					}
			  }
		 }*/
	}

	$sortQuery = array("BatchName"=>-1);
	$result = $db_handle->reteriveResultWithSort(array("BatchId"=>$mainBatchId),"tempcontent",$sortQuery);
	if(!empty($result))
	{
		$tempbatchname = "";
		$innerArr = array();
		foreach($result as $doc)
		{
			
			if($tempbatchname != $doc["BatchName"])
			{
				if($tempbatchname == "")
				{
					$tempbatchname = $doc["BatchName"];
					array_push($innerArr,$doc["Content"]);
				}
				else
				{
					$contentArray[$tempbatchname] = $innerArr;
					$innerArr = array();
					array_push($innerArr,$doc["Content"]);
					$tempbatchname = $doc["BatchName"];
				}
			}
			else
			{
					array_push($innerArr,$doc["Content"]);
			}
		}
		if($tempbatchname!="")
			$contentArray[$tempbatchname] = $innerArr;
		
	}
	
	if($mandParameters !="NA")
	{
		//echo $mandParameters;
		$mandParmArr  = explode("~",$mandParameters);
		foreach($mandParmArr as $mandParm)
		{
			//echo $mandParm;
			$mandParmSplit = explode(",",$mandParm);
			//echo $mandParmSplit[0];
			//$type =  $mandParmSplit[1];
			//echo $type;
			if(!(array_key_exists($mandParmSplit[0],$contentArray)))
			{    
				if($mandParmSplit[1]  == "array")
						$contentArray[$mandParmSplit[0]] = array();
					else
						$contentArray[$mandParmSplit[0]] = "";
			}
		}
	}
	foreach($viewRes  as $vew)
	{
			foreach($vew['contnetimprinttype'] as $cnimpKey => $cnimpValue)
			{
				if($cnimpValue == "optionsr")
				{
					$optC = $vew[$cnimpKey];
					$optinValue = $optC['optionvalue'];
					//$already = false;
					foreach($optC['optionvalue'] as $z => $y)
					{
						if(!array_key_exists($z, $contentArray)){
							if(is_array($y))
								$contentArray[$z] = [];
							else
								$contentArray[$z] = "";
						}
						
					}
				}
				else if($cnimpValue == "optionsc")
				{
					
				}
				else if(is_array($cnimpValue))
				{
					if(!array_key_exists($cnimpKey, $contentArray)){
						$temArrInter =  [];
						foreach($cnimpValue as $arKey=>$arrValue)
							$temArrInter[$arKey]= "";
						$tempOuterArray= [];
						array_push($tempOuterArray,$temArrInter);
					   $contentArray[$cnimpKey] = $tempOuterArray;
					}
					
				}
				else if($cnimpValue == "multiSelectWithAd"){
					if(!array_key_exists($cnimpKey, $contentArray))
						$contentArray[$cnimpKey] = [];
				}
				else {
					if(!array_key_exists($cnimpKey, $contentArray))
						$contentArray[$cnimpKey] = "";
				}
			}
	}
	
	$query['Content'] = $contentArray;
	$query['CreatedUser'] = $sessuserName;
	date_default_timezone_set('Asia/Kolkata');
	$dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
	$ts = $dt->getTimestamp();
	$today = new MongoDate($ts);
	$query['CreatedDate'] = $today;
	$query['Status'] = "Inactive";
	$query['UserLikeTotalCount'] = "0";
    $query['UserUnLikeTotalCount'] = "0";
	$query['isNotificationSent'] = 0;
	$db_handle->insert($query,"contentdetails");
	$qr = array();
	$qr["BatchId"]=$mainBatchId;
	$db_handle->remove($qr,"tempcontent");
   }

}
else
{

}
	


?>

