
<?php
include "web_parts.php";
chklog();
?>
<!DOCTYPE html>
<html>
<head>
	<?php setup(); ?>
	<title>Search</title>
	<link rel="stylesheet" href="styles.css" type="text/css">
    <script type="text/javascript" language="javascript" src="logins/jquery.js"></script>
</head>
<body>
	<section class="jumbotron-fluid">
        <!-- nave bar   -->
        <?php 
         	web_header();
         	$page="manual_search.php";
            web_sidebar($page); 
        ?> 
        <!--  other content-->
        <section class="col-sm-9 data float-right p-3">
            <div class="temp m-4 row p-4">
            	<form class="form-inline">
            		<div class="form-group">
					    <label for="control">gases</label>
					    <select class="form-control ml-sm-2" id="control">
					    	<option selected  value="chose">select..</option>
						    <option value="humidity" >Humidity</option>
                            <option value="temperature" >Temperature</option>
                            <option value="methan" >Methan</option>
						    <option value="hydrogen" >hydrogen</option>
						    <option value="smoke" >Smoke</option>
						    <option value="carbonmono" >carbon mono-oxide</option>
						    <option value="dust" >dust strength</option>
					    </select>
					</div>
					<!-- <div class="btn btn-info ml-2 mt-sm-0 mt-3"  id="datasearch" name="search">search</div> -->
                    <input type="submit" name="submit" value="search" class="btn btn-info ml-2 mt-sm-0 mt-3"  id="datasearch">
            	</form>
            </div>

            <div class="temp m-4 row p-4" id="responsea"></div>
        </section>
    </section>

    <script type="text/javascript">
        $('#datasearch').on('click', function(event) {
            event.preventDefault();
            var name = $('#control').val();
            $.ajax({
                url: 'portmanagment/portactivate.php',
                type: 'post',
               data: {name: name},
               datatype:'json',
               success: function(response){
                var result= $.parseJSON(response); 
                $('#responsea').html(result);
               }
            });
        });
    </script>
</body>
</html>