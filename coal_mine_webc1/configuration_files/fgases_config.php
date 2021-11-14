<?php
 
$fgdata = array(
  array("label"=> "Methan", "y"=> 1),
  array("label"=> "Hygrogen", "y"=> 1)
); 
?>
<!DOCTYPE HTML>
<html>
  <head>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
      var fgchart;
      function fggraph()
      { 
        fgchart = new CanvasJS.Chart("flamechart", {
          title: {
            text: "Flameable Gases",
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
            maximum: 1000,
            suffix: "ppm"
          },
          axisX: {
            labelAngle: -30
          },
          data: [{
            type: "column",
            connectNullData: true,
            showInLegend: true,
            legendText: "normal state",
            legendMarkerColor: "green",
            yValueFormatString: "#,##0.00\"ppm\"",
            indexLabel: "{y}",
            dataPoints: <?php echo json_encode($fgdata, JSON_NUMERIC_CHECK); ?>
          }]
        });
        return fgchart;
      }
    </script>
    </head>
    <body>
    </body>
</html>     