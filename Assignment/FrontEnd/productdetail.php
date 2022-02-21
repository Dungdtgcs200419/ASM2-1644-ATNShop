<?php
    session_start();
    ob_start();
    require_once '../Admin/Ultility/Connectdb.php';

    $conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }

    $sql = 'SELECT * FROM `product` where catalog_id = '.$_GET['category'].' and id='.$_GET['id'].'';
    $result = $conn->query($sql);
        if(!$result) {
            echo "Error select: " . $conn->error . "<br/>";
        }
    $detail = mysqli_fetch_assoc($result);
    $title = $detail['name'];
    require_once './Outline/header.php';


    $view = $detail['view'] + 1;
    
    $updateView = 'Update product set view = '.$view.' where catalog_id = '.$_GET['category'].' and id='.$_GET['id'].'';
    $update = $conn->query($updateView);
        if(!$result) {
            echo "Update failed: " . $conn->error . "<br/>";
        }

    $query = 'SELECT * FROM catalog where id = '.$_GET['category'].'';
    $GetCatalog = $conn->query($query);
    if(!$GetCatalog) {
        echo "Error select: " . $conn->error . "<br/>";
        }
    $catalogName = mysqli_fetch_assoc($GetCatalog);


?>

<main>
        <div class="container-fluid" style="margin-top:20px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="main.php">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="products.php?category=<?=$_GET['category']?>"><?=$catalogName['name']?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a style="color: inherit;" href="productdetail.php?category=<?=$_GET['category']?>&id=<?=$_GET['id']?>"><?=$detail['name']?></a></li>
            </ol>        
        </div>


        <div class="container-fluid" style="padding: 10px 20px 10px 100px;">
            <div class="row">
                <div class="col-md-5">
                    <img style="width:100$; height:500px; object-fit:cover;" src="../Admin/Product_Management/Upload/<?=$detail['image_link']?>" alt="">
                </div>
                
                <div class="col-md-7">
                    <h2 style="margin: 20px; font-size: 50px;"><?=$detail['name']?></h2>
                    <h6 style="margin: 20px;"><strong><?=$format = number_format($detail['price'],2,".", ",")?> $</strong></h6>
                    <form method="POST" action="productdetail.php?category=<?=$detail['catalog_id']?>&id=<?=$detail['id']?>">
                    <button class="btn btn-info" style="width: 400px; font-size:30px;" name="add" type="submit">ADD TO CART</button>
                    </form>
                    <br/><br/>
                    <div style="width: 400px;">
                    <span><?=$detail['content']?></span>
                    </div>
                </div>
            </div>
        </div>
</main>

<?php
    require_once './Outline/footer.php';
    if(isset($_POST['add']))
    {
        $id = $detail['id'];
        $cart = [];
        if(isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
        }
        $isFind = false;
        for ($i=0; $i < count($cart); $i++) {
            if($cart[$i]['id'] == $id) {
                $cart[$i]['num']++;
                $isFind = true;
                break;
            }
        }
        if(!$isFind) {
            $sql = 'select * from product where id = '.$id;
            $product = $conn->query($sql);
            if(!$product) {
            echo "Error select: " . $conn->error . "<br/>";
            }
            $item = mysqli_fetch_assoc($product);
            $item['num'] = 1;
            $cart[] = $item;
        }

        //update session
        $_SESSION['cart'] = $cart;
        header("Location:productdetail.php?category=".$detail['catalog_id']."&id=".$detail['id']);
    }
?>