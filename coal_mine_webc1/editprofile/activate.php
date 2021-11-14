<?php
include('../web_parts.php');
chklog();
// session_start();
if($_SESSION["emailstatus"]!="verified")
{
	if (isset($_GET['secretkey'])&& $_SESSION["key"]==$_GET['secretkey'] )
	{
		if ($_SESSION["uvfile"]!=0) 
		{
			move_uploaded_file($_SESSION["uvtmp"],$_SESSION["uvtargf"]);
			$del="userpics/".$_SESSION["pic"];
			if(file_exists($del))
			{
				unlink($del);
			}
		}

		$link =connection();
	    $query = "UPDATE user SET name=? ,password=?,email=?,picture=?   WHERE id=?";
		if($stmt = mysqli_prepare($link, $query))
		{
			mysqli_stmt_bind_param($stmt, "ssssi",$_SESSION["uvname"],$_SESSION["uvpassword"],$_SESSION["uvemail"],$_SESSION["uvpicture"] ,$_SESSION["id"]);
	    	// Attempt to execute the prepared statement
	    	if(mysqli_stmt_execute($stmt))
	    	{
	    		$_SESSION["username"] = $_SESSION["uvname"];
	            $_SESSION["password"] = $_SESSION["uvpassword"];
	            $_SESSION["email"] = $_SESSION["uvemail"];
	            $_SESSION["pic"] = $_SESSION["uvpicture"];
	            $_SESSION["emailstatus"]="verified";

	            $_SESSION["uvname"]="";
	            $_SESSION["uvpassword"]="";
	            $_SESSION["uvemail"]="";
	            $_SESSION["uvpicture"]="";
	            $_SESSION["uvfile"]="";
	            $_SESSION["uvtmp"]="";
	        	$_SESSION["updatemsg"]="profile updated sucessfully";
	        	header("location: edit_profile.php");

	    	}
		}
		else
		{
			$_SESSION["updatemsg"]="something went wrong in server try again";
			header("location: edit_profile.php");
		}
	}
	else
	{
		echo "maybe you are using expired link maybe so we cant update your profile  please try again";	
	}
}
else
{
    echo "verified maile";
	// header("location: logins/login.php");
}
?>