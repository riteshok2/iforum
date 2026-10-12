<?php
// Script to connect to the database (supports local and cloud environments like TiDB/Vercel)
$servername = getenv('DB_HOST') ?: "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$username   = getenv('DB_USER') ?: "U3wDiEB9GevZ3oK.root";
$password   = getenv('DB_PASS') ?: ""; // fallback
$database   = getenv('DB_NAME') ?: "test";
$port       = getenv('DB_PORT') ?: 4000;
// Initialize mysqli with SSL connection for TiDB Cloud
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
if (!@mysqli_real_connect($conn, $servername, $username, $password, $database, (int)$port, NULL, MYSQLI_CLIENT_SSL)) {
    // Fallback standard connection
    $conn = mysqli_connect($servername, $username, $password, $database, (int)$port);
}
if (!$conn) {
    die("Database Connection failed: " . mysqli_connect_error());
}
?>
