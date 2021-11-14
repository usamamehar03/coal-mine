<?php
include ('../web_parts.php');
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
  header("location: ../index.php");
  exit;
}
//connect database
$link =connection();
$username = $password = $username_err=$password_err="";
$error=0;
// $username_err = $password_err = "";
if(isset($_POST["sub"]) &&$_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check if username is empty
    if(empty(trim($_POST["uname"])) || empty(trim($_POST["upass"])) )
    {
      $error++;
    } 
    else
    {
      $username = trim($_POST["uname"]);
      $password = trim($_POST["upass"]);
    }
    // Validate credentials
    if($error==0)//empty($username_err) && empty($password_err)
    {
      // Prepare a select statement
      $sql = "SELECT * FROM user WHERE name = ?";  
      if($stmt = mysqli_prepare($link, $sql))
      {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $username;
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt))
        {
          // Store result
          mysqli_stmt_store_result($stmt);
          // Check if username exists, if yes then verify password
          if(mysqli_stmt_num_rows($stmt) >0)
          {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password,$email,$pic);
            if(mysqli_stmt_fetch($stmt))
            {
              if($password==$hashed_password)//password_verify($password, $hashed_password)
              {
                // Password is correct, so start a new session
                // session_start();
                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["password"] = $hashed_password;
                $_SESSION["email"] = $email;
                $_SESSION["pic"] = $pic;
                $_SESSION["emailstatus"] = "verified";
                $_SESSION['portstatus']='off';
                if(!empty($_POST["remember"]))   
                {  
                  setcookie ("member_login",$username,time()+ (10 * 365 * 24 * 60 * 60));  
                  setcookie ("member_password",$password,time()+ (10 * 365 * 24 * 60 * 60));
                  // $_SESSION["coki"]="set";
                }  
                else  
                {
                  if(isset($_COOKIE["member_login"]))   
                  {
                    $vl=" ";
                    setcookie ("member_login","",time()- 3600);
                  }  
                  if(isset($_COOKIE["member_password"]))   
                  {
                    $vl1=" ";   
                    setcookie ("member_password","",time()- 3600);
                  }  
                  // $_SESSION["coki"]="unset";  
                }                     
                // Redirect user to welcome page
                header("location: ../index.php");
              } 
              else
              {
                // Display an error message if password is not valid
                $password_err = "invalid password.";
              }
            }
          } 
          else
          {
            // Display an error message if username doesn't exist
            $username_err = "invalid user name.";
          }
        } 
        else
        {
          echo "Oops! Something wrong in server.try again.";
        }
      }  
      // Close statement
      // mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- manual style sheet     -->
    <!-- <link rel="stylesheet" href="stylesheet.css" type="text/css"> -->
    <link rel="stylesheet" href="../styles.css" type="text/css">
      <!--      font awesome-->
    <script src="https://kit.fontawesome.com/876e7a43f2.js" crossorigin="anonymous"></script>
    <script type="text/javascript" language="javascript" src="jquery.js"></script>
    <script type="text/javascript" language="javascript" src="script.js"></script>
    <title>Coalmine investigator</title>
  </head>
    
  <body>
      <section class="container-fluid logforme">
          <div class="container logforms d-flex ">
              <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="logform">
                  <div class="row  pt-4 pb-2 pl-3">
                      <div class="col-1"><i class="fas fa-lightbulb" style="color:yellow"></i></div>
                      <div  class="col-7" style="color: white"><h6>coal mine monitor</h6></div> 
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-10 ml-4">
                      <input type="text" class="form-control" id="inputname" placeholder="User name" name="uname" required value="<?php if(isset($_COOKIE["member_login"])) { echo (trim($_COOKIE["member_login"]));}else{ if(isset($username)){echo($username);} }?>"> 
                    </div>
                    <span class="error_form alert alert-danger m-0 ml-5 mt-1 p-0 pl-3 pr-5" id="username_error_message" ></span>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-10 ml-4">
                      <input type="password" class="form-control" id="inputpass" placeholder="Password" name="upass" required value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } else{ if(isset($password)){echo($password);} } ?>">
                    </div>
                    <span class="error_form alert alert-danger m-0 ml-5 mt-1 p-0 pl-3 pr-5" id="password_error_message" ></span>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-10 ml-4">
                      <button type="submit" class="btn btn-light btn-block" name="sub">Sign in</button>
                      <!-- <a href="index.php" class="btn btn-light btn-block">Sign in </a> -->
                    </div>
                    <span class=" alert alert-danger m-0 ml-5 mt-1 p-0 pl-2"> 
                      <?php if (isset($username_err )|| isset($password_err))
                      {
                        echo ($username_err);
                        echo ($password_err);
                      } ?> 
                  </span>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-10 ml-4">
                      <div class="form-check">
                        <input class="form-check-input" name="remember" type="checkbox" id="gridCheck1"  <?php if(isset($_COOKIE["member_login"])) { echo 'checked'; }else{echo 'unchecked';} ?>>
                        <label class="form-check-label" for="gridCheck1" style="color: white;" >
                          Remember me.
                        </label>
                      </div>
                    </div>
                  </div>
                </form>


          </div>
      </section>    
      
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  </body>
</html>