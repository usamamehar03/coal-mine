<?php
include('../web_parts.php');
chklog();
if ($_SESSION["emailstatus"]!="verified") 
{
   $_SESSION["updatemsg"]="u didnt verify email your data maybe  discard soon and we will not able to update it";
}
function sendmailee($link,$targetf,$name,$email,$Password,$picture,$upfil)
{
	require 'phpmailer/PHPMailerAutoload.php';
	//include 'phpmailer/class.smtp.php';
	$mail = new PHPMailer;
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host ='smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth =true;                               // Enable SMTP authentication
	//$mail->SMTPDebug = 2;
	$mail->Username ='coderpi7@gmail.com';                 // SMTP username
	$mail->Password ='12345asdf!@';                           // SMTP password
	$mail->SMTPSecure ='tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port =587;                                    // TCP port to connect to
	$mail->setFrom('coderpi7@gmail.com', 'coalmine web');
	$mail->addAddress($email);     // Add a recipient
	$mail->isHTML(true);     
	$str=rand(); 
	$key = md5($str);
	$link = "http://localhost/coal_mine_webc1/editprofile/activate.php?mail=$email&secretkey=$key";

	$mail->Subject = 'email update veriffication';
	$mail->Body    = 'your activation link is here '.$link. '  plz click on it and verify your email';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	if(!$mail->send()) 
	{
	    $_SESSION["updatemsg"]="verification mail failed to sent try again."."Mailer Error: " . $mail->ErrorInfo;	
	} 
	else
	{
		$_SESSION["key"] = $key;
	    $_SESSION["emailstatus"] = "notverified";
	    $_SESSION["uvname"] = $name;
	    $_SESSION["uvpassword"] =$Password;
	    $_SESSION["uvemail"] = $email;
	    $_SESSION["uvpicture"] = $picture;
	    $_SESSION["uvtargf"]=$targetf;
	    $_SESSION["uvfile"]=$upfil;
	    if ($upfil!=0) 
	    {
	    	$_SESSION["uvtmp"]=$_FILES['image']['tmp_name'];
	    }
	    $_SESSION["updatemsg"]="Verification link send, verify your account";
	}
}

function updatedata($upfil,$targetf,$link,$name,$email,$password,$picture)
{
	if($upfil!=0)
	{
		move_uploaded_file($_FILES['image']['tmp_name'],$targetf);
		$del="userpics/".$_SESSION["pic"];
		if(file_exists($del))
		{
			unlink($del);
		}
	}

	$query = "UPDATE user SET name=? ,password=?,email=?,picture=?   WHERE id=?";
	if($stmt = mysqli_prepare($link, $query))
	{
		mysqli_stmt_bind_param($stmt, "ssssi",$name,$password,$email,$picture,$_SESSION["id"]);
    	// Attempt to execute the prepared statement
    	if(mysqli_stmt_execute($stmt))
    	{
    		$_SESSION["username"] = $name;
            $_SESSION["password"] = $password;
            $_SESSION["email"] = $email;
            $_SESSION["pic"] = $picture;
        	$_SESSION["updatemsg"]="profile updated sucessfully";
    	}
	}
	else
	{
		$_SESSION["updatemsg"]="something went wrong in server try again";
	}
}

function sanitize($data) 
{
  $data = htmlspecialchars($data);
  $data = trim($data);
  return $data;
}
$targetf="userpics/";
$name=$email=$password=$picture="";
$name_err=$password_error=$email_err=$pic_err="";
$upem=$upfil=0;
$error=0;
function validatename($error,$name_err,$name)
{
	if (empty(trim($_POST["ename"]))) 
	{
		$name= $_SESSION["username"];
	}
	else
	{
		$name=sanitize($_POST["ename"]);
		if (!preg_match("|^[a-z]+$|i", $name))
		{
			$name_err="Name should contain only letters";
			$error++;
			// if (strlen($name)<10 )
			// {
			// 	$name_err="password should not  greather than 10 letters";;
			// 	$error++;
			// }
		}
	}
	$result=array($error,$name_err,$name);
	return $result;		
}

$link =connection();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
{
	if(isset($_POST["esub"]) &&$_SERVER["REQUEST_METHOD"] == "POST")
	{
		//validate name
		list($error,$name_err,$name)=validatename($error,$name_err,$name);
		// echo $error;
		// echo $name_err;
		// echo $name;
		//validate password
		if (empty(trim($_POST["epass"]))) 
		{
			$password= $_SESSION["password"];
		}
		else
		{
			$password=sanitize($_POST["epass"]);
			if (strlen($password)<5 )
			{
				$password_err="password should not  smaller than 5 digits";;
				$error++;
			}
		}
		//validate email
		if (empty(trim($_POST["eemail"]))) 
		{
			$email= $_SESSION["email"];
		}
		else
		{
			$email=sanitize($_POST["eemail"]);
			if (filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				list( $user, $domain ) = explode( '@', $email );
				if(checkdnsrr( $domain, 'MX'))
				{
					$query = "SELECT id FROM user WHERE email = ?";
					if($stmt = mysqli_prepare($link, $query))
	      			{
	      				mysqli_stmt_bind_param($stmt, "s", $param_mail);
			        // Set parameters
				        $param_mail = $email;
				        // Attempt to execute the prepared statement
				        if(mysqli_stmt_execute($stmt))
				        {
					        // Store result
					        mysqli_stmt_store_result($stmt);
					        // Check if username exists, if yes then verify password
					        if(mysqli_stmt_num_rows($stmt) ==1)
					        {
					            $email_err="this email already registered";
					            $error++;
					        }
					        else
					        {
					        	$upem++;
					        }
					    }
	      			}
	      			else
	      			{
	      				$email_err="something went wrong in server try again";
	      				$error++;
	      			}			
				}	
				else
				{
					$email_err="Domain does not exist";
					$error++;
				}
			}
			else
			{
				$email_err="Email invalid";
				$error++;
			}
		}
		//valid pic
		if ($_FILES['image']['error']==4) 
		{
			$picture= $_SESSION["pic"];
		}
		else
		{
	     	$picture=$_FILES['image']['name'];
	      	$targetf = $targetf. basename($_FILES['image']['name']);
	      	$file_size =$_FILES['image']['size'];
	      	// $file_tmp =$_FILES['image']['tmp_name'];
	      	// $file_type=$_FILES['image']['type'];
	      	$file_ext=strtolower(pathinfo($targetf,PATHINFO_EXTENSION));     
	      	$extensions= array("jpeg","jpg","png");
	      	if(!file_exists($targetf))
	      	{
	      		if(in_array($file_ext,$extensions)=== false)
		      	{
		        	$pic_err="extension not allowed, please choose a JPEG or PNG file";
		        	$error++;
		      	}
		      	else
		      	{
		      		if($file_size > 500000)
		      		{
			         	$pic_err='File size must be less then  5 MB';
			         	$error++;
			      	}
			      	else{$upfil++;}
		      	}
	      	}
	      	else
	      	{
	      		$pic_err="this picture already exist";
	      		$error++;
	      	}
	      
		}



		if ($error==0) 
		{			
			if($upem!=0)
			{
				sendmailee($link,$targetf,$name,$email,$password,$picture,$upfil);
			}
			else
			{
				updatedata($upfil,$targetf,$link,$name,$email,$password,$picture);
			}
		}
	}	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Update Profile</title>
		<?php setup();	?>
		<link rel="stylesheet" href="../styles.css" type="text/css">
		<script type="text/javascript" language="javascript" src="../logins/jquery.js"></script>
		<script type="text/javascript">
			function timedMsg()
			{
				var t=setTimeout("document.getElementById('msg').style.display='none';",4000);
			}
		</script>
	</head>
	<body onload="timedMsg();" >
		<section class="jumbotron-fluid">
			<?php
				web_header();
				$page="edit_profile.php";
				web_sidebar($page);
			?>
			<section class="col-sm-9 data float-right p-3">
				<?php if (isset($_SESSION['updatemsg'])) 
				{
					echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" id="msg">
							  	<span><strong>'. $_SESSION["username"].'!'.'</strong>'.  $_SESSION["updatemsg"].'</span>
							  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							    	<span aria-hidden="true">&times;</span>
							  	</button>
							</div>';
					unset($_SESSION["updatemsg"]);
				}?>
				<div class="temp updateform m-4 row p-4">
					<div class="d-flex flex-column heading">Update Profile</div>
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data" class="d-flex flex-column p-3 p-edit" style="width: 800px; border: 2px solid black; border-radius: 5px;">
					  	<div class="form-group">
					    	<label for="inputAddress">Name</label>
					    	<input type="text" class="form-control" id="inputname" placeholder="enter new name" name="ename">
					    	<span class=" alert alert-danger float-right d-inline-block m-0 p-0 mt-2 pl-sm-2 pr-sm-2">
					    		<?php
					    			if (isset($name_err)) 
					    			{
					    				echo $name_err;
					    			}
					    		?>
					    	</span>
					  	</div>
					  	<div class="form-group">
					    	<label for="inputAddress">New Password</label>
					    	<input type="password" class="form-control" id="inputpass" placeholder="new password" name="epass">
					    	<span  class=" alert alert-danger float-right d-inline-block m-0 p-0 mt-2 pl-sm-2 pr-sm-2">
					    		<?php
					    			if (isset($password_err)) 
					    			{
					    				echo $password_err;
					    			}
					    		?>
					    	</span>
					  	</div>
					  	<div class="form-group">
					    	<label for="inputAddress">New Email</label>
					    	<input type="email" class="form-control" id="inputemail" placeholder="new email" name="eemail">
				    		<?php
				    			if (isset($email_err)) 
				    			{
				    				echo '<span  class=" alert alert-danger float-right d-inline-block m-0 p-0 mt-2 pl-sm-2 pr-sm-2">'.$email_err.'</span>';
				    			}
				    		?>
					    	
					  	</div>
					  	<div class="form-group">
					    	<label for="inputAddress">Add picture</label>
					    	<input type="file" class="form-control" id="inputpic" placeholder="add picture" name="image">
					    	<span  class=" alert alert-danger float-right d-inline-block m-0 p-0 mt-2 pl-sm-2 pr-sm-2">
					    		<?php
					    			if (isset($pic_err)) 
					    			{
					    				echo $pic_err;
					    			}
					    		?>
					    	</span>
					  	</div>
					  	<button type="submit" class="btn btn-primary" name="esub" id="su">Submit</button>
					</form>
				</div>
			</section>
		</section>



	<!-- 	<script type="text/javascript">
			function password_match()
			{
			    var namee = document.getElementById('inputname').value ; 
			    var passe = document.getElementById('inputpass').value ; 
			    
			     $.post("test.php", {
			     name: namee, pass: passe
			      },
			   
			   function(data, status){
			   	alert(data);
			   // document.getElementById('show-result').innerHTML = data ; 
			   
			   }
			   ) ; 
			}
			// document.getElementById("su").addEventListener("click",timedMsg);
		</script> -->
	</body>
</html>