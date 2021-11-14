<?php
//echo json_encode("string");
if (isset($_POST['predi'])) 
{
	$resultp=shell_exec("complete_svm.py");
    echo $resultp;
}
?>