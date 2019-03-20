<?php require_once('../model/common/auth.php');
$_SESSION['SESS_SCREEN_NAME'] = 'cmsusersearch'; ?>
<?php include 'header.php';?>
<?php include 'menu.php';?>



<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="home.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Content</a> </div>
    
  </div>
  
  <div class="container-fluid">
    
	<div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
             <span class="icon"><i class="icon-th"></i></span> 
            <h5>Content</h5>
			<?php include '../model/content/content_exec.php';?>
          </div>
		  
		<div class="widget-content nopadding">
		  <form action="" method="post" name="categoryform" >
		  <table class="table table-bordered">
		  <tr>
		   <td>
		  <div class="control-group">
                <label class="control-label">Language</label>
                <div class="controls">
				<select  id="language" name="language"  onchange="return languageOnChange();">
						<option value="All" <?php if($language == ""){echo "selected='selected'";} ?> > ---- All ---- </option>
						 <?php  foreach($languageList as $document) {
							 if($language == $document['_id']) {
								 echo "<option value='".$document['_id']."' selected='selected'>" .$document['language']. " </option>";
							 }
						      else 
							  {
								  echo "<option value='".$document['_id']."'>" .$document['language']. " </option>";
							  }
						 }?>
                  </select>
				</div>
			</div>
		  <td>
		  <div class="control-group">
                <label class="control-label">Category</label>
                <div class="controls">
				<select  id="category" name="category" onchange="return categoryOnChange();">
						<option value="All" <?php if($category == "All"){echo "selected='selected'";} ?>>---- All ---- </option>
						<?php
							foreach($categoryList as $document)
							{
								if($category == $document['_id']) {
								 echo "<option value='".$document['_id']."' selected='selected'>" .$document['category']. " </option>";
								}
								else 
								{
								  echo "<option value='".$document['_id']."'>" .$document['category']. " </option>";
								}
							}
						?>
						
						
                  </select>
				</div>
			</div>
			</td>
			<td>
				<div class="control-group">
					<label class="control-label">SubCategory</label>
					<div class="controls">
					  <select  id="subcategory" name="subcategory">
						<option value="All" <?php if($subcategory == "All"){echo "selected='selected'";} ?>>---- All ---- </option>
						<?php
							foreach($subcategorylist as $document)
							{
								if($subcategory == $document['_id']) {
								 echo "<option value='".$document['_id']."' selected='selected'>" .$document['subcategory']. " </option>";
								}
								else 
								{
								  echo "<option value='".$document['_id']."'>" .$document['subcategory']. " </option>";
								}
							}
						?>
					 </select>
				  </div>
				</div>
		  </td>
			<td>
				<div class="control-group">
					<label class="control-label">Status</label>
					<div class="controls">
					  <select  id="status" name="status">
							<option value= "All" 		<?php if($status == "All"){echo "selected='selected'";} ?>>---- All ---- </option>
							<option value = "Active" 	<?php if($status == "Active"){echo "selected='selected'";} ?>  >Active </option>
							<option value = "Inactive"  <?php if($status == "Inactive"){echo "selected='selected'";} ?> >Inactive</option>
					 </select>
				  </div>
				</div>
		  </td>
		  <td>
				<div class="control-group">
                <label class="control-label">From Date</label>
                <div class="controls">
                  <input type="text" id="fromDate" name="fromDate" data-date="<?php echo $fromDate; ?>" data-date-format="yyyy-mm-dd" value="<?php echo $fromDate; ?>" class="datepicker"  autocomplete="off" >
				  <span id="errFromDate" class="help-block" ></span> 
              </div>
			  </div>
		  </td>
		  <td>
				<div class="control-group">
                <label class="control-label">To Date</label>
                <div class="controls">
                  <input type="text" id="toDate" name="toDate" data-date="<?php echo $toDate; ?>" data-date-format="yyyy-mm-dd" value="<?php echo $toDate; ?>" class="datepicker"  autocomplete="off" >
				   <span id="errToDate" class="help-block" ></span> 
              </div>
			  </div>
				
		  </td>
		  <td>
		  <div class="control-group">
                <label class="control-label">&nbsp;</label>
                <div class="controls">
				 <button type="submit" class="btn btn-success" onClick="return valid();">Search</button>
				<?php
				  if(in_array('contentupload',$_SESSION['FF_SESS_SCREEN_ARR']))
				  {
					 echo "<a href='contentupload.php' type='submit' class='btn btn-warning' onClick='return validate();'>Add New</a>";
				  }
				  ?>
				  </div>
		
		  </td>
		  </tr>
		  </table>
		  </form>
        </div>
        
      </div>
    </div>
  </div>
</div>


  
  
  
  
  
 <div class="container-fluid"> 
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
             <span class="icon"><i class="icon-th"></i></span> 
            <h5>Content</h5>
			<h5>Records found : <?php echo count($res); ?></h5>
			
          </div>
		<div class="widget-content nopadding">
          <div class="widget-content nopadding">
            <!-- <table class="table table-bordered data-table" id="datacontent">-->
			 <table class="table table-bordered" id="datacontent">
              <thead>
                <tr>
				  <th>SL.No.</th>
                  <th>Language</th>
				  <th>Regional</th>
				  <th>Category</th>
				  <th>Sub Category</th>
				  <th>Content</th>
				  <th>Likes</th>
				  <th>DisLikes</th>
                  <th>CreatedDate</th>
				  <th>Status</th>
				  <th>Created By</th>
				  <th>Publish By</th>
				  <th>View</th>  
				  <?php
				  if(in_array('contentedit',$_SESSION['FF_SESS_SCREEN_ARR']))
				  {
					  echo '<th>Edit</th>';
				  }
				  ?>
				  <th>Push Notification</th>
				  
                </tr>
				</thead>
				 <tbody>
				   <?php 
				//   while($result->next())
				    //var_dump($result);
					//print_r($result);
						$i=1;
						foreach($res as $document) {
								   echo "<tr>";
								   echo "<td>".$i."</td>";
								   echo "<td>".$document['language']."</td>";
								   if($document['isRegional'] == "N") echo "<td>English</td>";
								   else echo "<td>".$document['regionalLanguage']."</td>";
								   echo "<td>".$document['category']."</td>";
								   //subCategorynameArray
								   if($document['SubCategoryId']!= null)
									   echo "<td>".$subCategorynameArray[(string)$document['SubCategoryId']]."</td>";
								   else
										echo "<td>Not Available</td>";
								   echo "<td>";
								   $k = 0;
								   foreach($document['Content'] as $key => $val)
								   {
									   if(!is_array($val))
									   {
										   $cont  = $key." : ".$val;
										   echo wordwrap($cont,10,"<br>\n");
									   }
									   else
										   echo $key." : list" ;
									    echo "</br>";
										$k++;
										if($k>0)
										{
											echo "......";
											break;
										}
								   }
								   echo "</td>";
								   echo "<td>";
								   if(array_key_exists('UserLikeTotalCount',$document) && $document['UserLikeTotalCount']!=0){
									   $pasparm = "'1','".$document['_id']."'";
											echo '<a href="javascript:getUsers('.$pasparm.');">'.$document['UserLikeTotalCount'].'</a>';
								   }
								   else
									  echo "0";
								    echo "</td>";
									echo "<td>";
								   if(array_key_exists('UserUnLikeTotalCount',$document)&& $document['UserUnLikeTotalCount']!=0){
									   $pasparm = "'zero','".$document['_id']."'";
									   echo '<a href="javascript:getUsers('.$pasparm.');">'.$document['UserUnLikeTotalCount'].'</a>';
								   }
								   else
									    echo "0"; 
									echo "</td>";
								   echo "<td>".date('Y-m-d', $document['CreatedDate']->sec)."</td>";
								   echo "<td>".$document['Status']."</td>";
								   echo "<td>".$document['CreatedUser']."</td>";
								   if(array_key_exists('UpdatedUser',$document))
											echo "<td>".$document['UpdatedUser']."</td>";
									else
											echo "<td> </td>";										
								   $idLoc = '"'.$document['_id'].'"';
								   echo "<td><button class='btn btn-warning' onclick='getContent(".$idLoc.");'  >View</button></td>";
								   if(in_array('contentedit',$_SESSION['FF_SESS_SCREEN_ARR']))
									{
										echo "<td><a class='btn btn-success' href='contentedit.php?id=".$document['_id']."'>Edit</a></td>";
										
									}
									$id= "'".$document['_id']."'";
									if($document['Status'] == 'Active'){
									if(array_key_exists('isNotificationSent',$document)  ){
										if($document['isNotificationSent'] == 1)
										echo '<td id="td'.$document["_id"].'"><button id="'.$document["_id"].'" class="btn btn-danger" onclick="pushnotification('.$id.')" >Resend</button></td>';
									else 
										echo '<td id="td'.$document["_id"].'"><button id="'.$document["_id"].'" class="btn btn-warning" onclick="pushnotification('.$id.')" >Send</button></td>';
												
									}
									else
										echo '<td id="td'.$document["_id"].'"><button id="'.$document["_id"].'" class="btn btn-warning" onclick="pushnotification('.$id.')" >Send</button></td>';
									
									}
									else
										echo '<td id="td'.$document["_id"].'" >Not Published</td>';
									 echo "</tr>";
								   $i++;
							}
				       
				   ?>
              
              
               
              </tbody>
            </table>
          </div>
        </div>
        <input type="hidden" id="sessUserId" name="sessUserId" value="<?php echo $_SESSION['FF_SESS_USER_NAME'];?>"/>
      </div>
    </div>
  </div>
</div>
<div class="modal fade"  id="detailModal" role="dialog" style="display:none">
	<div class="modal-dialog">
	  <div class="modal-content">
	  <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Details</h4>
			<input type="hidden" id="selectedContentId" name="selectedContentId">
       </div>
	   <div class="modal-body" id="detailContent">
	   
	   </div>
	   <div class="modal-footer">
	   <button type="button" class="btn btn-danger"  data-dismiss="modal" >Close</button>
	   </div>
	 </div>
	 </div>  
</div>



<div class="modal fade"  id="progressmodel" role="dialog" style="display:none">
	<div class="modal-dialog">
	  <div class="modal-content">
	  <div class="modal-header">
			<h4 class="modal-title">Notification Sending ....</h4>
       </div>
	   <div class="modal-body" id="userDetailContent">
	   <div class="progress progress-striped active">
			<div class="bar" style="width: 75%;"></div>
		</div>
	   </div>
	   
	 </div>
	 </div>  
</div>

<div class="modal fade"  id="userDetailModal" role="dialog" style="display:none">
	<div class="modal-dialog">
	  <div class="modal-content">
	  <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Details</h4>
       </div>
	   <div class="modal-body" id="userDetailContent">
	   
	   </div>
	   <div class="modal-footer">
	   <button type="button" class="btn btn-warning"  data-dismiss="modal" >Close</button>
	   </div>
	 </div>
	 </div>  
</div>


<div id="myAlert" class="modal fade" style="display:none">  
<h4 class="modal-title"> 	&nbsp; Progressing ...</h4>
<div class="modal-body  progress progress-striped active">
	
		<div class="bar" style="width: 100%;"></div>
</div>
</div>

</div>





<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/maruti.js"></script> 
<script src="../js/maruti.tables.js"></script>
<script src="../js/bootstrap-colorpicker.js"></script> 
<script src="../js/bootstrap-datepicker.js"></script> 
<script src="../js/maruti.form_common.js"></script> 
<script src="../js/jquery.dataTables10.min.js"></script>
<script src="../js/dataTables.buttons.min.js"></script>
<script src="../js/buttons.flash.min.js"></script>
<script src="../js/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../js/vfs_fonts.js"></script>
<script src="../js/buttons.html5.min.js"></script>
<script src="../js/buttons.print.min.js"></script>










<script >
$(document).ready(function() {

    $('#datacontent').DataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		//"sDom": '<""l>t<"F"fp>'
		"sDom": '<"H"lB>t<"F"fp>',
		
        buttons: [
           'csv', 'excel',  'print' ,'pdf'
        ]
    } );
} );

//datacontent

function moveToTop(){
	var id =document.getElementById('selectedContentId').value;
	var sessId=$('#sessUserId').val();
	$.ajax({
		type: "POST",
		url: "../model/common/getdata.php",
		data:'moveToTopId='+id+'&sessUserId='+sessId,
		success: function(data){
			//alert(data)
			console.log(data);
				$("#detailModal").modal('hide');
			},
		error: function() {
				alert ("Error");
					 //$("#userDetailContent").html("");
					 $("#detailModal").modal('hide');
					},
		});
}

function publish(status)
{
	var id =document.getElementById('selectedContentId').value;
	var ids = "'"+id+"'";
	var sessId=$('#sessUserId').val();
	if(status == "Inactive")
		$("#td"+id).html("Not Published");
	else
		$("#td"+id).html('<button id="'+id+'" class="btn btn-warning" onclick="pushnotification('+ids+')">Send</button>');
	$.ajax({
		type: "POST",
		url: "../model/common/getdata.php",
		data:'publishId='+id+'&status='+status+'&sessUserId='+sessId,
		success: function(data){
			//alert(data)
			console.log(data);
				$("#detailModal").modal('hide');
			},
		error: function() {
				alert ("Error");
					 //$("#userDetailContent").html("");
					 $("#detailModal").modal('hide');
					},
		});
}
function pushnotification(id){
	$('#progressmodel').modal('show');
 var sessId=$('#sessUserId').val();
 
	$.ajax({
		type: "POST",
		url: "../model/notification/contentnotification.php",
		data:'contentId='+id+'&sessUserId='+sessId,
		success: function(data){
			console.log(data);
			  //alert(data);
			  document.getElementById(id).className="btn btn-danger";
				//$("#"+id).className="btn btn-danger";
				$("#"+id).html("Resend");
				$('#progressmodel').modal('hide');
				//$("#userDetailContent").html(data);
				//$("#userDetailModal").modal('show');
			},
		error: function() {
				//alert (data);
					 //$("#userDetailContent").html("");
					},
		});
}
function getUsers(status,id)
{
	
	$.ajax({
		type: "POST",
		url: "../model/content/getdetail.php",
		data:'contentId='+id+'&status='+status,
		success: function(data){
				$("#userDetailContent").empty();
				$("#userDetailContent").html(data);
				$("#userDetailModal").modal('show');
			},
		error: function() {
				//alert (data);
					 $("#userDetailContent").html("");
					},
		});
}
function getContent(id)
{
	document.getElementById('selectedContentId').value =id;
	$.ajax({
		type: "POST",
		url: "../model/content/getdetail.php",
		data:'contentId='+id,
		success: function(data){
				$("#detailContent").empty();
				$("#detailContent").html(data);
				$("#detailModal").modal('show');
			},
		error: function() {
				//alert (data);
					 $("#detailContent").html("");
					 document.getElementById('selectedContentId').value=""
					},
		});
}
function valid()
{
	var result = true;
	document.getElementById("errFromDate").innerHTML= "";
	document.getElementById("errToDate").innerHTML= "";
	var fromDate = document.getElementById("fromDate").value;
	var toDate = document.getElementById("toDate").value;
	if(fromDate =="")
	{
		document.getElementById("errFromDate").innerHTML= "<font style='color:red'>Choose Date</font>";
		result = false;
	}
	else
		fromDate = new Date(fromDate);
	if(toDate == "")
	{
		document.getElementById("errToDate").innerHTML= "<font style='color:red'>Choose Date</font>";
		result = false;
	}
	else
	  toDate = new Date(toDate);
	var val = toDate-fromDate;
	if(val<0 && result === true)
	{
			
			document.getElementById("errToDate").innerHTML= "<font style='color:red'>To Date Must be Greater</font>";
			result =false;
	}
	if(result == true)
			$('#myAlert').modal('show');
		
	return result;
}
function languageOnChange()
{
	var val = document.getElementById("language").value;
	//alert(val);
	if(val!="All")
	{
	
		$.ajax({
		type: "POST",
		url: "../model/common/getdata.php",
		data:'commonLanguage='+val,
		success: function(data){
				//alert (data);
				$("#category").html(data);
			},
		error: function() {
				//alert (data);
					 $("#category").html("");
					},
		});
	}
	else
	{
				$("#category").html("<option value='All'  selected='selected'>--All--</option>");
	}
	return false;
		
}



function categoryOnChange()
{
	
	var val = document.getElementById("category").value;
	//alert(val);
	if(val!="All")
	{
		$.ajax({
		type: "POST",
		url: "../model/common/getdata.php",
		data:'commonCategory='+val,
		success: function(data){
			//alert(val);
			//alert(data);
				$("#subcategory").empty();
				$("#subcategory").html(data);
			},
		error: function() {
				//alert (data);
					 $("#subcategory").html("");
					},
		});
	}
	else
	{
		$("#subcategory").html("<option value='All'  selected='selected'>--All--</option>");
	}
	return false;

}
</script>

<?php include 'footer.php';?>
</body>
</html>
