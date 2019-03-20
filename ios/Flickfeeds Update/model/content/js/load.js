/*****language  Dropdown on Chaging this function called *****/
function languageOnChange()
{
	var val = document.getElementById("language").value;
	//alert(val);
	$.ajax({
	type: "POST",
	url: "../model/common/getdata.php",
	data:'language='+val,
	success: function(data){
			//alert (data);
			$("#category").html(data);
			$("#inputData").html("");
			//document.getElementById("inputData").style.display = 'block';
			document.getElementById("submitdiv").style.display = 'none';
			$.ajax({
				type: "POST",
				url: "../model/common/getdata.php",
				data:'regionallanguage='+val,
				success: function(data){
						//alert (data);
						$("#isRegional").html(data);
						$("#isRegional").select2();
					},
				error: function() {
						//alert (data);
							 $("#category").html("<font style='color:Red'>UserName Already exsist</font>");
							},
				});

			
		},
	error: function() {
			//alert (data);
				 $("#category").html("<font style='color:Red'>Server Err.</font>");
                },
	});
	
	return false;
		
}
/*****End language  Dropdown on Chaging  *****/



/*****Category  Dropdown on Chaging this function called *****/
function categoryOnChange()
{
	document.getElementById("errSubCategoryNot").innerHTML = "";
	var val = document.getElementById("category").value;
	document.getElementById("subCategoryDiv").style.display="none";
	document.getElementById("inputData").style.display="none";
	document.getElementById("submitdiv").style.display="none";
	//alert(val);
	if(val!="NA" && val!="" && val!="All")
	{
		$.ajax({
		type: "POST",
		url: "../model/common/getdata.php",
		data:'category='+val,
		success: function(data){
			 //alert (data);
				$("#subcategory").html(data);
				$("#hiddenValues").html("");
				document.getElementById("batchId").value = "NA";
				if(document.getElementById("isSubcat").value == 'true')
				{
					
					if(document.getElementById("isSubCatNotCreated").value == 'true')
					{
						document.getElementById("errSubCategoryNot").innerHTML = "<font style='color:red'> Please Create SubCategory for this Category</font>";
						
					}
					else
					{
						document.getElementById("subCategoryDiv").style.display="block";
						document.getElementById("submitdiv").style.display = "none";
					}
				}
				else
				{
					document.getElementById("subCategoryDiv").style.display="none";
					return loadinputs();
				}
			},
		error: function() {
				//alert (data);
					 $("#subcategory").html("<font style='color:Red'>UserName Already exsist</font>");
					},
		});
	}
	else
	{
		$("#hiddenValues").html("");
		$("#inputData").html("");
		document.getElementById("inputData").style.display = 'none';
		document.getElementById("submitdiv").style.display = 'none';
		document.getElementById("batchId").value = "NA";
	}
	return false;

}

function UpdateBalance(id,elem,col,batch)
{
	$('#myAlert').modal("show");
	var form_data = new FormData();
	form_data.append('UpdateBalanceId',id);
	form_data.append('UpdateCol',col);
	form_data.append('UpdateBid',batch);
	if(document.getElementById(elem).type == "file")
		form_data.append('UpdateData',document.getElementById("base64ValueOf"+elem).value);
	else
		form_data.append('UpdateData',document.getElementById(elem).value);
		
	$.ajax({
				url: "../model/content/contentadd_ajax_exec.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
			    success: function(data){
					//console.log(data);
					$("#hiddenValues").html(data);
					$('#myAlert').modal("hide");
					//for(i=0;i<valres.length;i++)
						//document.getElementById(valres[i]).value ="";				
					
				},
			error: function() {
				//to-do
				alert("fails");
						},
			});
	
	
}

/*****End Category  Dropdown on Chaging *****/

/*****SubCategory  Dropdown on Chaging this function called *****/
function subcategoryOnChange()
{
	
	var val = document.getElementById("subcategory").value;
	//alert(val);
	var language = document.getElementById("language").value;
	var category = document.getElementById("category").value;
	var isSubcat = document.getElementById("isSubcat").value;
	var subcategory = document.getElementById("subcategory").value;
	var form_data = new FormData();   
	form_data.append('language', language);
	form_data.append('category', category);
    form_data.append('isSubcat', isSubcat);
	form_data.append('subcategory', subcategory);
	$.ajax({
				url: "../model/content/loadinputs.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
				success: function(data){
				//	alert(data);
						$("#hiddenValues").html("");
						$("#inputData").html(data);
						document.getElementById("inputData").style.display = 'block';
						document.getElementById("submitdiv").style.display = 'block';
						document.getElementById("batchId").value = "NA";
					},
				error: function() {
							},
				});
	return false;

}

/*****End SubCategory  Dropdown on Chaging *****/


/*****Load Inputs  Button Click *****/
function loadinputs ()
{
	
	document.getElementById("errLanguage").innerHTML = "";
	document.getElementById("errCategory").innerHTML = "";	
	var language = document.getElementById("language").value;
	var category = document.getElementById("category").value;
	var isSubcat = document.getElementById("isSubcat").value;
	var subcategory = document.getElementById("subcategory").value;
	if(isSubcat=='true')
		document.getElementById("errSubCategory").innerHTML = "";
	if(language=="") 	
		document.getElementById("errLanguage").innerHTML = "<font style='color:red'>Choose Language</font>";
	if(category =="")	
		document.getElementById("errCategory").innerHTML = "<font style='color:red'>Choose Category</font>";
	
	if(isSubcat=='true' && subcategory=="")		
		document.getElementById("errSubCategory").innerHTML = "<font style='color:red'>Choose SubCategory</font>";
	else
	{
		var form_data = new FormData();   
		form_data.append('language', language);
		form_data.append('category', category);
		form_data.append('isSubcat', isSubcat);
		
		if(isSubcat=='true')
			form_data.append('subcategory', subcategory);
		
		$.ajax({
				url: "../model/content/loadinputs.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
				success: function(data){
				//	alert(data);
						$("#inputData").html(data);
						document.getElementById("inputData").style.display = 'block';
						document.getElementById("submitdiv").style.display = 'block';
							
					},
				error: function() {
					
							},
				});
		
		
	}

	return false;
}

/*****End Load Inputs  Button Click *****/
function CheckHieghtWidth(img,width,height){
	 var imgData= document.getElementById(img);
	 var file = imgData.files[0];
			imgs = new Image();
			imgs.src = URL.createObjectURL(file);
			imgs.onload = function() {
			imgwidth = this.width;
			imgheight = this.height;
			
			if(imgwidth==width && imgheight==height){
				if (imgData.files && imgData.files[0]) {
				 var readerObj = new FileReader();
				 readerObj.onload = function (element) {
					$('#'+img+'preview').attr('src', element.target.result);
							}
								readerObj.readAsDataURL(imgData.files[0]);
							}
			 return true;
			}else{
				alert("Image dimension not Support");
				$('#'+img).val('');
				$('#'+img+'preview').attr('src', '');
				$('input[type=file]').uniform();
				//$('select').select2();
				return false;
			}
	}
	
	 
}

function AddImagesSiz(key)
{
	document.getElementById("err"+key).innerHTML='';
	var  normal = document.getElementById(key).value;
	var  inside =document.getElementById(key+'_inside').value ;
	var  front = document.getElementById(key+'_front').value ;
	if(!(normal =="" || inside  == ""  || front == "")){
		var normalSrc = document.getElementById(key+'preview').src;
		var insideSrc = document.getElementById(key+'_insidepreview').src;
		var frontSrc = document.getElementById(key+'_frontpreview').src;
		var elem = document.createElement("img");			
		elem.setAttribute("id", normalSrc+'~'+insideSrc+'~'+frontSrc);
		elem.setAttribute("src", normalSrc);
		elem.setAttribute("height", "45");
		elem.setAttribute("width", "45");
		document.getElementById(key+'divpreview').appendChild(elem);
		var btn = document.createElement("BUTTON");
		btn.setAttribute("id", "butt"+normalSrc+'~'+insideSrc+'~'+frontSrc);
		btn.setAttribute("class", "badge badge-warning");
		btn.setAttribute("onclick", 'removeElementMulti("'+normalSrc+'~'+insideSrc+'~'+frontSrc+'","'+key+'")' );
		var t = document.createTextNode("x");   
		btn.appendChild(t); 
		document.getElementById(key+'divpreview').appendChild(btn);
		document.getElementById(key+'preview').src ="";
		document.getElementById(key+'_insidepreview').src ="";
		document.getElementById(key+'_frontpreview').src ="";
		document.getElementById(key).value ="";
		document.getElementById(key+'_inside').value ="";
		document.getElementById(key+'_front').value ="";
		$('input[type=file]').uniform();
		var value = $('#'+key+'imgDifSizAdd').val(); //retrieve array
		if(value != ""){
			value +="<<SP=ST>>"+normalSrc+"~"+insideSrc+"~"+frontSrc;	
			$('#'+key+'imgDifSizAdd').val(value);
		}
		else{
			$('#'+key+'imgDifSizAdd').val(normalSrc+"~"+insideSrc+"~"+frontSrc);	
		}
		
		
	}
	else
		document.getElementById("err"+key).innerHTML='<font style="color:red"> Please Select all Images</font>';
		
	
}

function removeElementMulti(id,divid)
{
//		alert('test');
		var d = document.getElementById(divid+'divpreview');
		var d_nested = document.getElementById(id);
		d.removeChild(d_nested);
		var d_button = document.getElementById('butt'+id);
		d.removeChild(d_button);
		var value = $('#'+divid+'imgDifSizAdd').val(); 
		value = value.replace("~"+id,"");
		value = value.replace(id,"");
		$('#'+divid+'imgDifSizAdd').val(value); //store array	
		return false;
}



//** Remove added data  function **// 

function remove(removeId,localBat)
{
	var form_data = new FormData(); 
	form_data.append('removeId' ,removeId);
	var bat = document.getElementById("batchId").value
	var groupVal =document.getElementById(localBat+"value").value;		 
	form_data.append("batchId",bat);
	$('#myAlert').modal("show");
	$.ajax({
			url: "../model/content/contentadd_ajax_exec.php",
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'POST',
		success: function(data){
				$("#hiddenValues").html(data);
				var res = parseInt(groupVal)-1;
				if(res<=0)
					document.getElementById(localBat+"value").value = "";
				else
					document.getElementById(localBat+"value").value = res;
				
				$('#myAlert').modal("hide");	
			},
		error: function() {
			//to-do
			alert("fails");
					},
		});
	
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
	
    return true;
}


function optionsr(vals)
{
	var val = vals['current'];
	//var val = vals['current'];
	var divs = vals['total'].split("~");
	var validate =vals['validparms'];
	for (i=0;i<divs.length;i++)
	{
		var x = document.getElementById(divs[i]+"div");
		
		if(divs[i]==val)
		{
			
			document.getElementById("validateParameters").value +="~"+validate[divs[i]];
			x.style.display = "block";
		}
		else
		{
			var res = document.getElementById("validateParameters").value.replace("~"+validate[divs[i]], "");
			document.getElementById("validateParameters").value = res;
			x.style.display = "none";
		}
	}
}

function optionsc(val,validateValue)
{
	var x =  document.getElementById(val);
	var vp = document.getElementById("validateParameters").value;
	//var rm = document.getElementById("remParameters").value;
	var adpar = document.getElementById(validateValue).value;

	if (x.style.display === "none") {
		vp +="~"+adpar;
		//rm +="~"+adpar;
		document.getElementById("validateParameters").value = vp;
		//document.getElementById("remParameters").value = rm;
        x.style.display = "block";
		//alert(vp);
    } else {
		var res = vp.replace("~"+adpar, "");
		//var rms = rm.replace("~"+adpar, "");
		document.getElementById("validateParameters").value = res;
		//document.getElementById("remParameters").value = rms;
        x.style.display = "none";
    }
}

function valid()
{
	var result = true;
	//alert("test");
	var x = document.getElementById("validateParameters").value;
    var splitstr = x.split("~");
	for (i = 0; i < splitstr.length; i++) {
		if(document.getElementById(splitstr[i]).type == "file" && document.getElementById(splitstr[i]+"imgDifSizAdd") == null )
		{
			
			if(document.getElementById(splitstr[i])!=null && document.getElementById(splitstr[i]+"_front")!=null && document.getElementById(splitstr[i]+"_inside") !=null)
			{
				
				if(document.getElementById(splitstr[i]).value == "" && 
				document.getElementById(splitstr[i]+"_front").value == "" &&
				document.getElementById(splitstr[i]+"_inside").value == ""){
					result = true;
				}
				else if(document.getElementById(splitstr[i]).value != "" && 
				    document.getElementById(splitstr[i]+"_front").value != "" &&
				      document.getElementById(splitstr[i]+"_inside").value != ""){
					result = true;
				}
				else {
					
					document.getElementById("err"+splitstr[i]).innerHTML ="<font style='color:red'>Please Select all Images</font>";
					result = false;
				}
			}
		}
		else if(document.getElementById(splitstr[i]).type == "file" && document.getElementById(splitstr[i]+"imgDifSizAdd") != null)
		{
			if(document.getElementById(splitstr[i]).value != "" || 
			   document.getElementById(splitstr[i]+"_front").value != "" ||
			   document.getElementById(splitstr[i]+"_inside").value != ""){
					result = false;
					document.getElementById("err"+splitstr[i]).innerHTML ="<font style='color:red'>Please Select all Images and Click add</font>";
					
				}
			
		}
	}
	
	
    /*for (i = 0; i < splitstr.length; i++) 
    {
		//alert(splitstr[i]+"="+document.getElementById(splitstr[i]).value);
		if(document.getElementById(splitstr[i]).type == "radio")
		{
			
			document.getElementById("err"+splitstr[i]).innerHTML ="";
			var rad = document.getElementsByName(splitstr[i]);
			var checked = false;
			for(var j = 0; j < rad.length; j++) {
					if(rad[j].checked)
					{
						checked = true;
						break;
					}
				}
			
			if(!checked)
			{
				result = false;
				document.getElementById("err"+splitstr[i]).innerHTML ="<font style='color:red'>choose proper value</font>";
			}

        }
		
		else if(document.getElementById(splitstr[i]).type != "checkbox")
		{
			//alert(document.getElementById(splitstr[i]).type);
			if(document.getElementById("err"+splitstr[i]) !=null){
				document.getElementById("err"+splitstr[i]).innerHTML = "";
				if(document.getElementById(splitstr[i]).type == "file")
				{
					if(document.getElementById("base64ValueArrayOf"+splitstr[i]) !=null)
					{
						if(document.getElementById("base64ValueArrayOf"+splitstr[i]).value == "")
						{
							result = false;
							document.getElementById("err"+splitstr[i]).innerHTML = "<font style='color:red'>Choose valid "+splitstr[i]+"</font>";
						}
					}
					else if(document.getElementById("base64ValueOf"+splitstr[i]) !=null)
					{
						if(document.getElementById("base64ValueOf"+splitstr[i]).value == "")
						{
							result = false;
							document.getElementById("err"+splitstr[i]).innerHTML = "<font style='color:red'>Choose valid "+splitstr[i]+"</font>";
						}
					}
					else
					{
						if(document.getElementById(splitstr[i]).value == "")
						{
							result = false;
							document.getElementById("err"+splitstr[i]).innerHTML = "<font style='color:red'>Choose valid "+splitstr[i]+"</font>";
						}
					}
				}
				else if(document.getElementById(splitstr[i]).value == "")
				{
					result = false;
					document.getElementById("err"+splitstr[i]).innerHTML = "<font style='color:red'>Choose valid "+splitstr[i]+"</font>";
				}
			}
		}
    }*/
	
	if(result) 
		result = ajaxPassing();
	return result;
}

function Add(formname)
{
	var groupval = document.getElementById(formname+"value").value;
	var isFieldsAvl = true;
	var x = document.getElementById(formname);
	var form_data = new FormData(document.forms[formname]);
	var bat = document.getElementById("batchId").value
	form_data.append("batchId",bat);
	var allEmpty = 0;
	var formlength = 0;
	for (i = 0; i < x.length; i++) 
	{
		if(x.elements[i].type != "submit" && x.elements[i].type != "hidden")
		{	formlength ++;
			if(document.getElementById("err"+x.elements[i].name) != null){
				document.getElementById("err"+x.elements[i].name).innerHTML ="";
				
				if(x.elements[i].value == "")
				{
					allEmpty++;
					//x.elements[i].value = "EMPTY";
					//document.getElementById("err"+x.elements[i].name).innerHTML = "<font style='color:red'> Choose "+x.elements[i].name+"</font>";
					//break;
				}
			}
		}
		
		
	}
	
	if(allEmpty == formlength)
			isFieldsAvl = false;
	if(isFieldsAvl == true)
	{
		$('#myAlert').modal("show");
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
					if(groupval=="")
						document.getElementById(formname+"value").value = 1;
					else
						document.getElementById(formname+"value").value = parseInt(groupval)+1;
					for (i = 0; i < x.length; i++) 
					{
						if(x.elements[i].type != "submit" && x.elements[i].type != "hidden")
						{
							if(x.elements[i].type=="file")
							{
								if(document.getElementById(x.elements[i].name+"preview")!=null)
									document.getElementById(x.elements[i].name+"preview").src="";
							}
							if(document.getElementById("err"+x.elements[i].name) != null)
								document.getElementById("err"+x.elements[i].name).innerHTML ="";
							if(document.getElementById(x.elements[i].name) != null)
								document.getElementById(x.elements[i].name).value = "";
						}
					}
					$("#hiddenValues").html(data);
					
					//for(i=0;i<valres.length;i++)
					$('#myAlert').modal("hide");
				//document.getElementById(valres[i]).value ="";				
					
				},
			error: function() {
				//to-do
				alert("fails");
						},
			});
		
	}
	
	return false;
}

function dupicateCheckIndivijual(fieldName)
{
	var checval = document.getElementById(fieldName).value;
	if(checval !="")
	{
		//alert(document.getElementById(fieldName).value);
		var form_data = new FormData();
		form_data.append("languageId",document.getElementById("language").value);
		form_data.append("CategoryId",document.getElementById("category").value);
		form_data.append("isDuplicate",fieldName);
		form_data.append(fieldName,checval);
		if(document.getElementById("isSubcat").value=='true')
			form_data.append("subCategoryId",document.getElementById("subcategory").value);
		else
			form_data.append("subCategoryId","NA");
		
		$.ajax({
				url: "../model/content/contentupload_exec.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
			    success: function(data){
						console.log(data);
					//alert(data.search("success"));
						document.getElementById("err"+fieldName).innerHTML="";
					if(data.search("success") == -1)
						document.getElementById("err"+fieldName).innerHTML="<font style='color:red'>"+checval+" Already Exist please go and edit the content</font>";
					
				},
			error: function() {
				//to-do
				alert("fails");
						},
			});
		
	}
	
	
	
	
}

function dupicateCheck()
{
	var result = true;
	//alert("ajaxEnter");
	var form_data = new FormData();
	form_data.append("languageId",document.getElementById("language").value);
	form_data.append("CategoryId",document.getElementById("category").value);
	form_data.append("isDuplicate",document.getElementById("isDuplicateCheck").value);
	if(document.getElementById("isSubcat").value=='true')
		form_data.append("languageId",document.getElementById("subcategory").value);
	else
		form_data.append("languageId","NA");
	
		$.ajax({
				url: "../model/content/contentupload_exec.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
			    success: function(data){
						console.log(data);
					//alert(data.search("success"));
						document.getElementById("message").innerHTML="";
					if(data.search("success") == -1)
					{
						document.getElementById("message").innerHTML="<font style='color:red'>Content Already Exist please go and edit the content</font>";
						result = false;
					}
					
				},
			error: function() {
				//to-do
				//alert("fails");
				result = false;
						},
			});
		//alert("ajax"+result);
		return result;
}

function ajaxPassing()
{
	$('#myAlert').modal("show");
	var form_data = new FormData(document.forms["contentUpload"]);
	$.ajax({
				url: "../model/content/contentuploadajax_exec.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
			    success: function(data){
					console.log(data);
					   var language = document.getElementById("language").value;
					   var category = document.getElementById("category").value;
					   var subcategory = document.getElementById("subcategory").value;
					   document.getElementById("contentUpload").reset();
					   document.getElementById("language").value=language;
					   document.getElementById("category").value=category;
					   categoryOnChange();
					   if(subcategory != "")
					   {
							document.getElementById("subcategory").value=subcategory;
							subcategoryOnChange();
					   }
					   if(data.search("fails") > -1)
						   document.getElementById("message").innerHTML = "<font style='color:red'>Content Already Exist please edit the content</font>";
					   else
							document.getElementById("message").innerHTML = "<font style='color:green'>Content Success fully added</font>";
					   $("#hiddenValues").html("");
					   $('#myAlert').modal("hide");
					   
				},
			error: function() {
					document.getElementById("message").innerHTML = "<font style='color:red'>Content Not added Please Try again.</font>";
						},
						
			});
		return false;
}

function removeElement(id,divid)
{
//		alert('test');
		var d = document.getElementById(divid+'divpreview');
		var d_nested = document.getElementById(id);
		d.removeChild(d_nested);
		var d_button = document.getElementById('butt'+id);
		d.removeChild(d_button);
		var value = $('#base64ValueArrayOf'+divid).val(); 
		value = value.replace("~"+id,"");
		value = value.replace(id,"");
		$('#base64ValueArrayOf'+divid).val(value); //store array	
		return false;
}

function imgAdd(img)
{
	if(document.getElementById(img).value !="") {
		var src = document.getElementById(img+'preview').src;
		var elem = document.createElement("img");
		elem.setAttribute("id", src);
		elem.setAttribute("src", src);
		elem.setAttribute("height", "45");
		elem.setAttribute("width", "45");
		document.getElementById(img+'divpreview').appendChild(elem);
		var btn = document.createElement("BUTTON");
		btn.setAttribute("id", "butt"+src);
		btn.setAttribute("class", "badge badge-warning");
		btn.setAttribute("onclick", 'removeElement("'+src+'","'+img+'")' );
		var t = document.createTextNode("x");   
		btn.appendChild(t); 
		document.getElementById(img+'divpreview').appendChild(btn);
		document.getElementById(img+'preview').src="";
		document.getElementById(img).value="";
		var value = $('#base64ValueArrayOf'+img).val(); //retrieve array
		//console.log(value);
		//alert('noCond'+value);
		if(value != ""){
			//value = JSON.parse(value);
			value +="~"+src;
			//console.log(value);
			$('#base64ValueArrayOf'+img).val(value); //store array	
		}
		else{
			//alert ("ELSE"+value);
			//var elarr = [];
			//elarr.push(src);
			$('#base64ValueArrayOf'+img).val(src); //store array	
			//alert ("ELSE"+value);
		}
		//value = JSON.parse(value);
		//alert(value);
		//value.push(src);
		//$('#'+img+'value').val(JSON.stringify(value)); //store array
	}
	return false;
}
function readImageData(img){
	 var imgData= document.getElementById(img);
	 if (imgData.files && imgData.files[0]) {
     var readerObj = new FileReader();
     readerObj.onload = function (element) {
		$('#'+img+'preview').attr('src', element.target.result);
       }
        readerObj.readAsDataURL(imgData.files[0]);
    }
}

function readImagesData(img)
{
	
	var imgData= document.getElementById(img);
	var preview = document.getElementById(img+'preview');
	$('#'+img+'preview').html("");
		for(i=0;i<imgData.files.length;i++)
		{
			 
			
			if(imgData.files[i].name)
			{
				var readerObj = new FileReader();
				readerObj.onload = function (element) {
					var image = new Image();
					image.height = 100;
					image.width = 100;
					image.src    = element.target.result;
					preview.appendChild(image);
				}
				readerObj.readAsDataURL(imgData.files[i]);
			}
			
		}
	//for(i=0;)
}
function showModal(id,colname)
{
	$('#detailContent').empty();
	$('#detailContent').append('<input type="hidden" id="collName" name="collName" value="'+colname+'" />');
	$('#detailContent').append('<input type="hidden" id="selectId" name="selectId" value="'+id+'" />');
	$('#detailContent').append('Keywords : <input type="text" id="newWord" name="newWord" value=""/>	&nbsp;');
	$('#detailContent').append('<button type="button" class="badge badge-warning" onClick="addField();" >Add</button>');
	$('#detailModal').modal('show');
	return false;
	
}

function addField()
{
	var newWord = document.getElementById('newWord').value
	var selectId = document.getElementById('selectId').value
	var collName = document.getElementById('collName').value
	var sessUserId = document.getElementById('headerUserName').value
	
	
	if(document.getElementById('newWord').value == '')
		alert("Please Enter Valid word");
	else
	{
		var form_data = new FormData();
		form_data.append('newWord',newWord);
		form_data.append('selectId',selectId);
		form_data.append('collName',collName);
		form_data.append('sessUserId',sessUserId);
		$.ajax({
				url: "../model/common/getdata.php",
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'POST',
			    success: function(data){
					//console.log(data);
					//alert(data);
					if(data.indexOf("success") != -1)
						$('#'+selectId).append('<option value="'+newWord+'" selected;>'+newWord+'</option>');   
					$('#detailModal').modal('hide');
				},
			error: function() {
					//document.getElementById("message").innerHTML = "<font style='color:red'>Content Not added Please Try again.</font>";
						},
						
			});
		
		
	}
	
}



