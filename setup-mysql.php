<?php
// Start the session^M
require 'vendor/autoload.php';
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

// Create a table 
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'tch-db',
]);

print_r($result['DBInstance'][0]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";
$link = mysqli_connect($endpoint,"controller","ilovebunnies","3306") or die("Error " . mysqli_error($link));
echo "Here is the result: " . $link;
$sql = "CREATE TABLE comments 
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
PosterName VARCHAR(32),
Title VARCHAR(32),
Content VARCHAR(500)
)";
$con->query($sql);
?>

