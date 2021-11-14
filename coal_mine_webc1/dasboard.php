<?php
require('configuration_files/thconfig.php');
require('configuration_files/fgases_config.php');
require('configuration_files/hgases_config.php');
include ('web_parts.php');
chklog();
if ($_SESSION["emailstatus"]!="verified") 
{
  $_SESSION["updatemsg"]="you didnt verify email your data maybe discard soon and we will not able to update it";
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
<html>
  <head>
    <?php setup(); ?>
    <!-- manual style sheet     -->
    <!-- <link rel="stylesheet" href="stylesheet.css" type="text/css"> -->
    <link rel="stylesheet" href="styles.css" type="text/css">
    <script type="text/javascript" language="javascript" src="logins/jquery.js"></script>
    <title><?php echo $_SESSION['username']; ?></title>
    <script type="text/javascript">
        function timedMsg()
        {
            var t=setTimeout("document.getElementById('msg').style.display='none';",4000);
        }
        function fetchdata(pretime)
        {
           $.ajax({
            url: 'port_chartconn.php',
           data: {ctime:pretime},
            dataType: "json",
            type: 'post',
            success: function(data){
              //alert(data)
              if(data[3][0]=="false")
              {
                alert(data[3][1]);//connect arduino
              }
              else
              {
                if (data[3][1]==0) 
                {
                  alert("problem in hc12");
                  return;
                }

                if (data[3][0]=="true" && data[3][1].length<7) 
                {
                  alert( 'some sensors not connected to drone check your circuit');
                  return;
                }
                else
                {
                  // if (data[3][1]) 
                  // {
                  //   alert("problem in hc12");
                  //   return;
                  // }
                    for (var i=0 ; i <data[3][1].length; i++) 
                    {
                      if(data[3][1][i]=="00-999.00" || data[3][1][i]=="-9999")
                      {
                        alert('some sensors not gaving correct readings');
                        return;
                      }
                    }
                    var ar=["Temperature","Humidity"];
                    var color;
                    var dps = chart.options.data[0].dataPoints;
                    var ar1=["carbon mono","smoke","dust strength"];
                    var dps1 = hzchart.options.data[0].dataPoints;
                    var ar2=["methan","hydrogen"];
                    var dps2 = fgchart.options.data[0].dataPoints;
                    var ses= data[3];
                    var j=0;
                    for (var i = 0; i< 2 ; i++ )
                    {
                      var pval=parseInt(ses[1][j], 10);   //
                      color=pval> 75 ? "#FF2500" : pval >=50 ? "#FF6000" : pval<50 ? "#41CF35" : null;
                      dps[i] = {label: ar[i], y: pval, color: color};
                      j++;
                    }
                    for (var i = 0; i< 3 ; i++ )
                    {
                      var pval=parseInt(ses[1][j], 10);   //
                      color=pval> 75 ? "#FF2500" : pval >=50 ? "#FF6000" : pval<50 ? "#41CF35" : null;
                      //alert(pval);
                      dps1[i] = {label: ar1[i], y: pval, color: color};
                      j++;
                    }
                    for (var i = 0; i< 2 ; i++ )
                    {
                      var pval=parseInt(ses[1][j], 10);  
                      color=pval> 75 ? "#FF2500" : pval >=50 ? "#FF6000" : pval<50 ? "#41CF35" : null;
                      //alert(pval);
                      dps2[i] = {label: ar2[i], y: pval, color: color};
                      j++;
                    }
                    document.getElementById('tval').innerHTML = ses[1][0];
                    document.getElementById('hval').innerHTML = ses[1][1];
                    document.getElementById('cval').innerHTML = ses[1][2];
                    document.getElementById('sval').innerHTML = ses[1][3];
                    document.getElementById('dval').innerHTML = ses[1][4];
                    document.getElementById('mval').innerHTML = ses[1][5];
                    document.getElementById('hival').innerHTML = ses[1][6];
                    //document.getElementById('logss').innerHTML = ses[1][6];
                    var x=document.getElementsByClassName('log');
                    x[0].innerHTML =data[2];
                    x[1].innerHTML =data[2];
                    x[2].innerHTML =data[2];
                    x[3].innerHTML =data[2];
                    if (ses[1][7]<=48) 
                    {
                       var str1 = " danger drone will hit! ";
                      var str2 = ses[1][7];
                      var str3 = " cm ahead rocks";
                      var res = str1.concat(str2,str3);
                      document.getElementById('hurt').innerHTML = res;
                      if (document.getElementById("hurdle").classList.contains("alert-success"))
                      {
                        document.getElementById("hurdle").classList.add("alert-danger");
                        document.getElementById("hurdle").classList.remove("alert-success");
                        document.getElementById("huric").classList.add("fa-exclamation-triangle");
                        document.getElementById("huric").classList.remove("fa-user-shield");
                        document.getElementById("huric").style.color="rgb(133, 100, 4)";
                      }
                    }
                    else
                    {
                      document.getElementById('hurt').innerHTML = "still drone in safe zone";
                      if (document.getElementById("hurdle").classList.contains("alert-danger"))
                      {
                        document.getElementById("hurdle").classList.add("alert-success");
                        document.getElementById("hurdle").classList.remove("alert-danger");
                        document.getElementById("huric").classList.add("fa-user-shield");
                        document.getElementById("huric").classList.remove("fa-exclamation-triangle");
                        document.getElementById("huric").style.color="rgb(21, 87, 36)";
                      }
                    }
                    // <caption class="log">Last undated at '.date('dM,Y H:i:a').'</caption>
                    chart.options.data[0].dataPoints = dps;
                    chart.render();
                    hzchart.options.data[0].dataPoints = dps1;
                    hzchart.render();
                    fgchart.options.data[0].dataPoints = dps2;
                    fgchart.render();
                    if (data[0]=="yes")
                    {
                      pretime=data[1];
                      alert("value saved in database");
                     // insertdata(ses[1][0],ses[1][1],ses[1][2],ses[1][3],ses[1][4],ses[1][5],ses[1][6],pretime);
                    }
                }
              }
            },
            complete:function(data){
              fetchpredict();
            setTimeout(fetchdata,5000,pretime);
            }
           });
        }
      function fetchpredict()
      {
         $.ajax({
             url: 'predict.php',
             data: 'predi=',
            dataType: "json",
            type: 'post',
            success: function(res){
              document.getElementById('expt').innerHTML = res[1];
              if (res[0]==2) 
              {
                if (document.getElementById("explosition").classList.contains("alert-success"))
                {
                  document.getElementById("explosition").classList.add("alert-warning");
                  document.getElementById("explosition").classList.remove("alert-success");
                  document.getElementById("expic").classList.add("fa-exclamation-circle");
                  document.getElementById("expic").classList.remove("fa-user-shield");
                  document.getElementById("expic").style.color="rgb(133, 100, 4)";
                }
              }
              else
              {
                if (document.getElementById("explosition").classList.contains("alert-warning"))
                {
                  document.getElementById("explosition").classList.add("alert-success");
                  document.getElementById("explosition").classList.remove("alert-warning");
                  document.getElementById("expic").classList.add("fa-user-shield");
                  document.getElementById("expic").classList.remove("fa-exclamation-circle");
                  document.getElementById("expic").style.color="rgb(21, 87, 36)";
                }
              }
            }
            ,
            complete:function(res){
            //setTimeout(fetchpredict,2000);
            }
         });
      }
    </script>
  </head> 
  <body onload="timedMsg()S">
      <section class="jumbotron-fluid">
          <!-- nave bar   -->
         <?php  web_header(); ?> 
          <!--  other content-->
          <section class="main">
              <!--  side bar     -->
              <?php
                $page="dasboard.php";
                web_sidebar($page);
              ?>
                <!--  charts    -->
              <section class="col-sm-9 data float-right p-3">
                <div class="temp m-4 row p-4">
                  <?php if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
                  {
                    date_default_timezone_set('Asia/Karachi');
                   echo '
                    <div class="col-lg-6 mt-2 mt-lg-0">
                      <div class="col-12 btitle"> <span style"color:black;">Hurdle Distance</span></div>
                      <div id="hurdle" class="alert alert-success"> 
                        <i id="huric" class="fas fa-user-shield" style="color:rgb(21, 87, 36);"></i>
                        <span id="hurt">  Still drone in safe zone  <span>
                      </div>
                      <div class="table-responsive bg-light">
                        <table class="table table-hover table-bordered ">
                          <caption >Last updated at <span class="log">'.date('dM,Y H:i:s:a').'</span></caption>
                        </table>
                      </div>
                    </div>
                    <div class="col-lg-6 mt-2 mt-lg-0">
                      <div class="col-12 btitle"> <span style"color:black;">Explosition Prediction</span></div>
                      <div id="explosition" class="alert alert-success">
                        <i id="expic" class="fas fa-user-shield" style="color:rgb(21, 87, 36);"></i>
                        <span id="expt">internal environment of coal mine  is safe</span>
                      </div>
                    </div>';
                     // echo '<script type="text/javascript">
                     //        $(document).ready(function(){
                     //            fetchpredict();
                     //          });
                     //        </script>';
                  }
                  //setTimeout(fetchdata,2000);
                  else
                  {
                    echo '
                    <div class="alert alert-warning">live stats disabled until u enable data port</div>';
                  }
                  ?>
                </div>
                <!-- temp & humidity -->
                <div class="temp m-4 row p-4">
                  <?php if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
                  {
                    date_default_timezone_set('Asia/Karachi');
                   echo '
                    <div class="col-12 btitle"> <span>Temperature and Humidity</span></div>
                    <div class="col-lg-6 pt-0 pt-lg-5">
                      <div class="table-responsive bg-light">
                        <table class="table table-hover table-bordered ">
                        <caption >Last undated at  <span class="log">'.date('dM,Y H:i:s:a').'</span></caption>
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">DATA</th>
                              <th scope="col">VALUE</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">1</th>
                              <td>Temperature</td>
                              <td id="tval">NULL</td>
                            </tr>
                            <tr>
                              <th scope="row">2</th>
                              <td>Humidity</td>
                              <td id="hval">NULL</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-lg-6 mt-2 mt-lg-0">
                      <div id="thdiv" style="height: 350px;"></div>
                    </div>';
                    $tim=time();
                     echo '<script type="text/javascript">
                            $(document).ready(function(){
                                var chart=charts();
                                var chart1=hzgraph();
                                var chart2=fggraph();
                                chart.render();
                                chart1.render();
                                chart2.render();
                                var pretime='.$tim.';
                                fetchdata(pretime);
                              });
                            </script>';
                  }
                  //setTimeout(fetchdata,2000);
                  else
                  {
                    echo '
                    <div class="alert alert-warning">live stats disabled until u enable data port</div>';
                  }
                  ?>
                </div>
                <!--  gases -->

                <div class="hazar  m-4 p-4 row">
                   <?php if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
                  {
                    date_default_timezone_set('Asia/Karachi');
                   echo '
                  <div class="col-12 btitle"> <span>Flameable Gases</span></div>
                  <div class="col-lg-6 pt-0 pt-lg-5">
                    <div class="table-responsive bg-light">
                      <table class="table table-hover table-bordered ">
                      <caption >Last undated at <span class="log">'.date('dM,Y H:i:s:a').'</span></caption>
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">DATA</th>
                            <th scope="col">VALUE</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Carbon mono</td>
                            <td id="cval">NULL</td>
                          </tr>
                          <tr>
                            <th scope="row">2</th>
                            <td>smoke</td>
                            <td id="sval">NULL</td>
                          </tr>
                          <tr>
                            <th scope="row">3</th>
                            <td>Dust particals</td>
                            <td id="dval">NULL</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-lg-6 mt-2 mt-lg-0">
                    <div id="hazarchart" style="height: 350px;"></div>
                  </div>';
                  }
                  else
                  {
                    echo '
                    <div class="alert alert-warning">live stats disabled until u enable data port</div>';
                  }
                  ?>
                </div>

                <div class="flam  m-4 p-4 row">
                  <?php if (isset($_SESSION['portstatus']) &&$_SESSION['portstatus']=='on') 
                  {
                    date_default_timezone_set('Asia/Karachi');
                   echo '
                  <div class="col-12 btitle"> <span>Flameable Gases</span></div>
                  <div class="col-lg-6 pt-0 pt-lg-5">
                    <div class="table-responsive bg-light">
                      <table class="table table-hover table-bordered ">
                      <caption >Last undated at <span class="log">'.date('dM,Y H:i:s:a').'</span></caption>
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">DATA</th>
                            <th scope="col">VALUE</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Methan</td>
                            <td id="mval">NULL</td>
                          </tr>
                          <tr>
                            <th scope="row">2</th>
                            <td>Hydrogen</td>
                            <td id="hival">NULL</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-lg-6 mt-2 mt-lg-0">
                    <div id="flamechart" style="height: 350px;"></div>
                  </div>';
                  }
                  else
                  {
                    echo '
                    <div class="alert alert-warning">live stats disabled until u enable data port</div>';
                  }
                  ?>
                </div>
              </section>
          </section>
      </section>
  </body>
</html>