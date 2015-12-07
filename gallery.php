
<html>
<head>
<title>Gallery</title>
<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="magnific-popup/magnific-popup.css">

<!-- jQuery 1.7.2+ or Zepto.js 1.0+ -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!-- Magnific Popup core JS file -->
<script src="magnific-popup/jquery.magnific-popup.js"></script>

<script>

$('.gallery-item').magnificPopup({
  type: 'image',
  gallery:{
    enabled:true
  }
});

</script>

</head>
<body>

<?php
session_start();
$email = $_POST["email"];
echo $email;

require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'tch-db',
));

$endpoint = "";

foreach ($result->getPath('DBInstances/*/Endpoint/Address') as $ep) {
    // Do something with the message
    echo "============". $ep . "================";
    $endpoint = $ep;
}

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];


echo "begin connecting  database";


$link = mysqli_connect($endpoint,"controller","ilovebunnies","customerrecords") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
 printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//below line is unsafe - $email is not checked for SQL injection -- don't do this in real life or use an ORM instead

if(isset($_SESSION['useremail'])){
$email=$_SESSION['useremail'];

$link->real_query("SELECT * FROM items WHERE email = '$email'");
//$link->real_query("SELECT * FROM items");
$res = $link->use_result();
echo "Result set order...\n";
print "\n=================\n";
?>
<div class="gallery">
<?php
while ($row = $res->fetch_assoc()) {
    //echo "<img src =\" " . $row['s3rawurl'] . "\" /><img src =\"" .$row['s3finishedurl'] . "\"/>";
//echo $row['id'] . "Email: " . $row['email'];
echo "<img src =\"".$row['s3finishedurl'] . "\">";

print "\n=========================================\n";

}

$link->real_query("SELECT * FROM items WHERE email = '$email'");
//$link->real_query("SELECT * FROM items");
$res = $link->use_result();
echo "Raw images\n";
print "\n=================\n";

while ($row = $res->fetch_assoc()) {
    //echo "<img src =\" " . $row['s3rawurl'] . "\" /><img src =\"" .$row['s3finishedurl'] . "\"/>";
//echo $row['id'] . "Email: " . $row['email'];
echo "<img src =\"".$row['s3rawurl'] . "\">";

print "\n=========================================\n";

}

}
else{
print "\n============================================\n";
print "The user has not entered any emailid";
$link->real_query("SELECT * FROM items WHERE email = '$email'");
//$link->real_query("SELECT * FROM items");
$res = $link->use_result();
echo "Raw images\n";
print "\n=================\n";

while ($row = $res->fetch_assoc()) {
    //echo "<img src =\" " . $row['s3rawurl'] . "\" /><img src =\"" .$row['s3finishedurl'] . "\"/>";
//echo $row['id'] . "Email: " . $row['email'];
echo "<img src =\"".$row['s3rawurl'] . "\">";

print "\n=========================================\n";

}

}
?>
</div>

<?php
$link->close();
?>
</body>
</html>
                    
