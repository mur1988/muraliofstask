<?php require_once('../model/common/auth.php');
$_SESSION['FF_SESS_SCREEN_NAME'] = 'cmsuseredit'; ?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
<?php include '../model/language/language_edit_exec.php';?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
	<a href="home.php" title="Go to Home" class="tip-bottom">
	<i class="icon-home"></i> Home</a>
	<a href="language.php" >Language</a> 
	<a href="#" class="current">Edit</a> </div>
    
  </div>
   <div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Language </h5>
			
          </div>
          <div class="widget-content nopadding">
		  
		  <form name="cmsuseradd" class="form-horizontal" method="post" action=""  >
		  <input type="hidden" name="languageVal" id="languageVal"/>
		  <input type="hidden" name="languageId" id="languageId" value='<?php echo $languageId;?>'/>
		  
			<span class="help-block" id="message"><font style='color:Green'><?php echo $message?></font></span>
			<div class="control-group">
                <label class="control-label">Language Name :</label>
                <div class="controls">
                  <input type="text" class="span11" id="language" name="language" placeholder="Language Name" required="" value='<?php echo $resultLanguage;?>' onblur="checkLanguageName();"/>
				  <span class="help-block" id="errLangName"></span>
                </div>

              </div>
							<div class="control-group">
                <label class="control-label">Regional Language :</label>
                <div class="controls">
                  <input type="text" class="span11" id="regionallanguage" name="regionallanguage" placeholder="Regional Language" required="" value='<?php echo $resultRegionalLanguage;?>' />
				  <span class="help-block" id="errLangName"></span>
                </div>

              </div>
			  <div class="control-group">
                <label class="control-label">Descriptions</label>
                <div class="controls">
					<textarea id="description" name="description" class="span11" placeholder="Enter Description"   required=""><?php echo $resultDescription;?> </textarea>
                </div>
              </div>
			<div class="control-group">
                <label class="control-label">Status</label>
                <div class="controls">
                  <select class="span6" id="status" name="status">
						<option value = "Active" 	<?php if($resultStatus == "Active"){echo "selected='selected'";} ?>  >Active </option>
						<option value = "Inactive"  <?php if($resultStatus == "Inactive"){echo "selected='selected'";} ?> >Inactive</option>
                  </select>
				 </div>
			</div>
              <div class="form-actions">
                <button id="submit" type="submit" class="btn btn-success" onClick="return validate();">Save</button>
				<a href="language.php" type="button" class="btn btn-warning" >Back</a>
              </div>
            </form>
          </div>
        </div>
      </div>
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
<script >


/*Submit validation*/
function validate()
{
	var result =true;
	
	if(document.getElementById("languageVal").value == '')
		checkLanguageName();
	if(document.getElementById("languageVal").value == 'false')
	{		
		document.getElementById("errLangName").innerHTML ="<font style='color:Red'>User Name Not Available</font>";
		result =false;
	}	
	return result ;
}
/*End Submit validation*/

/*user Name Availability */
function checkLanguageName()
{
	var name = document.getElementById("language").value;
	var languageId = document.getElementById("languageId").value;
	
	var val = "languageId="+ languageId + "&language=" + name;
	
	$.ajax({
	type: "POST",
	url: "../model/language/getlanguagename.php",
	data:val,
	success: function(data){
			$("#errLangName").html(data);
		},
	error: function() {
				 $("#errLangName").html("<font style='color:Red'>UserName Already exsist</font>");
                },
	});
}
/*End user Name Availability */	










</script>
<?php include 'footer.php';?>
</body>
</html>
