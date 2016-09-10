<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "library";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>
<!DOCTYPE html>
<html>
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      
    <title>Library</title>
    <link rel="stylesheet" href="static/src/generated-css/app.min.css">
    <link rel="stylesheet" href="static/src/bower_components/bootstrap/dist/css/bootstrap.min.css">
     <script src="static/src/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="static/src/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
	<script src="static/src/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
     
</head>
<body id="page">
	<div class="container">
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">Plan-B Library</a>
		    </div>
		
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Book <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="?page=bookList">Book list</a></li>
		            <li><a href="?page=newBook">New book</a></li>
		          </ul>
		        </li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		        <li><a href="#">Log out</a></li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="#">Action</a></li>
		            <li><a href="#">Another action</a></li>
		            <li><a href="#">Something else here</a></li>
		            <li role="separator" class="divider"></li>
		            <li><a href="#">Separated link</a></li>
		          </ul>
		        </li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
		
		<div id="content">
			<?php 
				include $_GET['page'] . '.php';
			?>
		</div>
	</div>
	

</body>
</html>