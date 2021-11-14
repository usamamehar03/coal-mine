<?php
include'../web_parts.php';
chklog();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>port</title>
		<?php setup();	?>
		<!-- <link rel="stylesheet" href="stylesheet.css" type="text/css"> -->
		<link rel="stylesheet" href="../styles.css" type="text/css">
		<link rel="stylesheet" type="text/css" href="../ani.css">
		<script type="text/javascript" language="javascript" src="../logins/jquery.js"></script>
		<script>
			$(document).ready(function() {
				$('#mainani').hide();
				});
			function onport()
			{
				if (document.getElementById("obtn").classList.contains("btn-outline-success"))
				{
					document.getElementById("obtn").classList.add("btn-success");
					document.getElementById("obtn").classList.remove("btn-outline-success");
					document.getElementById("ofbtn").classList.remove("btn-danger");
					document.getElementById("ofbtn").classList.add("btn-outline-danger");
				}
				if (document.getElementById("obtn").classList.contains("btn-success"))
				{
					document.getElementById("alertm").classList.add("alert-success");
					document.getElementById("alertm").classList.remove("alert-warning");
					document.getElementById("alertm").innerHTML="Data port is on,now you can see live results.";
				}
			}
			function ofport()
			{
				if (document.getElementById("ofbtn").classList.contains("btn-outline-danger"))
				{
					document.getElementById("ofbtn").classList.add("btn-danger");
					document.getElementById("ofbtn").classList.remove("btn-outline-danger");
					document.getElementById("obtn").classList.remove("btn-success");
					document.getElementById("obtn").classList.add("btn-outline-success");
				}
				if (document.getElementById("ofbtn").classList.contains("btn-danger"))
				{
					document.getElementById("alertm").classList.add("alert-warning");
					document.getElementById("alertm").classList.remove("alert-success");
					document.getElementById("alertm").innerHTML=
					"Data port is off.unable to get real time data";
				}

			}
		</script>
	</head>
	<body id="1m">
<section id="bod">	
		<section class="jumbotron-fluid">
			<?php
				web_header();
				$page="port_mangment.php";
				web_sidebar($page);
			?>
			<section class="col-sm-9 data float-right p-3" >
				<div class="temp m-4 row p-4 portstatus">
					<?php
						if(isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on' )
						{
							$msgee='Data port is on,now you can see live results.';
							$clas='alert-success' ;
						}
						else
						{ 
						 	$msgee='Data port is off.unable to get real time data';
						 	$clas='alert-warning' ;
						} 
					?>
					<div class="alert <?php echo $clas; ?> col-12" id="alertm" role="alert">
					  	<span id="aler">
					  		<?php echo $msgee; ?>
					  	</span>
					</div>
				</div>
				<div class="temp m-4 row p-4 pl-5 portb">
					<button class= "btn <?php if(isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on' ){ echo 'btn-success' ;}else{ echo ' btn-outline-success ';} ?>  mr-5 ml-md-5 " id="obtn" onclick="onport()">PORT ON</button>
					<button class="btn <?php if(isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='off' ){ echo 'btn-danger' ;}else{ echo ' btn-outline-danger ';} ?> ml-md-5" id="ofbtn" onclick="ofport() ">PORT OFF</button>
				</div>
			</section>
		</section>
</section>	
		<script type="text/javascript">
	        $(document).ready(function() {
	        	var as,obj;
	        	var pa=$('#1m');
	            $('#obtn').click(function(e) {
	                $.ajax({
	                	type : 'POST',
	                    url : 'portactivate.php',
	                    data:'sesion=',
	                    datatype: 'json',
	                    success : function(data) {
	                    	obj= JSON.parse(data);
	                            alert(obj[1]);
	                    },
	                    complete : function()
	                    {
	                    	if (obj[0]!="on" && obj[0]=="true") 
	                    	{
	                    		$('#bod').hide();
		                    	$('#mainani').fadeIn(2000);
		                    	var i=1;
								function changetext(i)
								{
									$('#anitext').fadeOut(1000,function(){document.getElementById("anitext").innerHTML=obj[i];});
									$('#anitext').fadeIn(1000);									
									i++;
									var t=setTimeout(changetext, 6000,i);
									if (i==5)
									{
										setTimeout(function(){
										$.ajax({
						                	type : 'POST',
						                    url : 'portactivate.php',					          
						                    data:'validateconn=',
						                    datatype: 'json',
						                    success : function(resp) {
						                    	obj= JSON.parse(resp);
						                    	if (obj[0]=="false")
						                    	{
						                    		$('#anitext').fadeOut(1000,function(){document.getElementById("anitext").innerHTML="connection failed connect your device ";});
													$('#anitext').fadeIn(1000);
						                    		// document.getElementById("anitext").innerHTML="connection failed connect your device ";
						                    		setTimeout(function(){
						                    			$( "#mainani" ).fadeOut(500, function() {
												      	as=$( "#mainani" ).detach();
												       	$( "#bod" ).show();
												       	});
														clearTimeout(t);
						                    		},3000);
						                    	}
						                    }
					                	});
					                	},2000);
									}

									if (i>5) 
									{
										setTimeout(function(){
										$( "#mainani" ).fadeOut(500, function() {
								      	as=$( "#mainani" ).detach(); //makes page more lightweight
								       	$( "#bod" ).show();
								       	});
										clearTimeout(t);
										},3000);
									}
								}
								changetext(i);
	                    	}
	                    	if (obj[0]!="on" && obj[0]=="false") 
	                    	{
	                    		$('#bod').hide();
		                    	$('#mainani').fadeIn(2000);
		                    	$('#anitext').fadeOut(1000,function(){document.getElementById("anitext").innerHTML="failed to connect,no device found.please connect your device";});
									$('#anitext').fadeIn(1000);
								setTimeout(function(){
								$( "#mainani" ).fadeOut(500, function() {
							      	as=$( "#mainani" ).detach();
							       	$( "#bod" ).show();
							       	});
								},6000);
	                    	}
	                    }
	                });
	            });

	            $('#ofbtn').click(function(e) {
	                $.ajax({
	                	type : 'POST',
	                    url : 'portactivate.php',
	                    data:'sesion1=',
	                    datatype:'json',
	                    success : function(data) {
	                    	data=JSON.parse(data);
                            alert(data[1]);
                            if (data[0]!="off") 
                            {
                            	pa.append(as);
                            }
	                    }
	                });
	            });
	        });
    	</script>
    	<div id="mainani">
			<div class="cube-wrapper">
			  <div class="cube-folding">
			    <span class="leaf1"></span>
			    <span class="leaf2"></span>
			    <span class="leaf3"></span>
			    <span class="leaf4"></span>
			  </div>
			  <span class="loading" data-name="Loading" id="anitext">Loading...</span>
			</div>
		</div>
	</body>
</html>