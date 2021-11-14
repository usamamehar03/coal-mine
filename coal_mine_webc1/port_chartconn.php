<?php
// if(!isset($_SERVER['HTTP_REFERER'])){
//     // redirect them to your desired location
//     echo "<h6>ERROR 403</h6>";
//     echo "You don't have permission to access the requested directory. There is either no index document or the directory is read-protected";
//     exit();
// }
// $path=dirname(__FILE__) . '/test.py';
// 	$result=shell_exec('"'.$path.'" 9600');
// 	$result=json_decode($result);
// 	if ($result[1]=="nodata") 
// 	{
// 		echo "failed";
// 		exit();
// 	}
// 	else{
// 		$result=json_encode($result);
// 	echo $result;
// 	exit();
// 	}
include ('web_parts.php');
if (isset($_POST['ctime'])) 
{
	date_default_timezone_set('Asia/Karachi');
	$ar= array('no',0,date('dM,Y H:i:s:a'));
	$path=dirname(__FILE__) . '/port_config.py';
	$result=shell_exec('"'.$path.'" 9600');
	$result=json_decode($result);
	$temp=$result;
	if ($temp[0]=="true" && $temp[1]!=0) 
	{
		if (time()-$_POST['ctime']>50) 
		{
			$ar[0]="yes";
			date_default_timezone_set('Asia/Karachi');
			$ar[1]=time();
			$date=date('d-m-y H:i:s');
			//insertdata(1,2,3,4,5,6,7,$date);
			insertdata($result[1][0],$result[1][1],$result[1][2],$result[1][3],$result[1][4],$result[1][5],$result[1][6],$date);
			array_push($ar, $result);
			echo json_encode($ar);
			exit();
		}
	}
	array_push($ar, $result);
	echo json_encode($ar);
}
else
{
	$path=dirname(__FILE__) . '/port_config.py';
	$result=shell_exec('"'.$path.'" 9600');
	echo $result;
}
?>