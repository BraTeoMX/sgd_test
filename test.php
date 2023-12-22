<?php
$serverName = "128.150.102.62"; //serverName\instanceName
$connectionInfo = array( "Database"=>"INTIMARKDBAXPRODVAL", "UID"=>"taxis", "PWD"=>"1nt1mArk");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
    $query = "SELECT ";
	$query .= "MIN(PURCHLINE.PURCHID) AS OC ";
	$query .= ", MIN(PURCHLINE.CURRENCYCODE) AS DIVISA ";
	$query .= ", (SELECT SUM(PURCHLINE.LINEAMOUNT)) AS IMPORTE ";
	$query .= "FROM PURCHLINE ";
	$query .= "INNER JOIN PURCHTABLE ";
	$query .= "ON PURCHTABLE.PURCHID ";
	$query .= "= PURCHLINE.PURCHID ";
	$query .= "WHERE PURCHTABLE.PURCHID = '0049286'";
	
	$execute = sqlsrv_query($conn, $query);
	print_r(sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC));

}else{
    echo "Conexión no se pudo establecer.<br />";
    die( print_r( sqlsrv_errors(), true));
}
?>