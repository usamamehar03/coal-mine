<?php
// if(!isset($_SERVER['HTTP_REFERER'])){
//     // redirect them to your desired location
//     echo "<h1>ERROR 403</h1>";
//     echo "You don't have permission to access the requested directory. There is either no index document or the directory is read-protected";
//     exit();
// }
$thdata = array(
  array("label"=> "Temperature", "y"=> 1),
  array("label"=> "Humidity", "y"=> 1)
); 
?>
<!DOCTYPE HTML>
<html>
  <head>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
      var chart ;
      function charts()
      {
        chart = new CanvasJS.Chart("thdiv", {
          title: {
            text: "Temperature and Humidity", 
        fontColor: "#2f4f4f",
        fontSize: 25,
        padding: 10,
        margin: 15,
        backgroundColor: "#FFFFE0",
        borderThickness: 1,
        cornerRadius: 5,
        fontWeight: "lighter"
          },
          dataPointWidth: 40,
          axisY: {
            minimum: 0,
            maximum: 150,
            suffix: "C"
          },
          axisX: {
            labelAngle: -30
          },
          data: [{
            type: "column",
            connectNullData: true,
            yValueFormatString: "#,##0.00\"\"",
            indexLabel: "{y}",
            dataPoints: <?php echo json_encode($thdata, JSON_NUMERIC_CHECK); ?>
          }]
        });
        return chart;
      }
    </script>
    </head>
    <body>
    </body>
</html>     