<?php require_once('../model/common/auth.php');
$_SESSION['SESS_SCREEN_NAME'] = 'contentedit'; ?>
<?php include 'header.php';?>
<?php include 'menu.php';?>


<div id="content">
   <div id="content-header">
      <div id="breadcrumb"> 
         <a href="home.php" title="Go to Home" class="tip-bottom">
		 <i class="icon-home"></i> Home</a> 
		 <a href="content.php">Content</a> 
		 <a href="#" class="current">Edit</a> 
         <?php include '../model/content/content_edit_exec.php';?>
      </div>
   </div>
   <div class="container-fluid">
      <form name="contentEdit" id ="contentEdit" action="" method="post" class="form-horizontal" enctype="multipart/form-data">
         <div class="row-fluid">
            <div class="span6">
               <div class="widget-box">
                  <div class="widget-title">
                     <span class="icon"> <i class="icon-align-justify"></i> </span>
                     <h5>Content Edit</h5>
                  </div>
                  <div class="widget-content nopadding" >
                     <span class="help-block" id="message" ><font style='color:Green'><?php echo $message?></font></span>
                     <div class="control-group">
                        <label class="control-label">Language</label>
                        <div class="controls">
                           <select class="span11" id="languageName" name="languageName" disabled>
                           <?php echo "<option value='".$languageId."' selected='selected'>" .$resultlanguage. " </option>";?>
                           </select>
                        </div>
                     </div>
							<div class="control-group">
                        <label class="control-label">Regional Language</label>
                        <div class="controls">
                           <select class="span11" id="isRegional" name="isRegional">
                           <option value="N" <?php if($resultRegionallanguage =="N") echo "selected"; ?> >English</option>
									<option value="Y" <?php if($resultRegionallanguage =="Y") echo "selected"; ?> ><?php echo $resultRL; ?></option>
                           </select>
                        </div>
                     </div>
                     <div class="control-group">
                        <label class="control-label">Category</label>
                        <div class="controls">
                           <select class="span11" id="categoryName" name="categoryName" disabled>
                           <?php echo "<option value='".$categoryId."' selected='selected'>" .$resultCategory. " </option>";?>	
                           </select>
                        </div>
                     </div>
                     <input  type="hidden"  id="category" name="category"  value="<?php echo $categoryId;?>"/>
                     <input  type="hidden"  id="language" name="language"  value="<?php echo $languageId;?>"/>
                     <input  type="hidden"  id="batchId" name="batchId"  value="NA"/>
                     <input  type="hidden"  id="viewType" name="viewType"  value="NA"/>
                     <input  type="hidden"  id="validateParameters" name="validateParameters"  value="NA"/>
                     <input  type="hidden"  id="mandParameters" name="mandParameters"  value="NA"/>
                     <input  type="hidden"  id="remParameters" name="remParameters"  value="NA"/>
                     <input  type="hidden"  id="isDuplicateCheck" name="isDuplicateCheck"  value="NA"/>
                     <input type="hidden" id="contentId" name="contentId" value="<?php echo $contentId; ?>" />
                     <input type="hidden" id="isSubcat" name="isSubcat" value="NA"/>
                     <input  type="hidden"  id="subcategory" name="subcategory"  value="<?php echo $subCategoryId;?>"/>
                     <?php if($resultSubCategroy  != "" ) {?>
                     <div class="control-group" id="subCategoryDiv" >
                        <label class="control-label">Sub Category</label>
                        <div class="controls">
                           <select class="span11" id="subcategoryName" name="subcategoryName" disabled>
                           <?php echo "<option value='".$subCategoryId."' selected='selected'>" .$resultSubCategroy. " </option>";?>	
                           </select>
                           <span class="help-block" id="errSubCategory"></span>
                        </div>
                     </div>
                     <?php } else {?>
                     <input type="hidden" id="isSubcat" name="isSubcat" value="false"/>
                     <?php } ?>
                     <div id="inputData">
					 
                     </div>
                     <div class="control-group" id="submitdiv" style="display:none">
                        <div class="controls">
                           <button class="btn btn-success" onclick="return valid();"> Update </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div id="hiddenValues" class="span6">
			
            </div>
			<div id="myAlert" class="modal fade" style="display:none">  
			<h4 class="modal-title"> 	&nbsp; Progressing ...</h4>
				<div class="modal-body  progress progress-striped active">
						<div class="bar" style="width: 100%;"></div>
				</div>
			</div>
         </div>
      </form>
	</div>


	<div class="modal fade"  id="detailModal" role="dialog" style="display:none">
		<div class="modal-dialog">
		  <div class="modal-content">
		  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add</h4>
		   </div>
		   <div class="modal-body" id="detailContent">
		   
		   </div>
		   <div class="modal-footer">
		   <button type="button" class="btn btn-warning"  data-dismiss="modal" >Close</button>
		   </div>
		 </div>
		 </div>  
	</div>


</div>
  
 <div id="uploadimageModal" class="modal" role="dialog" style="display:none">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Upload & Crop Image</h4>
      		</div>
      		<div class="modal-body">
        		<div class="row">
  					<div class="col-md-8 text-center">
						  <div id="image_demo" style="width:350px; margin-top:30px"></div>
						  <input type="hidden" id="cropid" name="cropid" />
  					</div>
				</div>
      		</div>
      		<div class="modal-footer">
				<button class="btn btn-success crop_image" onclick="crop()">Crop & Upload Image</button>
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
    </div>
</div> 





<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/maruti.js"></script> 
<script src="../js/maruti.tables.js"></script>
<script src="../model/content/js/editload.js"></script>
<script src='../js/croppie.js'></script>
<?php include 'footer.php';?>

</body>

<script>

$(document).ready(function(){
	
$image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:300,
      height:300,
      type:'square' //circle
    },
    boundary:{
      width:325,
      height:325
    }
  });
  
  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
		$('#uploadimageModal').modal('hide');
         $('#uploaded_image').html('<img src="'+response+'" class="img-thumbnail" />');
    })
  });
  
});

function crop()
{
	$image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
		var cropid =$('#cropid').val();
        $('#'+cropid+'preview').attr('src',response);
		//$('#base64ValueOf'+$('#cropid').val()).val(response);
		if(document.getElementById('base64ValueOf'+cropid) != null)
			document.getElementById('base64ValueOf'+cropid).value = response;
		else if(document.getElementById('base64ValueArrayOf'+cropid) != null)
		{
			var src = document.getElementById(cropid+'preview').src;
			var elem = document.createElement("img");
			elem.setAttribute("id", src);
			elem.setAttribute("src", src);
			elem.setAttribute("height", "45");
			elem.setAttribute("width", "45");
			document.getElementById(cropid+'divpreview').appendChild(elem);
			var btn = document.createElement("BUTTON");
			btn.setAttribute("id", "butt"+src);
			btn.setAttribute("class", "badge badge-warning");
			btn.setAttribute("onclick", 'removeElement("'+src+'","'+cropid+'")' );
			var t = document.createTextNode("x");   
			btn.appendChild(t); 
			document.getElementById(cropid+'divpreview').appendChild(btn);
			document.getElementById(cropid+'preview').src="";
			document.getElementById(cropid).value="";
			var value = $('#base64ValueArrayOf'+cropid).val(); //retrieve array
			if(value != ""){
				value +='~'+src;
				$('#base64ValueArrayOf'+cropid).val(value); //store array	
			}
			else{
				//alert("test");
				$('#base64ValueArrayOf'+cropid).val(src); //store array
			}
		}
		
		$('#uploadimageModal').modal('hide');
    })
}

function loadCrop(id)
{
	$('#cropid').val(id);
	$('#'+id).on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
	for(var i=0;i<this.files.length;i++){
		reader.readAsDataURL(this.files[i]);
	}
	
    //reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });
}
</script>

<script>


$(document).ready(function(){
		loadajax();
});
function loadajax()
{

		var isSubcat = "false"
		var form_data = new FormData();
		form_data.append("language",document.getElementById("language").value);
		form_data.append("category",document.getElementById("category").value);
		form_data.append("contentId",document.getElementById("contentId").value)
		form_data.append("batchId",document.getElementById("batchId").value)
			if(document.getElementById("subcategory").value!="")
			{
				form_data.append('subcategory', document.getElementById("subcategory").value);
				isSubcat = "true";
				document.getElementById("isSubcat").value=isSubcat;
			}
		form_data.append("isSubcat",document.getElementById("isSubcat").value);
		//alert("suc");
		$.ajax({
				url: "../model/content/editinputs.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
			    success: function(data){
					console.log(data);
					//alert(data);
					$("#inputData").html(data);					
					document.getElementById("inputData").style.display = 'block';
					document.getElementById("submitdiv").style.display = 'block';
					
					loadListValues();
				},
			error: function() {
				//to-do
				alert("fails");
						},
			});
		
		
	
}



function loadListValues()
{
		var form_data = new FormData();
		form_data.append("editBatchId",document.getElementById("batchId").value);
		$.ajax({
					url: "../model/content/contentadd_ajax_exec.php",
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'POST',
					success: function(data){
						console.log(data);
					//	alert(data);
						$("#hiddenValues").html(data);					
					},
				error: function() {
					//to-do
					alert("fails");
							},
				});
	
}


</script>



</html>
