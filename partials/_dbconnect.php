<?php
$servername = trim(getenv('DB_HOST') ?: "gateway01.ap-southeast-1.prod.aws.tidbcloud.com");
$username   = trim(getenv('DB_USER') ?: "U3wDiEB9GevZ3oK.root");
$password   = trim(getenv('DB_PASS') ?: "");
$database   = trim(getenv('DB_NAME') ?: "test");
$port       = (int)(getenv('DB_PORT') ?: 4000);

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!@mysqli_real_connect($conn, $servername, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {
    $conn = mysqli_connect($servername, $username, $password, $database, $port);
}

if (!$conn) {
    die("Database Connection failed: " . mysqli_connect_error());
}
?>
