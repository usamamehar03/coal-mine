<?php 
include "web_parts.php";
require('configuration_files/thconfig.php');
require('configuration_files/fgases_config.php');
require('configuration_files/hgases_config.php');
chklog();
if ($_SESSION["emailstatus"]!="verified") 
{
  $_SESSION["updatemsg"]="you didnt verify email ur data maybe  discard soon and we will not able to update it";
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" id="msg">
                  <span><strong>'. $_SESSION["username"].'!'.'</strong>'.  $_SESSION["updatemsg"].'</span>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>';
   unset($_SESSION["updatemsg"]);
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
	<?php setup();?>
	<title>Coalmine investigator</title>
	<link rel="stylesheet" type="text/css" href="homest.css">
	<script>
		 var t1,ajxclose;
		function fetchdataq(ar,color,dps,gno)
        {
           ajxclose=$.ajax({
            url: 'port_chartconn.php',
            dataType: "json",
            type: 'post',
            success: function(data){
              	if(data[0]=="false")
              	{
              		alert(data[1]);//connect arduino
              	}
              	else
              	{
                	if (data[0]=="true" && data[1].length<7) 
                	{
	                	alert( 'some sensors not connected to arduino');
	                	return;
                	}
                	else
                	{
                  		for (var i=0 ; i <data[1].length; i++) 
                  		{
                    		if(data[1][i]=="00-999.00")
                    		{
                      			alert('some sensors not gaving correct readings');
                      			return;
                    		}
                  		}
  						if (gno==1) 
  						{
  							var j=0;
  							var ses= data;
		                    for (var i = 0; i< 2 ; i++ )
		                    {
		                      	var pval=parseInt(ses[1][j], 10)
		                        color=ses[1][i] > 75 ? "#FF2500" : ses[1][i] >=50 ? "#FF6000" : ses[1][i]<50 ? "#41CF35" : null;
		                        dps[i] = {label: ar[i], y: pval, color: color};
		                        j++;
		                    }
  							chart.options.data[0].dataPoints = dps;
	                    	chart.render();
  						}
  						else if (gno==2) 
  						{
  							var j=2;
  							var ses= data;
		                    for (var i = 0; i< 2 ; i++ )
		                    {
		                      	var pval=parseInt(ses[1][j], 10)
		                        color=ses[1][i] > 75 ? "#FF2500" : ses[1][i] >=50 ? "#FF6000" : ses[1][i]<50 ? "#41CF35" : null;
		                        dps[i] = {label: ar[i], y: pval, color: color};
		                        j++;
		                    }
  							fgchart.options.data[0].dataPoints = dps;
	                    	fgchart.render();
  						}
  						else
  						{
  							var j=4;
  							var ses= data;
		                    for (var i = 0; i< 3 ; i++ )
		                    {
		                      	var pval=parseInt(ses[1][j], 10)
		                        color=ses[1][i] > 75 ? "#FF2500" : ses[1][i] >=50 ? "#FF6000" : ses[1][i]<50 ? "#41CF35" : null;
		                        dps[i] = {label: ar[i], y: pval, color: color};
		                        j++;
		                    }
  							hzchart.options.data[0].dataPoints = dps;
	                    	hzchart.render();
  						}
                	}
              	}
            },
            complete:function(data){
            t1=setTimeout(fetchdataq,3000,ar,color,dps,gno);
            }
           });
        }
        					//
        $(document).ready(function(){
        	$(".thc").click(function(){
        		// alert("done");
        		ajxclose.abort();
            	clearTimeout(t1);
            });
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
					document.getElementById("alertm").innerHTML="Data port is on,now you can see live results."
				}
			}
			function ofport()
			{
				// alert("of");
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
					"Data port is off. unable to get real time data";
				}

			}
	</script>
</head>
<body class="home">
	<nav class="navbar navbar-expand-md navbar-dark nav-tabs pt-0 pb-0">
											<!-- toggler button -->
		<button class="navbar-toggler" type="button" data-toggle="collapse"  data-target="#navtoggle">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand col-4 pt-1 pb-1" href="index.php"> <img src="slider/logotest.png"> </a>
		<div class="collapse navbar-collapse" id="navtoggle" >
			<ul class="navbar-nav mr-auto mt-2 mt-sm-0">
				<li class="nav-item ml-md-5">	<a class="nav-link" href="index.php">home</a>		</li>
				<li class="nav-item ml-md-5 ">	<a class="nav-link" href="about.php">about</a>		</li>
			</ul>
			<div class="dropdown">
				<a class="nav-link dropdown-toggle customButton" data-toggle="dropdown" href="#">
					<?php
						$pic=$_SESSION['pic'];
					 echo '	<img src="editprofile/userpics/'.$pic.'" style="width: 60px;height: 60px;">';
					?>
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="dasboard.php">Dashboard</a>
					<a class="dropdown-item" href="editprofile/edit_profile.php">Edit profile</a>
					<a class="dropdown-item" href="?out=true">Logout</a>
				</div>	
			</div>
		</div>
	</nav>

									<!--slider  -->
	<section class="container-fluid p-0">
		<div class="carousel slide" data-ride="carousel" id="slider">
			<ul class="carousel-indicators">
				<li class="active" data-target="#slider" data-slide-to="0"></li>
				<li data-target="#slider" data-slide-to="1"></li>
				<li data-target="#slider" data-slide-to="2"></li>
				<li data-target="#slider" data-slide-to="3"></li>
				<li data-target="#slider" data-slide-to="4"></li>
				<li data-target="#slider" data-slide-to="5"></li>
				<li data-target="#slider" data-slide-to="6"></li>
			</ul>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img class="d-block w-100" src="slider/s7.jpg" alt="Responsive image">
					<div class="carousel-caption">
						<h2>Coal Mine Investigator</h2>
						<p>Efficient coal mine's environment survey</p>
					</div>
				</div>
				<div class="carousel-item">
					<img class="d-block w-100" src="slider/s2.jpg">
				</div>
				<div class="carousel-item">
					<img class="d-block w-100" src="slider/s5.jpg">
				</div>
				<div class="carousel-item">
					<img class="d-block w-100" src="slider/s17.jpg">
				</div>
				<div class="carousel-item">
					<img class="d-block w-100" src="slider/s16.jpg">
				</div>
				<div class="carousel-item">
					<img class="d-block w-100" src="slider/s19.jpg">
				</div>
				<div class="carousel-item">
					<img class="d-block w-100" src="slider/s1.jpeg">
				</div>
			</div>
			<a class="carousel-control-prev" href="#slider" data-slide="prev">
				<span class="carousel-control-prev-icon" > </span>
			</a>
			<a class="carousel-control-next" href="#slider" data-slide="next">
				<span class="carousel-control-next-icon"></span>
			</a>
		</div>
	</section>
									<!-- content -->
	<section class="jumbotron p-4 m-0 dashboard">
											<!-- dashboard -->
			<section class=" p-3 maindash">
				<h2 style="text-transform: capitalize; color: #6c757d; text-align: center;" class="mb-4 pt-2">admin dashboard</h2>
				<div class="d-flex flex-row justify-content-between mt-3">
					<div class="d-flex flex-column dash1">
						<h5 class="mt-5" >port managment</h5>
						<!-- <h4 class="">500</h4> -->
						<div class="mt-4"> 
							<button type="button" data-toggle="modal" data-target="#portm">
							  view more
							</button>
						</div>
					</div>
					<div class="d-flex flex-column dash2 ">
						<h5 class="mt-5">Temperature</h5>
						<!-- <h4>500</h4> -->
						<div class="mt-4"> 
							<button type="button" data-toggle="modal" data-target="#tempm" id="thb">
								view more
							</button>
						</div>
					</div>
					<div class="d-flex flex-column dash3">
						<h5 class="mt-5">flamable gases</h5>
						<!-- <h4>500</h4> -->
						<div class="mt-4">
							<button type="button" data-toggle="modal" data-target="#flamem" id="fgb">
								view more
							</button>
						</div>
					</div>
					<div class="d-flex flex-column dash4">
						<h5 class="mt-5">hazardous gases</h5>
						<!-- <h4>500</h4> -->
						<div class="mt-4">
							<button type="button" data-toggle="modal" data-target="#hazarm" id="hzb">
								view more
							</button>
						</div>
					</div>
				</div>
			</section>
	</section>
	<footer>
		<span>copy right</span>
	</footer>

	<!-- model of data -->
														<!-- port managment model -->
	<!-- Modal -->
	<div class="modal fade" id="portm">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
			    <div class="modal-body">
			        
			        <section class="jumbotron-fluid">
						<section class="p-3" >
							<div class="temp  portstatus">
								<?php
									if(isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on' )
									{
										$msgee='Data port on,now you can  see live results.';
										$clas='alert-success' ;
									}
									else
									{ 
									 	$msgee='Data port is off. unable to get real time data';
									 	$clas='alert-warning' ;
									} 
								?>
								<div class="alert <?php echo $clas; ?> col-12" id="alertm" role="alert">
								  	<span id="aler">
								  		<?php echo $msgee; ?>
								  	</span>
								</div>
							</div>
							<div class="temp pl-3 pt-5 portb">
								<button class= "btn <?php if(isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on' ){ echo 'btn-success' ;}else{ echo ' btn-outline-success ';} ?>  mr-5 ml-md-5 " id="obtn" onclick="onport()">PORT ON</button>
								<button class="btn <?php if(isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='off' ){ echo 'btn-danger' ;}else{ echo ' btn-outline-danger ';} ?> ml-md-5" id="ofbtn" onclick="ofport()">PORT OFF</button>
							</div>
						</section>
					</section>





			    </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        </div>
		    </div>
		</div>
	</div>
													<!-- temperature model -->
	<!-- Modal -->
	<div class="modal fade" id="tempm">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			        <button type="button" class="close thc" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true"  >&times;</span>
			        </button>
		      	</div>
			    <div class="modal-body">
			    	<?php 
			   			if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
					    {
					        echo '
					        <div>
					         	<div id="thdiv" style="height: 350px;"></div>
					        </div>';
					        echo'<script type="text/javascript">									 $(document).on("shown.bs.modal","#tempm", function () {
									var chart=charts();
					                chart.render();
					                var ar=["Temperature","Humidity"];
					                var color;
						            var dps = chart.options.data[0].dataPoints; 
						            var gno=1;
					                fetchdataq(ar,color,dps,gno);
								});
					        </script>';
					    }
					    else
					    {
					        echo '
					        <div class="alert alert-warning">Live stats disabled until data port is on</div>';
					    }
			    	?>
			    </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-outline-danger thc" data-dismiss="modal"  >Close</button>
		        </div>
		    </div>
		</div>
	</div>
														<!-- hazardous gases -->
	<!-- Modal -->
	<div class="modal fade" id="hazarm">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			        <button type="button" class="close thc" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
			    <div class="modal-body">
			        <?php 
			   			if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
					    {
					        echo '
					        <div >
					         	<div id="hazarchart" style="height: 350px;"></div>
					        </div>';
					        echo'<script type="text/javascript">
					                $(document).on("shown.bs.modal","#hazarm", function () {
					                    	var chart2=hzgraph();
					                    	chart2.render();
					                    	var ar=["carbon mono","smoke","dust strength"];
						                    var color;
						                    var dps = hzchart.options.data[0].dataPoints;
						                    var gno=3;
					                    	fetchdataq(ar,color,dps,gno);
					                    });
					            </script>';
					    }
					    else
					    {
					        echo '
					        <div class="alert alert-warning">Live stats disabled until data port is on</div>';
					    }
			    	?>
			    </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-outline-danger thc" data-dismiss="modal">Close</button>
		        </div>
		    </div>
		</div>
	</div>
														<!-- flamable gases -->
	<!-- Modal -->
	<div class="modal fade" id="flamem">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			        <button type="button" class="close thc" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
			    <div class="modal-body">
			        <?php 
			   			if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
					    {
					        echo '
					        <div>
					         	<div id="flamechart" style="height: 350px;"></div>
					        </div>';
					        echo'<script type="text/javascript">
					               $(document).on("shown.bs.modal","#flamem", function () {
					                    	var chart1=fggraph();
					                    	chart1.render();
					                    	var ar=["Methan","Hydrogen"];
						                    var color;
						                    var dps = fgchart.options.data[0].dataPoints;
						                    var gno=2;
					                    	fetchdataq(ar,color,dps,gno);
					                    });
					            </script>';
					    }
					    else
					    {
					        echo '
					        <div class="alert alert-warning">Live stats disabled until data port is on</div>';
					    }
			    	?>
			    </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-outline-danger thc" data-dismiss="modal">Close</button>
		        </div>
		    </div>
		</div>
	</div>

	<script type="text/javascript">
		
	        $(document).ready(function() {
	            $('#obtn').click(function(e) {
	                $.ajax({
	                	type : 'POST',
	                    url : 'portmanagment/portactivate.php',
	                    data:'sesion=',
	                    dataType: 'json',
	                    success : function(data) {
	                            alert(data[1]);
	                    }
	                });
	            });

	            $('#ofbtn').click(function(e) {
	                $.ajax({
	                	type : 'POST',
	                    url : 'portmanagment/portactivate.php',
	                    data:'sesion1=',
	                    dataType : 'json',
	                    success : function(data) {
                            alert(data[1]);
	                    }
	                });
	            });
	        });
    	</script>
	
									<!-- javascript&jquery link -->
									<script type="text/javascript" language="javascript" src="logins/jquery.js"></script>
</body>
</html>