<?php


?>
<html>
<head><title>Introspection Page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h1>Introspection page to create database export feature</h1>
<a href='index.php'>HomePage</a>

<?php
//From :http://www.tutorialspoint.com/php/perform_mysql_backup_php.htm

$dbname = 'customerrecords';
$dbuser = 'controller';
$dbpass = 'ilovebunnies';

require 'vendor/autoload.php';

echo "describe DB Instances of tch-db\n==================";
$rdswest = new Aws\Rds\RdsClient([
	'version' => 'latest',
	'region' => 'us-west-2'
]);

$result = $rdswest->describeDBInstances(['DBInstanceIdentifier' => 'tch-db']);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

echo "Make a connection to MYSQL\n=====================";
$link = mysqli_connect($endpoint,"controller","ilovebunnies","customerrecords");

echo "connection made\n=======================";
//create directory for dbbackup
echo "Make Directory to store backed up database\n==================";

mkdir("/tmp/Backup");

$Bkpspath = '/tmp/Backup/';
$bname = uniqid("DBBackUp", false);
$append = $bname . '.' . sql;
$Path = $Bkpspath . $append;
$sql="mysqldump --user=$dbuser --password=$dbpass --host=$endpoint $dbname > $Path";
exec($sql);
$bucketname = uniqid("dbbackup", false);

echo "Creating s3 object\n==================";

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

echo "creating bucket\n==================";

# AWS PHP SDK version 3 create bucket
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucketname,
]);

echo "Put object inside bucket\n==================";

# PHP version 3
$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucketname,
   'Key' => $append,
'SourceFile' => $Path,
]);

echo "Life cycle configuration of bucket\n==================";

$objectrule = $s3->putBucketLifecycleConfiguration([
    'Bucket' => $bucketname,
    'LifecycleConfiguration' => [
        'Rules' => [ 
            [
                'Expiration' => [
                    'Days' => 1,
                ],
                'NoncurrentVersionExpiration' => [
                    'NoncurrentDays' => 1,
                ],
               
                'Prefix' => ' ',
                'Status' => 'Enabled',
                
            ],
            
        ],
    ],
]);
mysql_close($link);
echo "Database Backup created successful";

?>
</div>
</body>
</html>
