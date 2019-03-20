<?php require_once('../model/common/auth.php');
$_SESSION['SESS_SCREEN_NAME'] = 'cmsusersearch'; ?>
<?php include 'header.php';?>
<?php include 'menu.php';?>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
		<a href="home.php" title="Go to Home" class="tip-bottom">
		<i class="icon-home"></i> Home</a>
		<a href="content.php">Content</a> 
		<a href="#" class="current">Add</a> 
		<?php include '../model/content/contentupload_exec.php';?>
	</div>
  </div>
  <div class="container-fluid">
	
		<form name="contentUpload" id ="contentUpload" action="" method="post" class="form-horizontal" enctype="multipart/form-data">
		<div class="row-fluid">
					<div class="span6">
					<div class="widget-box">
					  <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
						<h5>Content Upload</h5>
					  </div>
					  <div class="widget-content nopadding ">
					  <span class="help-block" id="message"><font style='color:Green'><?php echo $message?></font></span>
						  <div class="control-group">
							<label class="control-label">Language</label>
							<div class="controls">
							<select class="span11" id="language" name="language" onchange="languageOnChange();">
									<option ></option>
									<?php foreach($languageList as $document) {
											 echo "<option value='".$document['_id']."'>" .$document['language']. " </option>";
									}?>
							  </select>
							  <span class="help-block" id="errLanguage"></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Regional Language</label>
							<div class="controls">
							<select class="span11" id="isRegional" name="isRegional">
							  </select>
							  <span class="help-block" id="errLanguage"></span>
							</div>
						</div>
						 <div class="control-group">
							<label class="control-label">Category</label>
							<div class="controls">
							<select class="span11" id="category" name="category" onchange="return categoryOnChange();">
									
							  </select>
							  <span class="help-block" id="errCategory"></span>
							  <span class="help-block" id="errSubCategoryNot"></span>
							</div>
						</div>
						
						
						<input type="hidden" id="isSubcat" name="isSubcat" />
						<input type="hidden" id="isSubCatNotCreated" name="isSubCatNotCreated" />
						
						<div class="control-group" id="subCategoryDiv" style="display:none">
							<label class="control-label">Sub Category</label>
								<div class="controls">
								<select class="span11" id="subcategory" name="subcategory" onchange="return subcategoryOnChange();">
								
								</select>
								 <span class="help-block" id="errSubCategory"></span>
								</div>
						</div>
						<div id="inputData" style="display:none">
						
						</div>
						<div class="control-group" id="submitdiv" style="display:none">
						
						  <div class="controls">
							  <button class="btn btn-success" onclick="return valid();"> Save </button>
						  </div>
						</div>
						
						
						
					</div>
				  </div>
				 </div>
	      
       

	   <div id="hiddenValues" class="span6">

					
		</div>
		
		<input  type="hidden"  id="batchId" name="batchId"  value="NA"/>
		<input  type="hidden"  id="viewType" name="viewType"  value="NA"/>
		<input  type="hidden"  id="validateParameters" name="validateParameters"  value="NA"/>
		<input  type="hidden"  id="mandParameters" name="mandParameters"  value="NA"/>
		<input  type="hidden"  id="remParameters" name="remParameters"  value="NA"/>
		<input  type="hidden"  id="isDuplicateCheck" name="isDuplicateCheck"  value="NA"/>
	
<div id="myAlert" class="modal fade" style="display:none">  
<h4 class="modal-title"> 	&nbsp; Progressing ...</h4>
<div class="modal-body  progress progress-striped active">
	
		<div class="bar" style="width: 100%;"></div>
</div>
</div>
	</div>	
 </form>
		
  
</div>
</div>

<div id="detailModal" class="modal" role="dialog" style="display:none">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Add New KeyWord</h4>
      		</div>
      		<div class="modal-body" id="detailContent">
        		
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        		
						  <div class="col-md-12" id="image_demo" style="width:350px; margin-top:30px"></div>
						  <input type="hidden" id="cropid" name="cropid" />
				
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
<script src="../model/content/js/load.js"></script>
<script src='../js/croppie.js'></script>




<?php include 'footer.php';?>

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
				value +="~"+src;
				$('#base64ValueArrayOf'+cropid).val(value); //store array	
			}
			else{
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


