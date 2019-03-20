

<?php
require_once("../../db/dbcontroller.php");
$db_handle = new DBController();


if(!empty($_POST["moveToTopId"]) && !empty($_POST["sessUserId"])){
	date_default_timezone_set('Asia/Kolkata');
	$dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
	$ts = $dt->getTimestamp();
	$today = new MongoDate($ts);
	$newdata = array('$set'=>array('Priority'=>$today,'UpdatedUser'=>$_POST["sessUserId"]));
	$db_handle->update(array('_id'=>new MongoId($_POST["moveToTopId"])),$newdata,"contentdetails");
}

if(!empty($_POST["publishId"]) && !empty($_POST["sessUserId"]) && !empty($_POST["status"]))
{
	$newdata = array('$set'=>array('Status'=>$_POST["status"],'UpdatedUser'=>$_POST["sessUserId"]));
	$db_handle->update(array('_id'=>new MongoId($_POST["publishId"])),$newdata,"contentdetails");
}

if(!empty($_POST["newpwd"]) && !empty($_POST["oldpwd"]) && !empty($_POST["headerUserId"])) 
{
	$findQuery['_id'] = new MongoId($_POST["headerUserId"]);
	$findQuery['password'] = md5($_POST["oldpwd"]);	
	$count = $db_handle->reteriveResult($findQuery,"logindetailscms")->count();
	//echo $count;
	if($count>0)
	{
		echo 'success';
		$newdata = array('$set'=>array('password'=>md5($_POST["newpwd"])));
		$db_handle->update(array('_id'=>new MongoId($_POST["headerUserId"])),$newdata,"logindetailscms");
	}
	else
		echo 'fails';
}

if(!empty($_POST["newWord"]) && !empty($_POST["collName"]) && !empty($_POST["sessUserId"])) 
{
	$count = $db_handle->reteriveResult(array($_POST["collName"]=>$_POST["newWord"]),$_POST["collName"])->count();
	if($count<=0)
	{
		date_default_timezone_set('Asia/Kolkata');
		$dt = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		$today = new MongoDate($ts);
		echo 'success';
		$db_handle->insert(array($_POST["collName"]=>$_POST["newWord"],"CreatedDate"=>$today,"CreatedUser"=>$_POST["sessUserId"]),$_POST["collName"]);
	}
	else
		echo 'fails';
}

if(!empty($_POST["role"]) && !empty($_POST["roleId"])) 
{
	$role = $_POST["role"];
	$roleId =  new MongoId($_POST["roleId"]);
	$result = $db_handle->reteriveResult(array('role'=>$role,'_id'=>array('$ne'=>$roleId)),'roles');	
	if(!empty($result))
	{
		
		if($result->count()>0)
			{
		?>
				<font style='color:Red'>Role Name  Already exsist </font>
				<script>
				document.getElementById("roleVal").value =false;
			</script>
		<?php
			}
			else
			{
			?>
			<font style='color:Green'>Role Name Avaliable </font>
			<script>
				document.getElementById("roleVal").value =true;
			</script>
			<?php
			}
	}
	
	
}
else if(!empty($_POST["role"])) 
{
	$role = $_POST["role"];
	$result = $db_handle->reteriveResult(array('role'=>$role),'roles');	
	if(!empty($result))
	{
		
		if($result->count()>0)
			{
		?>
				<font style='color:Red'>Role Name  Already exsist </font>
				<script>
				document.getElementById("roleVal").value =false;
			</script>
		<?php
			}
			else
			{
			?>
			<font style='color:Green'>Role Name Avaliable </font>
			<script>
				document.getElementById("roleVal").value =true;
			</script>
			<?php
			}
	}
	
	
}


if(!empty($_POST["language"])) 
{
		$language = $_POST["language"];
		$result = $db_handle->reteriveResult(array('languageid'=>new MongoId($language),'status'=>'Active'),'categorymasters');
				// $finalquery = [array('$match' => array('languageid'=>new MongoId($language))),
					// array('$lookup'=> array('from'=>'categorymaster','localField'=>'categoryid','foreignField'=>'_id','as'=>'catref')),
					// array('$unwind'=> '$catref'),
					// array('$project' => array('categoryid' => 1,'category' => '$catref.category')
					// )];
					
					
			// $result = $db_handle->reteriveAgregateResult($finalquery ,'categoryxlanguage');	
			echo '<option value="All"></option>';
		if(!empty($result))
		{
		
			?>
				
				<?php
			   foreach($result as $doccument)	
			   {
				   
					   ?>
					   
						<option value="<?php echo $doccument['_id']?>"> <?php echo $doccument['category']?> </option>
					   <?php	
				  
			   }
			
				
		}
}

if(!empty($_POST["regionallanguage"])) {
	$language = $_POST["regionallanguage"];
	$result = $db_handle->reteriveResult(array('_id'=>new MongoId($language),'status'=>'Active'),'languagemasters');
	if(!empty($result)){
		echo '<option value="N" selected>English</option>';
		foreach($result as $doccument)	
		echo '<option value="Y">'.$doccument['regionallanguage'].'</option>';
	}
}

if(!empty($_POST["commonLanguage"])) 
{
		$language = $_POST["commonLanguage"];
		$result = $db_handle->reteriveResult(array('languageid'=>new MongoId($language),'status'=>'Active'),'categorymasters');
				// $finalquery = [array('$match' => array('languageid'=>new MongoId($language))),
					// array('$lookup'=> array('from'=>'categorymaster','localField'=>'categoryid','foreignField'=>'_id','as'=>'catref')),
					// array('$unwind'=> '$catref'),
					// array('$project' => array('categoryid' => 1,'category' => '$catref.category')
					// )];
					
					
			// $result = $db_handle->reteriveAgregateResult($finalquery ,'categoryxlanguage');	
		if(!empty($result))
		{
		
			?>
				<option value="All">--All--</option>
				<?php
			   foreach($result as $doccument)	
			   {
				   
					   ?>
					   
						<option value="<?php echo $doccument['_id']?>"> <?php echo $doccument['category']?> </option>
					   <?php	
				  
			   }
			
				
		}
}

if(!empty($_POST["languageWithSub"])) 
{
		$language = $_POST["languageWithSub"];
		$result = $db_handle->reteriveResult(array('languageid'=>new MongoId($language),'subcategoryavl'=>'AVL'),'categorymasters');

		if(!empty($result))
		{
		
			?>
				<option value="All">All</option>
				<?php
			   foreach($result as $doccument)	
			   {
				   
					   ?>
					   
						<option value="<?php echo $doccument['_id']?>"> <?php echo $doccument['category']?> </option>
					   <?php	
				  
			   }
			
				
		}
}

if(!empty($_POST["category"])) 
{
		$category = $_POST["category"];
		$catres = $db_handle->reteriveResult(array('_id'=>new MongoId($category)),'categorymasters');
		$subCat ="NotAvl";
		if(!empty($catres))
		{
			if($catres->count()>0)
			{
				foreach($catres as $docs)
				{
					$subCat = $docs["subcategoryavl"];
				}
					
			}
		}
		if($subCat == "AVL")
		{
			?>
			 <script> 
							document.getElementById("isSubcat").value = true;			   
			</script>
			<?php
			$result = $db_handle->reteriveResult(array('categoryid'=>new MongoId($category)),'subcategorymasters');
			if(!empty($result))
			{
				if($result->count()>0)
				{
				?>
					<option value=""></option>
					<?php
					   foreach($result as $doc)	
					   {
						   ?>
						   <option value="<?php echo $doc['_id']?>"> <?php echo $doc['subcategory']?> </option>
						   <?php
					   }
					 ?>
				  
				 <?php
				}
				else
				{
					?>
					 <script> 
				document.getElementById("isSubCatNotCreated").value = true;
				</script> 
				<?php
				}
					
			}
			else
			{
				?>
					 <script> 
				document.getElementById("isSubCatNotCreated").value = true;
				</script> 
				<?php
			}
		}
		else
		{
				?>
					 <script> 
							document.getElementById("isSubcat").value = false;
							document.getElementById("isSubCatNotCreated").value = false;							
					</script>
			  <?php
		}
}

if(!empty($_POST["commonCategory"])) 
{
		$category = $_POST["commonCategory"];
		$result = $db_handle->reteriveResult(array('categoryid'=>new MongoId($category)),'subcategorymasters');
		if(!empty($result))
		{
			echo '<option value="All">--All--</option>';
			if($result->count()>0)
			{
			?>
				<?php
				   foreach($result as $doc)	
				   {
					   ?>
					   <option value="<?php echo $doc['_id']?>"  > <?php echo $doc['subcategory']?> </option>
					   <?php
				   }
			}
		}
}		


?>