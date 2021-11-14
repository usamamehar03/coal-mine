<?php
ob_start(); 
include '../web_parts.php';
$i=0;$j=0;
$msge1="";$msge2="";
$data=array();
$data=readdata();
foreach ($data as $key => $value) 
{
	// print_r( $value);
	if (empty($value)) 
	{
		if ($key=="temperature" || $key=="humidity") 
		{
			$msge1.='<div class="alert alert-warning alert-dismissible fade show" role="alert">
					  0 record for <strong>'.$key.$data["temperature"][0]["label"].'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
			$i++;
		}
		else
		{
			$msge2.='<div class="alert alert-warning alert-dismissible fade show" role="alert">
					  0 record for <strong>'.$key.'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			$j++;
		}
	}
}
ob_flush();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<style type="text/css">
			.canvasjs-chart-credit{
				display: none;
			}
		</style>
		<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		<!-- <script type="text/javascript" language="javascript" src="../logins/jquery.js"></script> -->
		<script>
			var baseggraph;
			function database_gases()
			{

				baseggraph = new CanvasJS.Chart("basegchart", {
				// backgroundColor: "#a3b0d2",
				theme:"light2",
				animationEnabled: true,
				title:{
					text: "All Gases concentration ",
					fontColor: "#2f4f4f",
			        fontSize: 25,
			        padding: 10,
			        margin: 15,
			        backgroundColor: "#FFFFE0",
			        borderThickness: 1,
			        cornerRadius: 5,
			        fontWeight: "lighter"
				},
				axisX:{
					//labelAngle: -20,
					labelFontColor: "#6c757d",
					labelFontSize: 12
				},
				axisY :{
					includeZero: false,
					title: "gas quantity",
					suffix: "ppm",
					labelFontColor: "#6c757d",
					labelFontSize: 12,
					titleFontColor: "#ced4da",
					// ,minimum: -200,
     //   				maximum: 300 
				},
				toolTip: {
					shared: "true"
				},
				legend:{
					cursor:"pointer",
					itemclick : toggleDataSeries
				}
				,
				data: [
				{
					type: "splineArea",
					visible: true,
					connectNullData:true,
       	 			nullDataLineDashType:"dot",
					showInLegend: true,
					yValueFormatString: "##.00ppm",
					name: "Hydrogen",
					dataPoints:<?php if (empty($data["hydrogen"])) {echo json_encode($data["dum"],JSON_NUMERIC_CHECK);} else{ echo json_encode($data["hydrogen"], JSON_NUMERIC_CHECK);} ?>
				},
				{
					type: "splineArea", 
					connectNullData:true,
       	 			nullDataLineDashType:"dot",
					showInLegend: true,
					visible: true,
					yValueFormatString: "##.00ppm",
					name: "Methan",
					dataPoints: <?php if (empty($data['methan'])) {echo json_encode($data['dum'],JSON_NUMERIC_CHECK);} else{  echo json_encode($data['methan'], JSON_NUMERIC_CHECK);}?>
				},
				{
					type: "splineArea",
					connectNullData:true,
       	 			nullDataLineDashType:"dot",
					visible: false,
					showInLegend: true,
					yValueFormatString: "##.00ppm",
					name: "carbon-mono",
					dataPoints:<?php if (empty($data['carbonmono'])) {echo json_encode($data['dum'],JSON_NUMERIC_CHECK);} else{  echo json_encode($data['carbonmono'], JSON_NUMERIC_CHECK);}?>
				},
				{
					type: "splineArea", 
					connectNullData:true,
       	 			nullDataLineDashType:"dot",
					showInLegend: true,
					visible: false,
					yValueFormatString: "##.00ppm",
					name: "Smoke",
					dataPoints:<?php if (empty($data['smoke'])) {echo json_encode($data['dum'],JSON_NUMERIC_CHECK);} else{  echo json_encode($data['smoke'], JSON_NUMERIC_CHECK);}?>
				},
				{
					type: "splineArea", 
					connectNullData:true,
					visible: false,
       	 			nullDataLineDashType:"dot",
					showInLegend: true,
					yValueFormatString: "##.00ppm",
					name: "dust_strength",
					dataPoints: <?php if (empty($data['dust'])) {echo json_encode($data['dum'],JSON_NUMERIC_CHECK);} else{  echo json_encode($data['dust'], JSON_NUMERIC_CHECK);}?>
				}
				// ,
				// {
				// 	type: "splineArea", 
				// 	showInLegend: true,
				// 	yValueFormatString: "##.00mn",
				// 	name: "Season 6",
				// 	dataPoints: [
				// 		{ label: "Ep. 1", y: 7.94 },
				// 		{ label: "Ep. 2", y: 7.29 },
				// 		{ label: "Ep. 3", y: 7.28 },
				// 		{ label: "Ep. 4", y: 7.82 },
				// 		{ label: "Ep. 5", y: 7.89 },
				// 		{ label: "Ep. 6",  y: 8.80 },
				// 		{ label: "Ep. 8", y: 6.71 },
				// 		{ label: "Ep. 7", y: 7.66 },
				// 		{ label: "Ep. 10",y: 7.60 },
				// 		{ label: "Ep. 9", y: 7.89 }
				// 	]
				// }
				//,
				// {
				// 	type: "splineArea", 
				// 	showInLegend: true,
				// 	yValueFormatString: "##.00mn",
				// 	name: "Season 7",
				// 	dataPoints: [
				// 		{ label: 1, y: 10.11 },
				// 		{ label: "Ep. 2", y: 9.27 },
				// 		{ label: "Ep. 3", y: 9.25 },
				// 		{ label: "Ep. 4", y: 10.17 },
				// 		{ label: "Ep. 5", y: 10.72 },
				// 		{ label: "Ep. 6", y: 10.24 },
				// 		{ label: "Ep. 7", y: 12.07 }
				// 	]
				// }
				]
				});
				baseggraph.render();
				function toggleDataSeries(e) {
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					 baseggraph.render();
				}
			}
			var basethgraph;
			function database_th()
			{
				basethgraph = new CanvasJS.Chart("basethchart", {
				theme:"light2",
				animationEnabled: true,
				title:{
					text: "Temprature and Humidity",
					fontColor: "#2f4f4f",
			        fontSize: 25,
			        padding: 10,
			        margin: 15,
			        backgroundColor: "#FFFFE0",
			        borderThickness: 1,
			        cornerRadius: 5,
			        fontWeight: "lighter" 
				},
				axisX :{
					//labelAngle: -20,
					labelFontColor: "#6c757d",
					labelFontSize: 12
				},
				axisY :{
					includeZero: false,
					title: "temp &humidity",
					//suffix: "ppm",
					labelFontColor: "#6c757d",
					labelFontSize: 12,
					titleFontColor: "#ced4da"
					// suffix: "ppm"
				},
				toolTip: {
					shared: "true"
				},
				legend:{
					cursor:"pointer",
					itemclick : toggleDataSeries
				},
				data: [{
					type: "splineArea",
					connectNullData:true,
       	 			nullDataLineDashType:"dot",
					visible: true,
					showInLegend: true,
					yValueFormatString: "##.00C",
					name: "Temprature",
					dataPoints:<?php if (empty($data['temperature'])) {echo json_encode($data['dum'],JSON_NUMERIC_CHECK);} else{echo json_encode($data['temperature'], JSON_NUMERIC_CHECK);}?>
				},
				{
					type: "splineArea", 
					showInLegend: true,
					visible: false,
					connectNullData:true,
       	 			nullDataLineDashType:"dot",
					yValueFormatString: "##.00",
					name: "Humidity",
					dataPoints:<?php if (empty($data['humidity'])) {echo json_encode($data['dum'],JSON_NUMERIC_CHECK);} else{echo json_encode($data['humidity'], JSON_NUMERIC_CHECK);$i++;}?>
				}]
				});
				basethgraph.render();
				function toggleDataSeries(e) {
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
						e.dataSeries.visible = false;
					} else {
						e.dataSeries.visible = true;
					}
					basethgraph.render();
				}
			}
		</script>
	</head>
	<body>
		<script type="text/javascript">
			 // $(document).ready(function(){
			 // 	alert('hy');
			 	// $('#aler1').html(<?php //echo $msge1; ?>);
                // var chart=charts();
                // var chart1=hzgraph();
                // var chart2=fggraph();
                // chart.render();
                // chart1.render();
                // chart2.render();
                // setTimeout(fetchdata,2000);
              // });
		</script>
	</body>
</html>