<?php
// Start the session
session_start();
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
?>
<html>
<head><title>Hello app</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h1>Page to upload files</h1>
<?php
$useremail= $_POST["useremail"];
$phone = $_POST["phone"];
?>

<h4>Submitted to Email ID:</h4>

<?php
echo $useremail;
?>

<h4>Phone Number:</h4>

<?php
echo $phone;
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
echo '<pre>';
echo $uploadfile;
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";
require 'vendor/autoload.php';
#use Aws\S3\S3Client;
#$client = S3Client::factory();
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$bucket = uniqid("php-tch-",false);
#$result = $client->createBucket(array(
#    'Bucket' => $bucket
#));
# AWS PHP SDK version 3 create bucket
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket
]);
#$client->waitUntilBucketExists(array('Bucket' => $bucket));
#Old PHP SDK version 2
#$key = $uploadfile;
#$result = $client->putObject(array(
#    'ACL' => 'public-read',
#    'Bucket' => $bucket,
#    'Key' => $key,
#    'SourceFile' => $uploadfile 
#));
# PHP version 3
$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'SourceFile'=> $uploadfile,
   'Key' => $uploadfile
]);

$url = $result['ObjectURL'];
echo $url;
echo "s3RauUrl ready";

//Implementing bordered Imagick

$imgImagick = new Imagick($uploadfile);
        $imgImagick->borderImage('#000000',20,10);
        mkdir("/tmp/dirImagickImage");
$extension = end(explode('.', $fname));

$path = '/tmp/dirImagickImage/';
$imgImagickId = uniqid("Id");
// concatenating name and type
$imgImagickType = $imgImagickId . '.' . $extension;
$imgImagickPath = $path . $imgImagickType;

$imgImagick->writeImage($imgImagickPath);
//creating bucket to upload framed image
$borderbucket = uniqid("borderimage",false);
echo $borderbucket;
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $borderbucket,
]);
$result = $s3->putObject([
    'ACL' => 'public-read',
'Bucket' => $borderbucket,
   'Key' => "flipped".$imgImagickType,
'SourceFile' => $imgImagickPath,
]);
$finishedimgImagickurl=$result['ObjectURL'];
echo "processed image uploaded to s3";


$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'tch-db',
    #'Filters' => [
    #    [
    #        'Name' => '<string>', // REQUIRED
    #        'Values' => ['<string>', ...], // REQUIRED
    #    ],
        // ...
   # ],
   # 'Marker' => '<string>',
   # 'MaxRecords' => <integer>,
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
    echo "\n============\n". $endpoint . "\n================\n";
//echo "begin database";^M
$link = mysqli_connect($endpoint,"controller","ilovebunnies","customerrecords") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/* Prepared statement, stage 1: prepare */
if (!($stmt = $link->prepare("INSERT INTO items (id,uname, email,phone,s3rawurl,s3finishedurl,jpgfilename,state) VALUES (NULL,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
$email = $_POST['useremail'];
$uname = "Thanu";
$phone = $_POST['phone'];
$s3rawurl = $url; //  $result['ObjectURL']; from above
$jpgfilename = basename($_FILES['userfile']['name']);
$s3finishedurl = $finishedimgImagickurl;
$state =1;

$stmt->bind_param("sssssii",$uname,$email,$phone,$s3rawurl,$s3finishedurl,$jpgfilename,$state);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
printf("%d Row inserted.\n", $stmt->affected_rows);
/* explicit close recommended */
$stmt->close();
$link->real_query("SELECT * FROM items");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . " " . $row['email']. " " . $row['phone'];
}


//add code to detect if subscribed to SNS topic 
//if not subscribed then subscribe the user and UPDATE the column in the database with a new value 0 to 1 so that then each time you don't have to resubscribe them
// add code to generate SQS Message with a value of the ID returned from the most recent inserted piece of work
//  Add code to update database to UPDATE status column to 1 (in progress)

echo "Creating SNS topic:\n";

$sns = new Aws\Sns\SnsClient([
        'version' => 'latest',
        'region' => 'us-west-2'
]);

$result = $sns->createTopic(array(
        'Name' => 'Mp2SnsTopic',
));

echo "Sns Topic is created:\n";
//echo $result;

$arn = $result['TopicArn'];

echo "\nSetting SNS topic attributes\n";

$result = $sns->setTopicAttributes([

 // TopicArn is required
        'TopicArn' => $arn,
        // AttributeName is required
        'AttributeName' => 'DisplayName',
        'AttributeValue' => 'Mp2SnsTopic',

]);




echo "\nSubscribing Topic\n";

for($i=0;$i<200;$i++){
 echo "=";
}


$result = $sns->subscribe([
    // TopicArn is required
    'TopicArn' => $arn,
    // Protocol is required
    'Protocol' => 'email',
    'Endpoint' => $useremail,
]);

echo "\nsubscribed the topic to email\n:";

//echo $result ;

for($i=0;$i<200;$i++){
 echo "=";
}

echo "\n Publishing the email.....";
echo "===========================================\n";
$result = $sns->publish([
        'Message' => 'sns topic is published to email',
        'Subject' => 'SNS TOPIC created with image getting uploaded in S3',
        'TopicArn' => $arn,
]);

$link->close();
?>

</div>
</body>
</html>

