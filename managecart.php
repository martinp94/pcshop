<?php 


include_once 'core/init.php';

if($_SERVER['REQUEST_METHOD'] == "GET"){
   	Redirect::to('index.php');
}


$ajaxData = null;

if(isset($_POST['plus'])) {

	$ajaxData = json_decode($_POST['plus']);
	$shopKey = $ajaxData->shop_id;
	$productKey = $ajaxData->product_id;
	
	if($shopKey != '' && $productKey != '') {
		if(is_numeric($shopKey) && is_numeric($productKey)) {
			$cart = new Cart($shopKey, [
					'cartMaxItem' => 120,
					'itemMaxQuantity' => 100
				]);
			$cart->update($productKey);

			echo json_encode($cart->getShopItems()[$productKey][0], JSON_FORCE_OBJECT);
		}
	}
}

if(isset($_POST['minus'])) {

	$ajaxData = json_decode($_POST['minus']);
	$shopKey = $ajaxData->shop_id;
	$productKey = $ajaxData->product_id;
	
	if($shopKey != '' && $productKey != '') {
		if(is_numeric($shopKey) && is_numeric($productKey)) {
			$cart = new Cart($shopKey, [
					'cartMaxItem' => 120,
					'itemMaxQuantity' => 100
				]);
			$cart->update($productKey, -1);
			if(Session::exists('cart')) {
				if(isset($cart->getItems()[$shopKey])){
					if(isset($cart->getShopItems()[$productKey])) {
						echo json_encode($cart->getShopItems()[$productKey][0], JSON_FORCE_OBJECT);
					} 
				} 
			} 
			
			
			
		}
	}

}

if(isset($_POST['order'])) {

	$ajaxData = json_decode($_POST['order']);
	$shopKey = $ajaxData->shop_id;
	$userKey = $ajaxData->user_id;
	$totalPrice = $ajaxData->sum;

	if($shopKey != '') {
		if(is_numeric($shopKey)) {
			$cart = new Cart($shopKey, [
				'cartMaxItem' => 120,
				'itemMaxQuantity' => 100
			]);

			$cart->purchase($userKey, $totalPrice);
				
			
		}
	}
}

?>