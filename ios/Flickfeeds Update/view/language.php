<?php require_once('../model/common/auth.php');
$_SESSION['SESS_SCREEN_NAME'] = 'cmsusersearch'; ?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
<?php include '../model/language/language_exec.php';?>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="home.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Language</a> </div>
    
  </div>
  
  <div class="container-fluid">
    
	<div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
             <span class="icon"><i class="icon-th"></i></span> 
            <h5>Language</h5>
          </div>
		  
		<div class="widget-content nopadding">
		  <form action="" method="post" name="languageform" >
		  <table class="table table-bordered">
		  <tr>
		  <td>
		  <div class="control-group">
                <label class="control-label">Language</label>
                <div class="controls">
				<select class="span6" id="language" name="language">
						<option value="All" <?php if($language == "All"){echo "selected='selected'";} ?>>All</option>
						<?php foreach($languageList as $document) {
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
			</td>
			<td>
				<div class="control-group">
					<label class="control-label">Status</label>
					<div class="controls">
					  <select class="span6" id="status" name="status">
							<option value= "All" 		<?php if($status == "All"){echo "selected='selected'";} ?>>All</option>
							<option value = "Active" 	<?php if($status == "Active"){echo "selected='selected'";} ?>  >Active </option>
							<option value = "Inactive"  <?php if($status == "Inactive"){echo "selected='selected'";} ?> >Inactive</option>
					 </select>
				  </div>
				</div>
		  </td>
		  <td>
		  <div class="control-group">
                <label class="control-label">&nbsp;</label>
                <div class="controls">
				 <button type="submit" class="btn btn-success" onClick="$('#myAlert').modal('show');">Search</button>
				<?php
				  if(in_array('languageadd',$_SESSION['FF_SESS_SCREEN_ARR']))
				  {
					 echo "<a href='languageadd.php' type='submit' class='btn btn-warning' onClick='return validate();'>Add New</a>";
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
            <h5>Language</h5>
			<h5>Records found : <?php echo $result->count();?></h5>
          </div>
		<div class="widget-content nopadding">
          <div class="widget-content nopadding">
            <table class="table table-bordered" id="datacontent">
              <thead>
                <tr>
				  <th>SL.No.</th>
                  <th>Language</th>
					<th>Regional Language</th>
				  <th>Description</th>
                  <th>CreatedDate</th>
				  <th>SuspendedDate</th>
				  <th>Status</th>
				  <th>Created/Edited By</th>
				  <?php
				  if(in_array('languageedit',$_SESSION['FF_SESS_SCREEN_ARR']))
				  {
					  echo '<th>Edit</th>';
				  }
				  ?>
				  
                </tr>
				</thead>
				 <tbody>
				   <?php 
				        $i=1;
						foreach($result as $document) {
							echo "<tr>";
							   echo "<td>".$i."</td>";
								 echo "<td>".$document['language']."</td>";
								 echo "<td>".$document['regionallanguage']."</td>";
							   echo "<td>".$document['description']."</td>";
							   echo "<td>".date('Y-m-d', $document['createddate']->sec)."</td>";
							   if(!empty($document['suspendeddate']))
											echo "<td>".date('Y-m-d', $document['suspendeddate']->sec)."</td>";
								else 
										echo "<td>Language Live</td>";
							   echo "<td>".$document['status']."</td>";
							   echo "<td>".$document['createdusername']."</td>";
							   if(in_array('languageedit',$_SESSION['FF_SESS_SCREEN_ARR']))
								{
									echo "<td><a class='btn btn-warning' href='languageedit.php?id=".$document['_id']."'  >Edit</a></td>";
								}
							   echo "</tr>";
							   $i++;
				   }
				   
				   ?>
              
              
               
              </tbody>
            </table>
          </div>
        </div>
        
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
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/maruti.js"></script> 
<script src="../js/maruti.tables.js"></script>
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
            'csv', 'excel',  'print', 'pdf'
        ]
    } );
} );

/*user Name Availability */

function validate()
{
	var result = true;
	if(document.getElementById("languageVal").value == 'false')
	{
		document.getElementById("errLangName").innerHTML = "<font style='color:Red'>Choose Valid Language Name</font>";
		result = false;
	}
	return result ;
}
function checkLanguageName()
{
	var val = document.getElementById("language").value;
	$.ajax({
	type: "POST",
	url: "../model/language/getlanguagename.php",
	data:'language='+val,
	success: function(data){
			$("#errLangName").html(data);
		},
	error: function() {
				 $("#errLangName").html("<font style='color:Red'>UserName Already exsist</font>");
                },
	});
}
/*End user Name Availability */	

function showmodel()
{
	$('#myModal').modal("show");
}
</script>
<?php include 'footer.php';?>
</body>
</html>
