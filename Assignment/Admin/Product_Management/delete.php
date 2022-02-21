<?php 
    require_once '../Ultility/Connectdb.php';
    $conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }
    if(isset($_GET['id']))
    $id = $_GET['id'];
    $query = "delete from product where id = '$id'";

    $result = $conn->query($query);
    if(!$result)
    {
        echo "Delete Failed: $query<br/>" . $conn->error . "<br/>";
    } else 
    {
        mysqli_close($conn);
        header("Location:./product.php");
        die;
    }
?>