<?php
	phpinfo();die;
	header("Content-Type: text/html;charset=utf-8");
	try {
		$cdn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.8.246)(PORT = 1521))(CONNECT_DATA =(SERVICE_NAME=ORCL)))";
		$conn = oci_connect("bosnds3","abc123","(DEscriptION=(ADDRESS=(PROTOCOL =TCP)(HOST=192.168.8.246)(PORT = 1521))(CONNECT_DATA =(SID=ORCL)))",'utf8');
		var_dump(conn);
	}catch(PDOException $e){
		echo "ODBC异常: ".$e->getMessage();
	}
?>