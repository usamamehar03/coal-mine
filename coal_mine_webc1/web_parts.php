<?php
// require('configuration_files/thconfig.php');

function logoute()
{
  session_start();
  // Unset all of the session variables
  $_SESSION = array();
  // Destroy the session.
  session_destroy();
  header("location:http://localhost/coal_mine_webc1/logins/login.php");
}
if (isset($_GET['out']))
{
  logoute();
}
function chklog()
{
  session_start();
  if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true)
  {
    header("location:http://localhost/coal_mine_webc1/logins/login.php");
  }
}

function connection()
{
  define('DB_SERVER', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '');
  define('DB_NAME', 'minedata');
  /* Attempt to connect to MySQL database */
  $link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
  // Check connection
  if($link === false)
  {
      die("ERROR: Could not connect. " . $mysqli->connect_error);
      exit();
  }
  return $link;
}
function insertdata($ste,$shu,$sme,$shy,$sca,$ssm,$sdu,$sdate)
{
  $link=connection();
  $sql = "INSERT INTO data1 (temperature, humidity, methan, hydrogen, carbonmono, smoke, dust, date)
  VALUES (?,?,?,?,?,?,?,?)";  
  if($stmt = mysqli_prepare($link, $sql))
  {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "iiiiiiis",$ste,$shu,$sme,$shy,$sca,$ssm,$sdu,$sdate);
    // // Set parameters
    // $param_username = $username;
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {
      // Store result
      // mysqli_stmt_store_result($stmt);
      // // Check if username exists, if yes then verify password
      // if(mysqli_stmt_num_rows($stmt) >0)
      // {
      //   // Bind result variables
      //   mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password,$email,$pic);
      //   if(mysqli_stmt_fetch($stmt))
      //   {
      //     if($password==$hashed_password)//password_verify($password, $hashed_password)
      //     {
      //     }
      //   }
      // }
    }
  }
}
function readdata()
{
  //$date=date("dM,Y");
                // $date=date_format($date,"dM,Y H:i:a");
  $tempe=array();$humi=array();$meth=array();$hydro=array();$smo=array();$carbonmo=array();$dus=array();$dummy=array(); 
  $link=connection();
  $sql = "SELECT * FROM data1";  
      if($stmt = mysqli_prepare($link, $sql))
      {
        // mysqli_stmt_bind_param($stmt, "s", $param_username);
        // $param_username =$_POST['name'];
        if(mysqli_stmt_execute($stmt))
        {
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt) >0)
          {
            mysqli_stmt_bind_result($stmt,$id,$te,$hu,$me,$hy,$ca,$sm,$du,$date);
              while (mysqli_stmt_fetch($stmt)) 
              {
                //$date=date('dM, Y', strtotime($date));
                $date=date_create($date);
                $date=date_format($date,"dM,Y H:i:a");
                if ($te!=0)
                {
                  $temp= array( "label" => $date, "y"=>$te );
                  array_push($tempe, $temp);
                }
                if ($hu!=0) 
                {
                  $temp= array( "label" => $date, "y"=> $hu);
                  array_push($humi, $temp);
                }
                if ($me!=0) 
                {
                  $temp= array( "label" => $date, "y"=> $me);
                  array_push($hydro, $temp);
                }
                if ($hy!=0) 
                {
                  $temp= array( "label" => $date, "y"=> $hy);
                  array_push($meth, $temp);
                }
                if ($ca!=0)
                {
                  $temp= array( "label" => $date, "y"=> $ca);
                  array_push($carbonmo, $temp);
                }
                if ($sm!=0)
                {
                  $temp= array( "label" => $date, "y"=> $sm);
                  array_push($smo, $temp);
                }
                if ($du!=0)
                {
                  $temp= array( "label" => $date, "y"=> $du);
                  array_push($dus, $temp);
                }
              }
              mysqli_close($link);
              $temp1= array( "label" => 0, "y"=> 0);
              array_push($dummy, $temp1);
              return array('temperature'=>$tempe,'humidity'=>$humi,'hydrogen'=>$hydro,'methan'=>$meth,'carbonmono'=>$carbonmo,'smoke'=>$smo,'dust'=>$dus,'dum'=>$dummy);
          } 
          else
          {
            // Display an error message if username doesn't exist
            echo json_encode("stil no recorde found");
          }
        } 
        else
        {
          echo json_encode("Oops! Something wrong in server.try again.");
        }
      }  
      // Close statement
      // mysqli_stmt_close($stmt);
      // Close connection
    mysqli_close($link);
}

function web_header()
{
  echo ('<section class="header">
      <nav class="navbar navbar-expand-sm navbar-light p-0">
          <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarToggler">
            <span class="navbar-toggler-icon"></span>
          </button>
          <i class="navbar-brand col-4 pl-4 p-1" href="#"><img src="http://localhost/coal_mine_webc1/slider/logotest.png"></i>
          <div class="collapse navbar-collapse col-8" id="navbarToggler">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
              <li class="nav-item active  mr-5 mt-2 mt-sm-0">
                <a class="nav-link" href="http://localhost/coal_mine_webc1/index.php">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item mt-2 mt-sm-0">
                <a class="nav-link" href="http://localhost/coal_mine_webc1/about.php">About</i></a>
              </li>
            </ul>
          </div>
        </nav>
  </section>');
}
function setup()
{
  echo ('
   <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/876e7a43f2.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>');
}
function web_sidebar($page)
{
  $pice=$_SESSION['pic'];
  $na= $_SESSION["username"];
  $em=$_SESSION["email"];
  if($page=="dasboard.php"){$page1="activ";}else {$page1="notactive";}
  ($page=="database_stats.php"? $page2="activ" : $page2="notactive");
  ($page=="port_mangment.php"? $page3="activ" : $page3="notactive");
  ($page=="manual_search.php"? $page4="activ" : $page4="notactive");
  ($page=="edit_profile.php"? $page5="activ" : $page5="notactive"); 
  echo ('
              <section class="col-md-3 sidebar float-left p-0">
                <div class="d-flex flex-row profilebox pt-3 pb-2">
                  <div class="ml-3"><img src="http://localhost/coal_mine_webc1/editprofile/userpics/'.$pice.'" alt="pic unavail"></div>
                  <div class="pl-1 row">
                    <h4 class="col-9 text-truncate">'.$na.'</h4>
                    <span class="col-9 text-truncate">' .$em.'</span>
                  </div>
                </div>
                                        <!-- sidebar -->
                <nav class="navbar navbar-light nav-tabs p-0" >
                    <ul class="navbar-nav col-12 p-0 pt-2">
                        <li class="nav-item ">
                            <a class="nav-link '.  $page1. '" href="http://localhost/coal_mine_webc1/dasboard.php">
                              <i class="far fa-chart-bar col-1"></i>
                              <span class="col-10">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '.  $page2. ' " href="http://localhost/coal_mine_webc1/databasegraph-config/database_stats.php">
                              <i class="fas fa-cogs col-1"></i>
                              <span class="col-10">Database Statistics</span>
                            </a>
                        </li>
                                                  <!-- subbar -->
                        <li class="nav-item">
                            <a class="nav-link '.  $page3. '" href="http://localhost/coal_mine_webc1/portmanagment/port_mangment.php">
                              <i class="fas fa-book col-1"></i>
                              <span class="col-10">Port Managment</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '.  $page4. '" href="http://localhost/coal_mine_webc1/manual_search.php">
                              <i class="fas fa-user  col-1"></i>
                              <span class="col-10">Manual Search</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link '.  $page5. '" href="http://localhost/coal_mine_webc1/editprofile/edit_profile.php">
                              <i class="fas fa-user col-1"></i>
                              <span class="col-10">Profile Edit</span>
                            </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="?out=true">
                            <i class="fas fa-sign-out-alt col-1"></i>
                            <span class="col-10">Logout</span>
                          </a>
                        </li>
                    </ul>
                </nav>
                <div class="extra"> </div>
              </section>');
} 
?>
