<?php
session_start();
ob_start();
require_once '../Admin/Ultility/Connectdb.php';
$conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }
$title = 'Check your cart';
require_once './Outline/header.php';


$getUsrInfo = 'select * from user where username="'.$_SESSION['Web_user'].'"';
$UsrInfo = $conn->query($getUsrInfo);
    if(!$getUsrInfo) {
        echo "Error select: " . $conn->error . "<br/>";
    }
$Info = mysqli_fetch_assoc($UsrInfo);
?>
        <main>
<?php
if(isset($_POST['check_out']))
{
    $check = true;
}else $check = false;
?>
            <div class="container-fluid" style="margin-top:20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="main.php">Home</a></li>
                    <?php
                        if(!isset($_POST['search']))
                        {
                    ?>
                            <li class="breadcrumb-item active" aria-current="page"><a style="color: inherit;" href="cart.php>">Cart</a></li>

                    <?php
                        }
                    ?>
                </ol>        
            </div>
<?php
if(isset($_GET['success']))
{
    echo '<div class="alert alert-success" role="alert">
        Your order has been sent!
    </div>';
}
?>

        <div class="row">
            <div class="col-sm-8" style="padding: 30px 0px 0px 50px;">
            <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #b1f2d9;">
                            <th>No</th>
                            <th>Thumbnail</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Num</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$cart = [];
if(isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}
// var_dump($cart);die();
$count = 0;
$total = 0;
foreach ($cart as $item) {
    $total += $item['num'] * $item['price'];
    echo '
        <tr>
            <td>'.(++$count).'</td>
            <td><img src="../Admin/Product_Management/Upload/'.$item['image_link'].'" style="width: 100px"></td>
            <td>'.$item['name'].'</td>
            <td>'.number_format($item['price'], 0, '', '.').' $</td>
            <td>'.$item['num'].'</td>
            <td>'.number_format($item['num'] * $item['price'], 0, '', '.').' $</td>
            <td><a href="Ultility/delete.php?id='.$item['id'].'"><i class="far fa-trash-alt"></i></a></td>
        </tr>';
}
?>
                    </tbody>
                </table>
                <p style="font-size: 26px; color: red; text-align:right; padding-right:20px"><strong>Total:</strong> <?=number_format($total, 0, '', '.')?> $</p>
            </div>
            <div class="col-sm-4">
<?php

if(isset($_SESSION['Web_user']) && isset($_SESSION['Web_password']) && isset($_SESSION['Web_email']))
{
?>
    <div class="container sticky-top" style="margin-top:10%; text-align:center;">
        <h4><strong><?=$_SESSION['Web_user']?>'s checkout info</strong></h4>
        <form action="cart.php" method="post" style="margin-top: 5%;">
            <div class="form-group">
                <input type="text" style="width: 300px; margin:auto;" class="form-control" value="<?=$Info['name']?>" name="name" id="name" placeholder="Your name..." disabled required>
            </div>
            <div class="form-group">
                <input type="email" style="width: 300px; margin:auto;" class="form-control" value="<?=$Info['email']?>" name="email" id="email" placeholder="Your email address..." disabled required>
            </div>
            <div class="form-group">
                <input type="text" style="width: 300px; margin:auto;" class="form-control" value="<?=$Info['phone']?>" name="phone" id="phone" placeholder="Your phone number..." disabled required>
            </div>
            <div class="form-group">
                <input type="text" style="width: 300px; margin:auto;" class="form-control" value="<?=$Info['address']?>" name="address" id="address" placeholder="Your address..." disabled required>
            </div>
            <div class="form-group">
                <select class="form-group form-control form-control-md" id="payment_info" name="payment_info" style="width: 300px; margin:auto;">
                        <option>Bank</option>
                        <option>Cash</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" name="check_out" type="submit" onclick="$('.alert').alert()">
                    Submit your order.
                </button>
            </div>
        </form>
    </div>
<?php
}else{
?>
    <div class="container" style="margin-top:50%; text-align:center;">
        <button type="button"  class="btn btn-primary btn-round" data-toggle="modal" data-target="#loginModal">
          Please login to checkout order
        </button>
    </div>
<?php
}
?>
            </div>
        </div>
        </main>


<?php
if(isset($_POST['check_out']))
{
    $payment_Info = $_POST['payment_info'];
    $DATE =  date("Y-m-d h:i:s");
    $transaction = 'insert into `transaction` (`id`, `user_id`, `payment_info`, `total`, `created_at`) value (NULL, "'.$Info['id'].'", "'.$payment_Info.'", "'.$total.'", "'.$DATE.'")';
    $insertTrans = $conn->query($transaction);
    if(!$transaction) {
        echo "Error insert: " . $conn->error . "<br/>";
    }

    $TransID = 'select * from transaction where created_at="'.$DATE.'"';
    $getTransID = mysqli_fetch_assoc($conn->query($TransID));
    $cart = [];
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }
    foreach ($cart as $product) {
        $productID = $product['id'];
        $quantity = 0;
        $quantity = $product['num'];
        $status = 0;
        $order = 'insert into `order` (`id`, `transaction_id`, `product_id`, `quantity`, `status`) value (NULL, "'.$getTransID['id'].'", "'.$productID.'", "'.$quantity.'", "'.$status.'")';
        $insertOrder = $conn->query($order);
        if(!$insertOrder) {
            echo "Error insert: " . $conn->error . "<br/>";
        }
    }
    unset($_SESSION['cart']);
    header("Location:".$actual_link."?success=1");
}
    require_once './Outline/footer.php';
?>