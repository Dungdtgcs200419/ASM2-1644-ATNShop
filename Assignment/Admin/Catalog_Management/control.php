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
$usr = $pwd = $id = "";
    if(isset($_GET['id']))
    {
        $query = "Select * from catalog where id='".$_GET['id']."'";
        $result = $conn->query($query);
        if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
        }

        $data = mysqli_fetch_array($result);
        $id= $_GET['id'];
        $name = $data['name'];
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog Control</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    

</head>
<body>
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
                <a class="nav-link active" href="../Catalog_Management/catalog.php">Catalog Management</a>
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
        <div style="text-align: center; margin-bottom:50px; margin-top:50px"><h1>Catalog Control</h1></div>

        <form action="control.php" method="post">
            <div class="form-group" style="margin: auto; width: 35%; padding: 20px">
                <input type="text" id="id" name="id" value="<?php echo $id?>" hidden>
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" style="width: 500px; margin: auto;" value="<?php echo $name ?>" required>
                <button type="submit" class="btn btn-success" id="confirm" name="Confirm" style="margin-top: 20px; float:right;">Confirm</button>
            </div>
        </form>
    </main>
</body>
</html>


<?php
     if(isset($_POST['Confirm']))
     {
         $name = $_POST['name'];
         $id = $_POST['id'];

         if(!empty($id))
         {
            $query = "Update catalog set name='$name' where id = '$id'";
            $result = $conn->query($query);
    
            if (!$result)
            {
                echo "Edit failed: $query<br/>" . $conn->error . "<br/><br/>";
            }else 
            {
                header("Location:catalog.php");
                die;
            }
         } else{

            $query = "insert into catalog values (NULL,'$name')";
            $result = $conn->query($query);

            if (!$result)
            {
                echo '<br/><br/><br/><br/><br/>
                    <div class="alert alert-danger alert-dismissible" style="width: 1000px; margin:auto; text-align:center">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong> This category name is already taken! Try another name.
                    </div>';
            }else 
            {
                header("Location:catalog.php");
                die;
            }
         }
            
     }
?>