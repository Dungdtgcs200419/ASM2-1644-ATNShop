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
    $name = $price = $content = $thumbnail = $view = $catalog = $catalog_id = "";
    if(isset($_GET['id']))
    {
        $query = "Select product.id, product.name, product.price, product.content, product.image_link, product.view, catalog.name as catalog, product.catalog_id from product, catalog where product.catalog_id = catalog.id and product.id='".$_GET['id']."'";
        $result = $conn->query($query);
        if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
        }

        $data = mysqli_fetch_array($result);
        $id= $_GET['id'];
        $name = $data['name'];
        $price = $data['price'];
        $content = $data['content'];
        $thumbnail = $data['image_link'];
        
        $view = $data['view'];
        $catalog = $data['catalog'];
        $catalog_id = $data['catalog_id'];

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Control</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- SummerNote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        

        $(document).ready(function() {
            $('#summernote').summernote({width: 500, height: 100, });
            $('#summernote').summernote('code', '<?php echo $content?>');
            });


        $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

           function CheckList()
           {
            var list = "<?php echo $list; ?>";
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
    <main>
        <div style="text-align: center; margin-bottom:50px; margin-top:50px"><h1>Product Control</h1></div>

        <form action="control.php" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin: auto; width: 35%; padding: 20px">
            <div class="custom-file">

                <input type="text" id="id" name="id" value="<?php echo $id?>" hidden>
                <input type="text" id="thumbnail" name="thumbnail" value="<?php echo $thumbnail?>" hidden>
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" style="width: 500px; margin: auto;" value="<?php echo $name ?>" required>
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" style="width: 500px; margin: auto;" value="<?php echo $price?>" required>
                
                <div class="custom-file" style="margin-top:20px; margin-bottom:20px">
                    <label for="image"></label>
                    <input type="file" class="custom-file-input" id="customFileInput" name="image" aria-describedby="customFileInput">
                    <label class="custom-file-label" for="customFileInput">Select file</label>

                <script>
                document.querySelector('.custom-file-input').addEventListener('change', function (e) {
                    var name = document.getElementById("customFileInput").files[0].name;
                    var nextSibling = e.target.nextElementSibling
                    nextSibling.innerText = name
                })
                </script>

                </div>
                <label for="category">Category:</label>
                <select class="form-control form-control-sm" id="catalog" name="catalog" style="width: 500px;">
                        <option disabled selected><-- Select catalog of product --></option>
<?php
    $getCatalog = "select * from catalog";
    $result = $conn->query($getCatalog);
    if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
    }
    while($row = mysqli_fetch_array($result))
    {
        if($catalog == $row['name'])
        {
                        echo '<option selected>'.$row['name'].'</option>';
                        continue;
        }
                        echo '<option>'.$row['name'].'</option>';
    }
?>
                </select>
                <label for="name" style="margin:auto;">Content:</label>
                <!-- <textarea class="form-control" id="content" name="content" rows="5" style="width: 500px; margin: auto;"><?php echo $content; ?></textarea> -->
                <textarea id="summernote" name="content"></textarea>
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
        $image = $_FILES['image']['name'];
        $target = "Upload/".basename($image);
        

        if($image == "")
        {
            $image = $_POST['thumbnail'];
        }        
 

        $name = $_POST['name'];
        $price = $_POST['price'];
        $id = $_POST['id'];
        $content = $_POST['content'];
        $catalog = $_POST['catalog'];


         if(!empty($id))
         {
            $GetCatalogID = "Select id from catalog where name='".$catalog."'";
            $getIDfromCatalog = $conn->query($GetCatalogID);
            if(!$getIDfromCatalog)
            {
            echo "Error select: " . $conn->error . "<br/>";
            }
            while($idFromCatalog = mysqli_fetch_array($getIDfromCatalog))
            {
                $catalog_id = $idFromCatalog['id'];
            }

            $query = "Update product set catalog_id='$catalog_id', name= '$name', price= '$price', image_link= '$image', content= '$content' where id = '$id'";
            $result = $conn->query($query);

        
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $msg = "Image uploaded successfully";
            }else{
                $msg = "Failed to upload image";
            }
            
            
            if (!$result)
            {
                echo "Edit failed: $query<br/>" . $conn->error . "<br/><br/>";
            }else 
            {
                header("Location:product.php");
                die;
            }
         } else{
            $GetCatalogID = "Select id from catalog where name='".$catalog."'";
            $getIDfromCatalog = $conn->query($GetCatalogID);
            if(!$getIDfromCatalog)
            {
            echo "Error select: " . $conn->error . "<br/>";
            }
            while($idFromCatalog = mysqli_fetch_array($getIDfromCatalog))
            {
                $catalog_id = $idFromCatalog['id'];
            }
            
            $query = "insert into product values (NULL,'$catalog_id', '$name', '$price', '$content', '$image', '0')";
            $result = $conn->query($query);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $msg = "Image uploaded successfully";
            }else{
                $msg = "Failed to upload image";
            }
            
            if (!$result)
            {
                echo "Edit failed: $query<br/>" . $conn->error . "<br/><br/>";
            }else 
            {
                header("Location:product.php");
                die;
            }
         }
     }
?>