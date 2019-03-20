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

//foreach($_POST  as $vv=>$gg)
//	echo $vv;

if(!empty($_POST['validateParameters'])  && !empty($_POST['batchId']) && !empty($_POST['category']) && !empty($_POST['language']) && !empty($_POST["mandParameters"]) && !empty($_POST["remParameters"]))
{
	
	 echo "IF";
	$isDuplicate = "true";
	$language = $_POST['language'];
	$category=$_POST['category'];
	$isRegional=$_POST['isRegional'];
	$contentId = $_POST['contentId'];
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
		$dupfindQuery["_id"] = array('$ne'=>new MongoId($_POST["contentId"]));
		
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
		$newData = array();
		$newData['isRegional'] = $isRegional;
		if($batchId =="NA")
				$mainBatchId=date("dmy")."".time();
		else
				$mainBatchId=$batchId;
		$findQuery["BatchId"]=$mainBatchId;
		$validateParamArray	 = explode("~",$validateParameters);
		foreach($validateParamArray as $parm)
		{
			if($remParameters !="NA")
				$remParmArr =  explode("~",$remParameters);
			else
				$remParmArr = array();
			if(!empty($_POST[$parm])) {
				if(!(in_array($parm,$remParmArr)))
					//$contentArray[$parm] = $_POST[$parm];
					$newData['Content.'.$parm] = $_POST[$parm];
			}
			elseif(!empty($_POST["base64ValueOf".$parm])){
				//echo "baseImage";
				$ext = "";
				if($_POST["base64ValueOf".$parm]!="")
					$ext = substr( $_POST["base64ValueOf".$parm], 0, 11 ) ;
				if($ext == "data:image/"){
					$imageName = date("dmY")."".time().'.png';
					$obj->base64_to_s3($_POST['base64ValueOf'.$parm],$imageName,$imageName);
					$newData['Content.'.$parm] = $imageName;
					array_push($excludeFileArray,$parm);
				}
				else
					$newData['Content.'.$parm] = $_POST["base64ValueOf".$parm];
					//$contentArray[$parm] = $_POST["base64ValueOf".$parm];
			}
			elseif(!empty($_POST["base64ValueArrayOf".$parm])){
				$imageNameArr = array();
						$namecounter = 0;
						//echo $parm+"test";
						//var_dump($_POST['base64ValueArrayOf'.$parm]);
							$jsonarrayImg = explode("~",$_POST['base64ValueArrayOf'.$parm]);
							foreach($jsonarrayImg as $base64ImgeArr){
								$extion = "";
								if($base64ImgeArr!="")
									$extion = substr( $base64ImgeArr, 0, 11 ) ;
								if($extion =="data:image/") {
									echo $extion;
									$imageName = $namecounter.date("dmY")."".time().'.png';
									$obj->base64_to_s3($base64ImgeArr,$imageName,$imageName);
									array_push($imageNameArr,$imageName);
									$namecounter++;
								}
								else
									array_push($imageNameArr,$base64ImgeArr);
							}
						array_push($excludeFileArray,$parm);
						$newData['Content.'.$parm] = $imageNameArr;
						print_r($newData['Content.'.$parm]);
						//$contentArray[$parm] = $imageNameArr;
			}
			else if(!empty($_POST[$parm."imgDifSizAdd"]))
			{
				echo "test";
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
						$extion = "";
						if($baspngArr!="")
							$extion = substr( $baspngArr, 0, 11 ) ;
						if($extion =="data:image/") {
							//echo "data";
						if($i === 1) $imageNameStored = $fileName."_inside.png";
						else if($i === 2) $imageNameStored = $fileName."_front.png";
						//echo $baspngArr;
						$obj->base64_to_s3(trim($baspngArr),$imageNameStored,$imageNameStored);
						}
						else{
							if(strpos($baspngArr, '_front.png') === false && strpos($baspngArr, '_inside.png') === false)
									$imageName = $baspngArr;
								//echo $baspngArr;
						}
							
						$i++;
					}
					if($imageName != "")
						array_push($imageNameArr,$imageName);
					$namecounter++;
					
				}
				array_push($excludeFileArray,$parm);
				$newData['Content.'.$parm] = $imageNameArr;
				
				
			}
			else {
				if(isset($_FILES[$parm]) ) {
					$imageArr = array();
					$initnum = 0;
				if(!empty($_FILES[$parm]['tmp_name']))
				{
					if(is_array($_FILES[$parm]['tmp_name']))
					{
						if(!empty($_FILES[$parm]['tmp_name']))
						{
							echo "Enter";
							foreach($_FILES[$parm]['tmp_name'] as $key => $tmp_name )
							{
								  if(!empty($_FILES[$parm]['tmp_name'][$key]))
								  {
									//  echo "enterNotEm";
									    $initnum ++;
										$file_name=$_FILES[$parm]['name'][$key];
										$temp_name=$_FILES[$parm]["tmp_name"][$key];
										$ext= pathinfo($file_name, PATHINFO_EXTENSION);
										$imagename=$initnum."".date("dmY")."".time().".".$ext;
										$obj->uploadToS3Backet($_FILES[$parm]["tmp_name"][$key],$imagename);
										//$target_path = $rootfolder."/".$imagename;
										//if(move_uploaded_file($temp_name, $target_path))
											//$imageFilePath =$target_path;
										array_push($imageArr,$imagename);
									if(!(in_array($parm,$remParmArr)))
											//$contentArray[$parm] = $imageArr;
											$newData['Content.'.$parm] = $imageArr;
									}
									else
									{
										  if(isset($_POST[$parm."oldarr"]))
												//$contentArray[$parm] = explode("~",$_POST[$parm."oldarr"]);
											    $newData['Content.'.$parm] = explode("~",$_POST[$parm."oldarr"]);;
									}
						   }
						}
					}
					else
					{
						echo "test";
							$file_name=$_FILES[$parm]['name'];
							$temp_name=$_FILES[$parm]["tmp_name"];
							$ext= pathinfo($file_name, PATHINFO_EXTENSION);
							$tempimgname=$initnum."".date("dmY")."".time();
							$imagename=$initnum."".date("dmY")."".time()."."."png";
							$obj->uploadToS3Backet($_FILES[$parm]["tmp_name"],$imagename);
							//$target_path = $rootfolder."/".$imagename;
							//if(move_uploaded_file($temp_name, $target_path))
								//			$imageFilePath =$target_path;
							if(!(in_array($parm,$remParmArr)))
								$newData['Content.'.$parm] =$imagename;
									//$contentArray[$parm] = $imagename;
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
				}
				else
				{
					if(isset($_POST[$parm."old"]))
					{
						//$contentArray[$parm] = $_POST[$parm."old"];
						$newData['Content.'.$parm] = $_POST[$parm."old"];
					}
				}
				
			}
			
			
		 }
	}
	$sortQuery = array("BatchName"=>-1);
	$result = $db_handle->reteriveResultWithSort($findQuery,"tempcontent",$sortQuery);
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
					//$contentArray[$tempbatchname] = $innerArr;
					$newData['Content.'.$tempbatchname] =$innerArr;
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
			$newData['Content.'.$tempbatchname] =$innerArr;
			//$contentArray[$tempbatchname] = $innerArr;
		
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
			if(!(array_key_exists('Content.'.$mandParmSplit[0],$newData)))
			{    
				if($mandParmSplit[1]  == "array")
					$newData['Content.'.$mandParmSplit[0]] =array();
						//$contentArray[$mandParmSplit[0]] = array();
					else
						$newData['Content.'.$mandParmSplit[0]] ="";
						//$contentArray[$mandParmSplit[0]] = null;
			}
		}
	}

	//$newData['Content'] = $contentArray;
	//$query['CreatedUser'] = $sessuserName;
	date_default_timezone_set('Asia/Kolkata');
	$dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
	$ts = $dt->getTimestamp();
	$today = new MongoDate($ts);
	//$query['CreatedDate'] = $today;
	//$query['Status'] = "Active";
	$newvaldata =  array('$set' => (array)$newData);
	$db_handle->update(array("_id" => new MongoId($contentId)),$newvaldata,"contentdetails");
	//print_r($newvaldata);
	$qr = array();
	$qr["BatchId"]=$mainBatchId;
	$db_handle->remove($qr,"tempcontent");
   }

}
else
{
 echo "ELSE";
}
	


?>


