<?php
session_start();

include('includes/configure.php');
include('includes/db.php');

function isUnique($email){
  $query = "select * from users where email='$email'";
  global $db;

  $result = $db->query($query);

  if ($result->num_rows > 0){
    return false;
  }
  else return true;
}

if(isset($_POST['register'])){
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['password'] = $_POST['password'];
	$_SESSION['confirm_password'] = $_POST['confirm_password'];	
	
	if(strlen($_POST['name'])<3){
		header("Location:register.php?err=" . urlencode("Nombre muy corto, se necesitan 3 o más caracteres"));
		exit();
	}
	else if($_POST['password'] != $_POST['confirm_password']){
		header("Location:register.php?err=" . urlencode("Las contraseñas no coinciden"));
		exit();
		
	}
	else if(strlen($_POST['password']) < 5){
		header("Location:register.php?err=" . urlencode("La contraseña debe tener 5 o más caracteres"));
		exit();
	}
	else if(strlen($_POST['confirm_password']) < 5){
		header("Location:register.php?err=" . urlencode("La contraseña debe tener 5 o más caracteres"));
		exit();
  }
  
  else if (!isUnique($_POST['email'])){
    header("Location:register.php?err=" . urlencode("Email ya en uso."));
		exit();
  }

  else {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $token = bin2hex(openssl_random_pseudo_bytes(32));

    $query = "insert into users (name, email, password, token, status) values ('$name', '$email', '$password', '$token', 1)";

    $db->query($query);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Implantación Apps Web</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Login</a></li>
            <li class="active"><a href="register.php">Registration</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

     <form action="register.php" method="post" style="margin-top: 35px;">
	 <h2> Formulario de registro con validación </h2>
	 <hr>
	 <?php
	 if(isset($_GET['err'])){ ?>
	 <div class="alert alert-danger">
	 <?php echo $_GET['err'];?>
	 </div>
	 <?php } ?>
	<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo @$_SESSION['name']; ?>" class="form-control" placeholder="Name" required>
  </div>
  <div class="form-group">
    <label>Email address</label>
    <input type="text" name="email" value="<?php echo @$_SESSION['email']; ?>" class="form-control" placeholder="Email" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name="password" value="<?php echo @$_SESSION['password']; ?>" class="form-control"  placeholder="Password" required>
  </div>
  
  <div class="form-group">
    <label>Confirm Password</label>
    <input type="password" name="confirm_password" value="<?php echo @$_SESSION['confirm_password']; ?>" class="form-control"  placeholder="Password" required>
  </div>
  
  <button type="submit" name="register" class="btn btn-default">Register</button>
</form>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>
