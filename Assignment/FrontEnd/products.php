<?php
        session_start();
        require_once '../Admin/Ultility/Connectdb.php';
        
        $title = 'Products';
        require_once './Outline/header.php';


        $conn = new mysqli($host , $username, $password, $database, $port);
        if ($conn->connect_error)
            {
                die($conn->connect_error);
            }

        $limit = 12;
        $page = 1;

        if(isset($_POST['search']))
        {
            $search = $_POST['search'];
            if(isset($_GET['page']))
            {
                $page = $_GET['page'];
            }
            $firstIndex = ($page - 1)*$limit;

            $sql = 'SELECT * FROM `product` WHERE `name` like "%'.$search.'%"';
            $result = $conn->query($sql);
            if(!$result) {
                echo "Error select: " . $conn->error . "<br/>";
            }
        } else
        {
                if(isset($_GET['page']))
            {
                $page = $_GET['page'];
            }
            $firstIndex = ($page - 1)*$limit;

            $sql = 'SELECT * FROM `product` where catalog_id = '.$_GET['category'].' limit '.$firstIndex.', '.$limit;
        
            $result = $conn->query($sql);
            if(!$result) {
                echo "Error select: " . $conn->error . "<br/>";
            }

            $query = 'SELECT * FROM catalog where id = '.$_GET['category'].'';
            $GetCatalog = $conn->query($query);
            if(!$GetCatalog) {
                echo "Error select: " . $conn->error . "<br/>";
            }

            $catalogName = mysqli_fetch_assoc($GetCatalog);
        }

            
?>


    <main>
        <div class="container-fluid" style="margin-top:20px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="main.php">Home</a></li>
                <?php
                    if(!isset($_POST['search']))
                    {
                ?>
                        <li class="breadcrumb-item active" aria-current="page"><a style="color: inherit;" href="products.php?category=<?=$_GET['category']?>"><?=$catalogName['name']?></a></li>

                <?php
                    }
                ?>
            </ol>        
        </div>


        <!-- show by CARD -->

        <div class="container">
            <div class="row">
<?php
 
 while($row = mysqli_fetch_array($result))
 {
     ++$firstIndex;
     echo '<div class="col-md-3" style="margin-top: 20px;">
     <div class="card" style="width: 16rem;">
        <a style="margin:auto; margin-top:5px" href="productdetail.php?id='.$row['id'].'&category='.$row['catalog_id'].'"><img class="card-img-top" style="width:100%; height:300px; object-fit:contain;" src="../Admin/Product_Management/Upload/'.$row['image_link'].'" alt="Card image cap"></a>
     <div class="card-body">
        <a style="color: inherit;" href="productdetail.php?id='.$row['id'].'&category='.$row['catalog_id'].'"><p class="card-text">'.$row['name'].'</p>
        <a style="color: inherit;" href="productdetail.php?id='.$row['id'].'&category='.$row['catalog_id'].'"><h5>'.$format = number_format($row['price'],2,".", ",").' $</h5>
     </div>
     </div>
     </div>';
 }
?>
            </div>
        </div>

        <!-- END OF SHOW BY CARD -->

        <!-- PAGINATION -->
<?php
if(!isset($_POST['search']))
{
        $GetPageNum = $conn->query('select count(id) as total, catalog_id from product admin where catalog_id = '.$_GET['category'].'');
    
    if(!$GetPageNum) {
        echo "Error select: " . $conn->error . "<br/>";
    }
    $PageNum = mysqli_fetch_array($GetPageNum);
    $number = ceil($PageNum['total']/$limit);
?>        
        <ul class="pagination" style="justify-content: center; margin-top: 50px;">
<?php
    if($page > 1)
    {
        echo '<li class="page-item"><a class="page-link" href="?category='.$_GET['category'].'&page='.($page-1).'">Previous</a></li>';
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
                echo '<li class="page-item"><a class="page-link" href="?category='.$_GET['category'].'&page='.($page-2).'">...</a></li>';
                $isFirst = true;
            }
            if(!$isLast && $i > $page+1)
            {
                echo '<li class="page-item"><a class="page-link" href="?category='.$_GET['category'].'&page='.($page+2).'">...</a></li>';
                $isLast = true;
            }
            continue;
        }
        if($page == ($i+1))
        {
            echo '<li class="page-item active"><a class="page-link" href="?category='.$_GET['category'].'&page='.($i+1).'">'.($i+1).'</a></li>';
        }else{
            echo '<li class="page-item"><a class="page-link" href="?category='.$_GET['category'].'&page='.($i+1).'">'.($i+1).'</a></li>';
        }
    }
?>
<?php
    if($page < $number)
    {
        echo '<li class="page-item"><a class="page-link" href="?category='.$_GET['category'].'&page='.($page+1).'">Next</a></li>';
    }
}
?>

       
    </main>


<?php
    require_once './Outline/footer.php';
?>