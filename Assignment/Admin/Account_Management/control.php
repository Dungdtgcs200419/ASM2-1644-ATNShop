<?php
    require_once '../Ultility/Connectdb.php';
    ob_start();
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
$usr = $id = "";
    if(isset($_GET['id']))
    {
        $query = "Select * from admin where id='".$_GET['id']."'";
        $result = $conn->query($query);
        if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
        }

        $data = mysqli_fetch_array($result);
        $id= $_GET['id'];
        $usr = $data['username'];
        $list = ($data['status'] ==0 ? 'Active' : 'Disable');
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

    <script>
           function CheckList()
           {
            var list = "<?php echo $list; ?>";
            console.log(list);
            if(list == "Active")
            {
                document.getElementById('status').selectedIndex = "1";
            } else if(list == "Disable"){
                document.getElementById('status').selectedIndex = "2";
            }           }
    </script>
</head>
<body onload="CheckList()">
<nav>
        <ul class="nav nav-tabs">
            <li class="nav-item">
            <a class="nav-link dropdown-toggle active" data-toggle="dropdown" href="../Account_Management/admin.php">Account Management</a>
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
                <a class="dropdown-item" href="../Order_Management/allOrder.php">All Orders</a>
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
        <div style="text-align: center; margin-bottom:50px; margin-top:50px"><h1>Account Control</h1></div>

        <form action="control.php" method="post">
            <div class="form-group" style="margin: auto; width: 35%; padding: 20px">
                <input type="text" id="id" name="id" value="<?php echo $id?>" hidden>
                <label for="usr">Username:</label>
                <input type="text" class="form-control" id="usr" name="usr" style="width: 500px; margin: auto;" value="<?php echo $usr ?>" required>
<?php 
    if(!isset($_GET['id']))
    {
        echo '<label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="pwd" style="width: 500px; margin: auto;" required>';
    }
?>
                <label for="status">Status:</label>
                <select class="form-control form-control-sm" id="status" name="status" style="width: 500px;">
                        <option disabled selected><-- Select account status --></option>
                        <option>Active</option>
                        <option>Disable</option>
                </select>
                <button type="submit" class="btn btn-success" id="confirm" name="Confirm" style="margin-top: 20px; float:right;">Confirm</button>
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
         $username = $_POST['usr'];
         $status = ($_POST['status'] =='Active' ? 0 : 1);
         $id = $_POST['id'];
         $pwd = $_POST['pwd'];
         $date = date('Y-m-d H:i:s');

         if(!empty($id))
         {
            $query = "Update admin set username='$username', status= '$status' where id = '$id'";
            $result = $conn->query($query);
    
            if (!$result)
            {
                echo "Edit failed: $query<br/>" . $conn->error . "<br/><br/>";
            }else 
            {
                header("Location:admin.php");
                die;
            }
         } else{

            $query = "insert into admin values (NULL,'$username', MD5('$pwd'),'$status', '$date')";
            $result = $conn->query($query);

            if (!$result)
            {
                // echo "Insert failed: $query<br/>" . $conn->error . "<br/><br/>";
                echo '<br/><br/><br/><br/><br/>
                    <div class="alert alert-danger alert-dismissible" style="width: 1000px; margin:auto; text-align:center">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong> This username is already taken! Try another username.
                    </div>';
            }else 
            {
                header("Location:admin.php");
                die;
            }
         }
            
     }
?>