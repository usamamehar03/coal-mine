<?php
session_start();
if (isset($_POST['validateconn'])) 
{
  $result=shell_exec("conn.py 9600");
  $result=json_decode($result);
  // $temp="port on successfully";
  // array_splice($result,1,1,$temp);
  echo json_encode($result);
}
if (isset($_POST['sesion'])) 
{
    if(isset($_SESSION['portstatus'])&& $_SESSION['portstatus']=="on")
    {
      $ar=array("on","Data port already on");
      echo json_encode($ar);
    }
    else
    {
        $_SESSION['portstatus']="on";
        $result=shell_exec("conn.py 9600");
        $result=json_decode($result);
        $temp="port on successfully";
        array_splice($result,1,1,$temp);
        echo json_encode($result);
    }
}

if (isset($_POST['sesion1'])) 
{
    if(isset($_SESSION['portstatus'])&& $_SESSION['portstatus']=="off")
    {
        $ar=array("of","Data port already off");
      echo json_encode($ar);
    }
    else
    {
        $_SESSION['portstatus']="off";
        $result=array("0","port off successfully");
        echo json_encode($result);
    }
}


if (isset($_POST['name']) )
{
    if ($_POST['name']=="chose")
    {
        echo json_encode("select anything to search");
    }
    else
    {
        $result='<div class="table-responsive pre-scrollable">
        <table class="table table-hover table-bordered table-striped">
            <caption>List of '.$_POST["name"].' logs</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Value</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>';

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
        $sql = "SELECT id , ".$_POST['name']." ,date FROM data1";  
      if($stmt = mysqli_prepare($link, $sql))
      {
        // mysqli_stmt_bind_param($stmt, "s", $param_username);
        // $param_username =$_POST['name'];
        if(mysqli_stmt_execute($stmt))
        {
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt) >0)
          {
            mysqli_stmt_bind_result($stmt, $id,$value,$date);
              while (mysqli_stmt_fetch($stmt)) 
              {
                $date=date_create($date);
                $date=date_format($date,"d M,Y -- H:i:s:a");
                  $result.='<tr>
                    <th scope="row">'.$id.'</th>
                    <td>'.$_POST['name'].'</td>
                    <td>'.$value.'</td>
                    <td>'.$date.'</td>
                </tr>';
              }
              $result.='</tbody></table></div>';
              echo json_encode($result);
          } 
          else
          {
            // Display an error message if username doesn't exist
            echo json_encode("stil no recorde found");
            exit();
          }
        } 
        else
        {
          echo json_encode("Oops! Something wrong in server.try again.");
          exit();
        }
      }  
      // Close statement
      // mysqli_stmt_close($stmt);
      // Close connection
    mysqli_close($link);
    }
        // echo $_POST['name'];
    // }
}
?>