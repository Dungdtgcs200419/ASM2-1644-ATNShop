<?php
    session_start();
    $title = 'Winy Winery';
    require_once './Outline/header.php';
    require_once '../Admin/Ultility/Connectdb.php';


    $conn = new mysqli($host , $username, $password, $database, $port);
    if ($conn->connect_error)
        {
            die($conn->connect_error);
        }

    $top3MostView = 'SELECT * FROM product ORDER BY view DESC LIMIT 0, 3';
    $Top3 = $conn->query($top3MostView);
    if(!$Top3) {
        echo "Error select: " . $conn->error . "<br/>";
    }

    $AfterTop3 = 'SELECT * FROM product ORDER BY view DESC LIMIT 3, 8';
    $After3 = $conn->query($AfterTop3);
    if(!$After3) {
        echo "Error select: " . $conn->error . "<br/>";
    }
?>

      <main>
          <h4 style="text-align: center; margin-top: 20px;"><strong>Most viewed on Winy Winery</strong></h4>
          <div class="row" style="margin-top: 30px; margin-bottom:30px">
              <div class="col-sm-2"></div>
              <div class="col-sm-8">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <a href=""></a>
<?php

  if ($Top3View = mysqli_fetch_assoc($Top3)) {
    $firstRow = $Top3View;
    echo '<div class="carousel-item active">
            <a href="productdetail.php?category='.$firstRow['catalog_id'].'&id='.$firstRow['id'].'"><img class="d-block w-100" style="width: 14%; height:400px; object-fit:contain;" src="../Admin/Product_Management/Upload/'.$firstRow['image_link'].'"></a>
            
        </div>';
 
    while ($Top3View = mysqli_fetch_assoc($Top3)) {
        echo '<div class="carousel-item ">
        <a href="productdetail.php?category='.$Top3View['catalog_id'].'&id='.$Top3View['id'].'"><img class="d-block w-100" style="width: 14%; height:400px; object-fit:contain;" src="../Admin/Product_Management/Upload/'.$Top3View['image_link'].'"></a>
                
            </div>';
    }
 }
    

?>                            

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" style="background-color: #bfbfbd;" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" style="background-color: #bfbfbd;" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
              </div>
          </div>

          

          <div class="container">
              <div class="row">
 <?php
 
    while($After3Select = mysqli_fetch_array($After3))
    {
        echo '<div class="col-md-3" style="margin-top: 20px;">
        <div class="card" style="width: 15rem;">
        <a style="margin:auto; margin-top:5px" href="productdetail.php?category='.$After3Select['catalog_id'].'&id='.$After3Select['id'].'"><img class="card-img-top" style="width:100%; height:300px; object-fit:contain;" src="../Admin/Product_Management/Upload/'.$After3Select['image_link'].'" alt="Card image cap"></a>
        <div class="card-body">
            <a style="color: inherit;" href="productdetail.php?category='.$After3Select['catalog_id'].'&id='.$After3Select['id'].'"><p class="card-text">'.$After3Select['name'].'</p></a>
            <a style="color: inherit;" href="productdetail.php?category='.$After3Select['catalog_id'].'&id='.$After3Select['id'].'"><h5>'.$format = number_format($After3Select['price'],2,".", ",").' $</h5></a>
        </div>
        </div>
        </div>';
    }
 ?>
              </div>
          </div>
      </main>

<?php
    require_once './Outline/footer.php';
?>