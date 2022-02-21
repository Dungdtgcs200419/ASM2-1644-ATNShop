<?php
    session_start();
    $cart = [];
	if(isset($_SESSION['cart'])) {
		$cart = $_SESSION['cart'];
	}
	for ($i=0; $i < count($cart); $i++) {
		if($cart[$i]['id'] == $_GET['id']) {
			array_splice($cart, $i, 1);
			break;
		}
	}

	//update session
	$_SESSION['cart'] = $cart;

    header("Location:../cart.php");
?>