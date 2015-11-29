<?php session_start();?>
<html>
<head><title>Hello app</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h1>Welcome to  upload files</h1>
<a href='introspection.php'>Link to introspection page</a>
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="submit.php" method="POST">
    <div class="form-group">
    <label for="exampleInputEmail1">File Upload</label>
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
    <!-- Name of input element determines name in $_FILES array -->
    <!--<label for="exampleInputEmail1">Send this file</label>-->    
    <input name="userfile" class="btn btn-primary" type="file" /><br />
    <label for="exampleInputEmail1">Enter Email of user </label>
    <input type="email" name="useremail"><br />
    <label for="exampleInputEmail1">Enter Phone of user (1-XXX-XXX-XXXX)</label>
    <input type="phone" name="phone">
    <input type="submit" class="btn btn-primary" value="Send File" />
</form>
<hr />
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="gallery.php" method="POST">
<label for="exampleInputEmail1">Enter Email of user for gallery to browse:</label>
 <input type="email" name="email">
<input type="submit" class="btn btn-primary" value="Load Gallery" />
</form>

</div>
</body>
</html>

