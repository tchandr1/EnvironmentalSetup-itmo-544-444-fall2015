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
$link = mysqli_connect($endpoint,"controller","ilovebunnies","customerrecords") or die("Error " . mysqli_error($link));
echo "Here is the result: " . $link;
$sql = "CREATE TABLE IF NOT EXISTS items  
(
    id INT NOT NULL AUTO_INCREMENT,
    uname VARCHAR(20) NOT NULL,
    email VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    s3rawurl VARCHAR(256) NOT NULL,
    s3finishedurl VARCHAR(256) NOT NULL,
    jpgfilename VARCHAR(255) NOT NULL,
    state TINYINT(3) NOT NULL,
    PRIMARY KEY(id)
)";
$create_tbl = $link->query($sql);
if ($sql) {
        echo "Table is created or No error returned.";
}
else {
        echo "error!!";
}
$link->close();

?>

