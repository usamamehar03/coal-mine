<?php
include"web_parts.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>About Us</title>
	<link rel="stylesheet" type="text/css" href="homest.css">
	<?php setup();?>
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
	<section class="jumbotron mb-0 pb-3">
		<div class="jumbotron pt-4" style="background-color: white;">
			<h5 class="mb-3">About product</h5>
			<div style="text-transform: capitalize;">
				about this project
				Every year hundreds of workers dies in Coal mines. Coal mine exlopsion and presense  hazardous gases are major reason which cost human lives. Our project helps the Coal mine survey team to determine the enternal environment of coal mine. Detail about gases and other statictics that are collected via drone are given below.
			</div>
		</div>
		<div class="jumbotron pt-4" style="background-color: white;">
			<h5 class="mb-3"> Collected Data details</h5>
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
				  <thead style="background-color: #20c997;">
				    <tr>
				      <th scope="col">#</th>
				      <th scope="col">Name</th>
				      <th scope="col">Category</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				      <th scope="row">1</th>
				      <td>Hydrogen</td>
				      <td>Flameable</td>
				    </tr>
				    <tr>
				      <th scope="row">2</th>
				      <td>Methane</td>
				      <td>Flameable</td>
				    </tr>
				    <tr>
				      <th scope="row">3</th>
				      <td>Carbon mono oxide</td>
				      <td>Hazardous</td>
				    </tr>
				    <tr>
				      <th scope="row">4</th>
				      <td>Smoke</td>
				      <td>Hazardous</td>
				    </tr>
				    <tr>
				      <th scope="row">5</th>
				      <td>Dust</td>
				      <td>Hazardous</td>
				    </tr>
				    <tr>
				      <th scope="row">6</th>
				      <td>Temperature</td>
				      <td>NILL</td>
				    </tr>
				     <tr>
				      <th scope="row">7</th>
				      <td>Humidity</td>
				      <td>NILL</td>
				    </tr>
				  </tbody>
				</table>
			</div>
		</div>
	</section>
	<footer>
		<span>copy right</span>
	</footer>
</body>
</html>