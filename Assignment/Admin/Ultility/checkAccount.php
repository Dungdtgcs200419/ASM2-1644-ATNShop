<?php
    require_once '../Ultility/Connectdb.php';
    ob_start();
    $conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }

    
    session_start();

    if (isset($_POST['login'])) 
    {
        if(!isset($_POST['username']) || !isset($_POST['password']))
        {
            header("Location:../index.php");
            die;
        }else{
            $username = $_POST['username'];
            $password = md5($_POST['password']);
        }

        $query = 'select username, password, status from admin WHERE username ="'.$username.'" and password ="'.$password.'"';
        $result = $conn->query($query);
        if(!$result) {
        echo "Error select: " . $conn->error . "<br/>";
        }

        if(mysqli_num_rows($result) == 0)
        {
            header("Location:../index.php");
            die;
        }

        $row = mysqli_fetch_array($result);
     
        if($row['status'] == 1)
        {
            header("Location:../index.php?status=1");
            die;
        }else if($username == $row['username'] && $password == $row['password'])
        {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;            
            header("Location:../Account_Management/admin.php");
            die;
        }
        

        

        
    }
?>


