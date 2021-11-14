<?php
require'basegases_config.php';
chklog();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Database Stats</title>
		<?php setup();	?>
		<!-- <link rel="stylesheet" href="stylesheet.css" type="text/css"> -->
    <link rel="stylesheet" href="../styles.css" type="text/css">
    <script type="text/javascript" src="../logins/jquery.js"></script>
	</head>
	<body onload="database_gases(),database_th()">
		<section class="jumbotron-fluid">
			<?php
				web_header();
        $page="database_stats.php";
				web_sidebar($page);
			?>
			<section class="col-sm-9 data float-right p-3">
				<!-- temp & humidity -->
                <div class="temp m-4 row p-4">
                  	<div class="col-12 btitle"> <span>Temperature and Humidity</span></div>
                  	<div class="col-lg-12 mt-2 mt-lg-0" id="aler1"></div>
                  	<div class="col-lg-12 mt-2 mt-lg-0" id="disp1">
                    	<div id="basethchart" style="height: 450px;"></div>
                  	</div>
                </div>
                <!--  gases -->
                <div class="hazar  m-4 p-4 row">
                  	<div class="col-12 btitle"> <span>Flameable Gases</span></div>
                  	<div class="col-lg-12 mt-2 mt-lg-0" id="aler2"></div>
                  	<div class="col-lg-12 mt-2 mt-lg-0" id="disp2">
                    	<div id="basegchart" style="height: 550px;"></div>
                  	</div>
                </div>

                <!-- <div class="flam  m-4">flamable gases</div> -->
			</section>
		</section>
    <script type="text/javascript">
      $(document).ready(function(){
        // var res1=<?php //echo json_encode($msge1); ?>;
       // var res2=<?php// echo json_encode($msge2); ?>;
        if (<?php echo json_encode($i); ?>==2) 
        {

          $('#disp1').html('');
        }
        if (<?php echo json_encode($i); ?>==6) 
        {
          $('#disp2').html('');
        }
        $('#aler1').html(<?php echo json_encode($msge1); ?>);
        $('#aler2').html(<?php echo json_encode($msge2); ?>);
      });
    </script>
   <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
	</body>
</html>