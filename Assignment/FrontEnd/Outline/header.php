<?php
        ob_start();

    require_once '../Admin/Ultility/Connectdb.php';
    session_start();

    $conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }

    $query = 'SELECT * FROM `catalog`';
    $result = $conn->query($query);
    if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
    }
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link rel="shortcut icon" type="image/jpg" href="Ultility/favico/favicon.ico"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" referrerpolicy="no-referrer" />

    <style>

    .brand-name{
    text-align: left;
    }
    .account{
        text-align: center;
    }
    .account p {
        display: inline-block;
    }
      
    nav {
        width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 10px 0;
        box-shadow: 0px 5px 0px #dedede;
    }
    nav ul {
        list-style: none;
        text-align: center;
    }
    nav ul li {
        display: inline-block;
    }
    nav ul li a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #aaa;
        font-weight: 800;
        text-transform: uppercase;
        margin: 0 10px;
    }
    nav ul li a,
    nav ul li a:after,
    nav ul li a:before {
        transition: all .5s;
    }
    nav ul li a:hover {
        color: #555;
    }

    nav.blend ul li a {
        position: relative;
        border-radius: 5px;
        overflow: hidden;
        z-index: 1;
      }
      nav.blend ul li a:hover {
        color: #fff;
      }
      nav.blend ul li a:before,
      nav.blend ul li a:after {
        position: absolute;
        width: 0px;
        height: 100%;
        top: 0;
        bottom: 0;
        background: #1bdef8;
        transition: all .5s;
        margin: auto;
        content: '.';
        color: transparent;
        z-index: -1;
        opacity: 0.75;
      }
      nav.blend ul li a:before {
        left: 0;
      }
      nav.blend ul li a:after {
        right: 0;
      }
      
      nav.blend ul li a:hover:after,
      nav.blend ul li a:hover:before {
        width: 100%;
      }
    </style>
</head>
<body>
    <header id="Home">
        <div class="container">
            <div class="row" style="padding-top: 30px">
              <div class="col-sm-3">
                <div class="brand-name">
                    <a href="main.php" style="text-decoration: none; color: inherit; font-family:'Times New Roman', Times, serif;"><h2>Winy Winery</h2></a>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="search-box" style="padding-top: 5px;">
                    <form action="products.php" method="post">
                      <input type="text" name="search" id="search" style="width: 400px; margin-left:70px">
                      <button style="all: unset; cursor: pointer;" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="cart" style="padding-top: 10px;">
<?php
$cart = [];
if(isset($_SESSION['cart'])) {
	$cart = $_SESSION['cart'];
}
$count = 0;
foreach ($cart as $item) {
	$count += $item['num'];
}
?>
                <a href="cart.php"><button style="all:unset; padding-top:5px;" type="button" class="btn btn-light"><i class="fas fa-shopping-cart"></i> <span class="badge badge-light"> <?=$count?></span></button></a>
                </div>
              </div>
              <div class="col-sm-2">
<?php
  if(isset($_SESSION['Web_user']) && isset($_SESSION['Web_password']) && isset($_SESSION['Web_email']))
  {
    echo '<div class="dropdown">
            <button class="btn btn-light dropdown-toggle" style="all:unset; padding-top:15px; padding-left:50px; cursor: pointer;" padding-top:" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Hi  <strong>'.$_SESSION['Web_user'].'</strong>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="Ultility/logout.php">Log Out</a>
            </div>
          </div>';
  } else{
    echo '<button type="button" style="all:unset; padding-top:15px; padding-left:50px; cursor: pointer;"  class="btn btn-light btn-round" data-toggle="modal" data-target="#loginModal">
          Login
        </button>';
  }

?>  
              </div>
            </div>
          </div>
    </header>
    <nav class="blend">
        <ul>
          <li><a href="main.php">Home</a></li>
<?php 
  while($row = mysqli_fetch_array($result))
  {
    echo '          <li><a href="./products.php?category='.$row['id'].'">'.$row['name'].'</a></li>';
  }

?>
        </ul>
          <!-- LOG IN MODAL -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-title text-center">
                  <h4 style="padding: 1em;">Login</h4>
                </div>
                <div class="d-flex flex-column text-center">
                <form action="<?=$actual_link?>" method="POST">
                    <div class="form-group">
                      <input type="text" style="width: 300px; margin:auto;" class="form-control" id="actualLink" hidden>
                    </div>
                    <div class="form-group">
                      <input type="text" style="width: 300px; margin:auto;" class="form-control" name="user" id="user" placeholder="Your username or email address..." required>
                    </div>
                    <div class="form-group">
                      <input type="password" style="width: 300px; margin:auto;" class="form-control" name="password1" id="password1" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" style="width: 200px; margin:auto;" name="Login" class="btn btn-success btn-block btn-round">Login</button>
                  </form>
                </div>
                </div>
              <div class="modal-footer d-flex justify-content-center">
                <div class="signup-section">Not a member yet? <a href="#signUpModal" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal" class="text-info"> Sign Up</a>.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- END OF LOGIN MODAL -->

        <!-- SIGN UP MODAL -->
        <div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-title text-center">
                  <h4 style="padding: 1em;">Login</h4>
                </div>
                <div class="d-flex flex-column text-center">
                  <form action="<?=$actual_link?>" method="POST">
                    <div class="form-group">
                      <input type="text" style="width: 300px; margin:auto;" class="form-control" name="username" id="username" placeholder="Your username..." required>
                    </div>
                    <div class="form-group">
                      <input type="text" style="width: 300px; margin:auto;" class="form-control" name="name" id="name" placeholder="Your name..." required>
                    </div>
                    <div class="form-group">
                      <input type="email" style="width: 300px; margin:auto;" class="form-control" name="email" id="email" placeholder="Your email address..." required>
                    </div>
                    <div class="form-group">
                      <input type="text" style="width: 300px; margin:auto;" class="form-control" name="phone" id="phone" placeholder="Your phone number..." required>
                    </div>
                    <div class="form-group">
                      <input type="text" style="width: 300px; margin:auto;" class="form-control" name="address" id="address" placeholder="Your address..." required>
                    </div>
                    <div class="form-group">
                      <input type="password" style="width: 300px; margin:auto;" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" style="width: 200px; margin:auto;" name="SignUp" class="btn btn-info btn-block btn-round">SignUp</button>
                  </form>
                </div>
                </div>
            </div>
          </div>
        </div>
        <!-- END OF SIGNUP MODAL -->
      </nav>



