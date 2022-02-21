 <footer style="margin-top: 50px; border-top: 1px black solid">
        <p style="font-size: 20px; text-align:center;">&copy; Copyright 2021 DungDoan</p>
    </footer>
    </body>
</html>

<?php
if(isset($_POST['Login']))
{
    session_start();
    $user = $_POST['user'];
    $password = md5($_POST['password1']);
    $getUser = 'select username, password, email from user where password="'.$password.'" and username="'.$user.'" or email="'.$user.'"';
    $result = $conn->query($getUser);
    if(!$result) {
    echo "Error select: " . $conn->error . "<br/>";
    }
    if(mysqli_num_rows($result) == 0)
    {
        header("Location:".$actual_link."");
    }
    $row = mysqli_fetch_array($result);
    if($user == $row['username'] || $user == $row['email'] && $password == $row['password'])
    {
        $_SESSION['Web_user'] = $row['username'];
        $_SESSION['Web_email'] = $row['email'];
        $_SESSION['Web_password'] = $row['password'];
        header("Location:".$actual_link."");
      }

}

if(isset($_POST['SignUp']))
{
  $name = $email = $phone = $address = $username = $password = "";
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $addUser = "insert into user values (NULL,'$name','$email','$phone','$address','$username','$password')";
          $result = $conn->query($addUser);

          if (!$result)
          {
              echo "Insert failed: $query<br/>" . $conn->error . "<br/><br/>";
          }else{
            header("Location:".$actual_link."");
            die;
          }
}
?>