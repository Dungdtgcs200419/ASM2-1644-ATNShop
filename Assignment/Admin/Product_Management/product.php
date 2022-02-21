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
    <title>Product Management</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirm_alert(node) {
            return confirm("Do you want to delete this?");
        }
    </script>
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
                <a class="nav-link" href="../Catalog_Management/catalog.php">Catalog Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="../Product_Management/product.php">Product Management</a>
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
    <main style="padding: 30px;">
        <div style="text-align: center; margin-bottom:20px; margin-top:20px"><h1>Product Management</h1></div>
        <div class="row">
            <div class="col-lg-6" style="margin-top: 20px; margin-bottom:20px">
                <form action="control.php" method="post">
                    <button type="submit" class="btn btn-primary">Add new product</button>
                </form>
            </div>
            <div class="col-lg-6">
                <form method="get">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search" id="search" name="search" style="width: 500px; float:right; margin-top: 10px">
                    </div>
                 </form>
            </div>
        </div>
    
    <!-- Product table -->
        <table class="table table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Thumbnail</th>
                <th>Name</th>
                <th>Price</th>
                <th>Catalog</th>
                <th>Content</th>
                <th>View</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
<?php
    $search = "";
    if(isset($_GET['search']))
    {
        $search = $_GET['search'];
    }
    $additionalQuery = '';
    if(!empty($search))
    {
        $additionalQuery = ' and product.name like "%'.$search.'%" ';
    }

    $limit = 10;
    $page = 1;
    if(isset($_GET['page']))
    {
        $page = $_GET['page'];
    }
    $firstIndex = ($page - 1)*$limit;
    $query = 'Select product.id, product.name, product.price, product.content, product.image_link, product.view, catalog.name as catalog from product, catalog where 1 and product.catalog_id = catalog.id '.$additionalQuery.' limit '.$firstIndex.', '.$limit;
    $result = $conn->query($query);
    if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
    }
    while($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>".++$firstIndex. "</td>";
        echo '<td><img src="Upload/'.$row['image_link'].'" style="width: 20%;"></td>';
        echo "<td>".$row['name']. "</td>";
        echo "<td>".$format = number_format($row['price'],2,".", ","). " $</td>";
        echo "<td>".$row['catalog']. "</td>";
        echo "<td>".$row['content']. "</td>";
        echo "<td>".$row['view']. "</td>";
?>
            <td><a href="control.php?id=<?php echo $row['id'];?>">Edit</a></td>
            <td><a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm_alert(this);">Delete</a></td>
<?php
        echo "</tr>";
    }
?>
        </tbody>
        </table>
        <!-- End of Product Table -->
<?php
    $GetPageNum = $conn->query('select count(id) as total from product where 1 '. $additionalQuery);
    if(!$GetPageNum) {
        echo "Error select: " . $conn->error . "<br/>";
    }
    $PageNum = mysqli_fetch_array($GetPageNum);
    $number = ceil($PageNum['total']/$limit);
?>
        <ul class="pagination" style="justify-content: center;">
<?php
    if($page > 1)
    {
        echo '<li class="page-item"><a class="page-link" href="?page='.($page-1).'">Previous</a></li>';
    }
?>

<?php 
    $availablePage = [1, $page-1, $page, $page+1, $number];
    $isFirst = $isLast = false;
    for($i=0; $i<$number; $i++)
    {
        if(!in_array(($i+1), $availablePage))
        {
            if(!$isFirst && $page > 3)
            {
                echo '<li class="page-item"><a class="page-link" href="?page='.($page-2).'">...</a></li>';
                $isFirst = true;
            }
            if(!$isLast && $i > $page+1)
            {
                echo '<li class="page-item"><a class="page-link" href="?page='.($page+2).'">...</a></li>';
                $isLast = true;
            }
            continue;
        }
        if($page == ($i+1))
        {
            echo '<li class="page-item active"><a class="page-link" href="?page='.($i+1).'">'.($i+1).'</a></li>';
        }else{
            echo '<li class="page-item"><a class="page-link" href="?page='.($i+1).'">'.($i+1).'</a></li>';
        }
    }
?>
<?php
    if($page < $number)
    {
        echo '<li class="page-item"><a class="page-link" href="?page='.($page+1).'">Next</a></li>';
    }
?>
        </ul>
    </main>

</body>
</html>