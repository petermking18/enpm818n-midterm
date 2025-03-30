<?php
$capath = '/etc/pki/global-bundle.pem';
$servername = getenv('DB_SERVER_NAME');
$username = getenv('DB_USER_NAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

$con = new mysqli($servername, $username, $password);
$con->ssl_set(null, null, $capath, null, null);
$con->real_connect($servername, $username, $password);	

if(!$con){
    die(mysqli_error($con));
}

// Check if db schema already exists
$db_exists = false;
$result = $con->query("show databases;");
while ($row = $result->fetch_assoc()) {
    if ($row['Database'] == $dbname) {
        $db_exists = true;
		break;
    }
}

// If db doesn't exist, create it
if (!$db_exists) {
	$con->multi_query("create database $dbname; use $dbname;");

	do {
		$result = $con->store_result();
		if ($result) {
			$result->free_result();
		}
	} while ($con->next_result());
	
	$sql = file_get_contents("/var/www/html/Database/ecommerce_1.sql");
	
	$success = $con->multi_query($sql);

	do {
		$result = $con->store_result();
		if ($result) {
			$result->free_result();
		}
	} while ($con->next_result());
	
	if (!$success) {
		echo "Failed sql script execution";
		die("Failed sql script execution");
	}

	$con->close();
}


$con = new mysqli($servername, $username, $password, $dbname);
$con->ssl_set(null, null, $capath, null, null);
$con->real_connect($servername, $username, $password, $dbname);	
if (!$con) {
	die(mysqli_error($con));
}

?>
