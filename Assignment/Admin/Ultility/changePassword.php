<?php
    require_once '../Ultility/Connectdb.php';
    session_start();
    if($_SESSION['username'] == "")
    {
        header("Location:../index.php");
        die;
    }
    $conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Control</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body onload="CheckList()">
<nav>
        <ul class="nav nav-tabs">
            <li class="nav-item">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="../Account_Management/admin.php">Account Management</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="../Account_Management/admin.php">Admin accounts</a>
                <a class="dropdown-item" href="../Account_Management/user.php">User accounts</a>
            </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Catalog_Management/catalog.php">Catalog Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Product_Management/product.php">Product Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Transaction_Management/transaction.php">Transaction Management</a>
            </li>
            <li class="nav-item">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="../Account_Management/admin.php">Order Management</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="../Order_Management/waiting.php">Waiting to accept</a>
                <a class="dropdown-item" href="../Order_Management/on-going.php">On going</a>
                <a class="dropdown-item" href="../Order_Management/finished.php">Finished</a>
            </div>
            </li>
            <li class="nav-item nav ml-auto">
                <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="#">Hi <strong style="color: #052fff;"><?php echo $_SESSION['username'] ?></strong></a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="../Ultility/Logout.php">Log out</a>
                    <a class="dropdown-item" href="../Ultility/changePassword.php">Change password</a>
                </div>
            </li>
        </ul>
    </nav>
    <main>
        <div style="text-align: center; margin-bottom:50px; margin-top:50px"><h1>Change <?php echo $_SESSION['username']; ?>'s Password</h1></div>

        <form action="changePassword.php" method="post">
            <div class="form-group" style="margin: auto; width: 35%; padding: 20px">
                <label for="curPassword">Enter your current password:</label>
                <input type="password" class="form-control" id="curPassword" name="curPassword" style="width: 500px; margin: auto;" required>
                <label for="typeInPassword">Enter your new password:</label>
                <input type="password" class="form-control" id="typeInPassword" name="typeInPassword" style="width: 500px; margin: auto;" required>
                <label for="confirmPassword">Re enter your new password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" style="width: 500px; margin: auto;" required>
                <button type="submit" class="btn btn-success" id="Confirm" name="Confirm" style="margin-top: 20px; float:right;">Confirm</button>
            </div>
        </form>
<?php

?>
    </main>
</body>
</html>


<?php
     if(isset($_POST['Confirm']))
     {
        if(isset($_POST['curPassword']) && isset($_POST['typeInPassword']) && isset($_POST['confirmPassword']))
        {
            $query = "Select * from admin where username='".$_SESSION['username']."'";
            $result = $conn->query($query);
            if(!$result) {
            echo "Error select: " . $conn->error . "<br/>";}
            $data = mysqli_fetch_array($result);

            if(md5($_POST['curPassword']) == $data['password'])
            {
                if($_POST['typeInPassword'] != $_POST['confirmPassword'])
                {
                    echo '<br/><br/><br/><br/><br/>
                    <div class="alert alert-danger alert-dismissible" style="width: 1000px; margin:auto; text-align:center">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong> Make sure to let the retype password is similar to your new password!
                    </div>';
                }else {
                    $password = $_POST['typeInPassword'];
                    $query = "Update admin set password=MD5('$password') where username='".$_SESSION['username']."' ";
                    $result = $conn->query($query);
                    if (!$result)
                    {
                        echo "Edit failed: $query<br/>" . $conn->error . "<br/><br/>";
                    }else 
                    {
                        echo '<br/><br/><br/><br/><br/>
                            <div class="alert alert-success alert-dismissible" style="width: 1000px; margin:auto; text-align:center">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Success!</strong>' . $_SESSION['username'] . ' password has been updated!
                            </div>';
                        $_SESSION['password'] = md5($password);
                    }

                }
            } else{
                echo '<br/><br/><br/><br/><br/>
                <div class="alert alert-danger alert-dismissible" style="width: 1000px; margin:auto; text-align:center">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> Wrong current password that you typed in!
                </div>';
            }
        }
    }
    
?>
