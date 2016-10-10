<?php 
	if( isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$s = "select count(username) as total from user where username = '" . $username . "' AND password = '" . md5($password) ."'";
		$userCount= $conn->query($s);
		$data = $userCount->fetch_assoc();
		$total_rows = $data['total'];
		
		 if ( $total_rows ) {
		 	if(isset($_POST['remember']) && $_POST['remember']){
				setcookie("login", true, time()+3600);
		 	}else{
				$_SESSION["login"] = true;
		 	}
			header("Location: index.php?page=bookList");
		} else {
			echo "Invalid username or password.";
			//echo "Error: " . $sql . "<br>" . $conn->error;
		} 
	}else{
		echo "Please input username and password.";
	}
?>
<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember" value="1"> Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>