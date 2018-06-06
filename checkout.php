<?php

include_once 'core/init.php';

$user = new User();
$cart = array();
$shopCarts = array();
$productKeys = array();
$totals = array();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}	

if(Session::exists('cart')) $cart = json_decode(Session::get('cart'), true);

if(count($cart) == 0) {
	echo '<strong class="text-danger"> Vaša kupovna korpa je prazna </strong>';
} else{

	foreach ($cart as $shopId => $products) {
		
		$shop = new Shop(null, $shopId);
		$totalPrice = 0.0;

?>

		<div id="shop<?php echo $shopId; ?>" class="container-fluid">
			<div class="row text-center">
				<div class="cart-header col-md-4 col-md-offset-1">
					<?php echo $shop->data()->shop_name; ?>
					<span class="pull-right"><img src="images/korpa.png" class="img-responsive"></span>
				</div>
			</div>

			<div class="cart-details row text-center">
				<div class="col-md-12 ">
					<table class="table table-hover table-cart">
						<thead>
					      <tr>
					        <th>Brend</th>
					        <th>Model</th>
					        <th>Cijena</th>
					        <th>Količina</th>
					        <th></th>
					      </tr>
					    </thead>
					    <tbody>

					  	<?php
					  		foreach ($products as $key => $value) {
					  			$totalPrice += $value[0]['price'] * $value[0]['quantity'];
					  			$productKeys[$key] = $shopId;
					  			$totals[$shopId] = $totalPrice;
					  			
					  		
					  	?>

					      <tr id="product<?php echo $key; ?>">
					        <td><?php echo $value[0]['brand']; ?></td>
					        <td><?php echo $value[0]['model']; ?></td>
					        <td><?php echo number_format((float)$value[0]['price'], 2, ',', '') . ' €'; ?></td>
					        <td><?php echo $value[0]['quantity']; ?></td>
					        <td><span id="plus<?php echo $key; ?>" style="color: blue; cursor: pointer;" class="glyphicon glyphicon-plus"></span> &nbsp;
					         	<span id="minus<?php echo $key; ?>" style="color: red; cursor: pointer;" class="glyphicon glyphicon-minus"></span></td>
					      </tr>

					    <?php
					    	}
					    ?>
					      <tr>
					      	<td></td>
					      	<td></td>
					      	<td></td>
					        <td style="padding-top: 19px;">Ukupno: <?php echo number_format((float)$totalPrice, 2, ',', '') . ' €'; ?></td>
					        <td><button class="order btn btn-primary">Naruči</button></td>
					      </tr>

					    </tbody>
					</table>
				</div>
			</div>
		</div>

		<br>


<?php

	}
}
?>


<script>

	$(function(){

		var productShopKeyValue = <?php echo json_encode($productKeys); ?>;
		var totals = <?php echo json_encode($totals); ?>;
		var userId = <?php echo $user->data()->id; ?>;

		function updateTotal(tElement, price, minus, shop) {
			var total = tElement.children()[3].innerText;

				total = total.substring(total.indexOf(' '), total.indexOf('€'));
				total = total.replace(',', '.');
				total = parseFloat(total);
				if(minus === true) total -= price;
				 else total += price;
				
				total = total.toFixed(2);
				totals[shop] = total;
				total = total.replace('.', ',');
				total = 'Ukupno: ' + total + ' €';

				tElement.children()[3].innerText = total;

				console.log(total);
		}

	
		for(var key in productShopKeyValue) {

			(function(productid, shopid){
				$("#plus" + key).click(function(){

					var jsonData = { product_id : productid,
								  shop_id : shopid
								};
					jsonData = JSON.stringify(jsonData);
					

					$.ajax({
						url: "managecart.php",
						method: "POST",
						data: { plus : jsonData },
						dataType: "JSON",
						success: function(data)
						{
							
							var quantity = data['quantity'];
							var price = parseFloat(data['price']);

							var quantityElement = $("#plus" + productid).parent().parent().get(0).children[3];
							quantityElement.innerText = quantity;
							var totalElement = $("#plus" + productid).parent().parent().parent().children().last();

							updateTotal(totalElement, price, false, shopid);
							
						}
						,
						error: function(err){
							console.log(err.responseText);
						}
					});
				});
			})(key, productShopKeyValue[key]);

			(function(productid, shopid){
				$("#minus" + key).click(function(){

					var jsonData = { product_id : productid,
								  shop_id : shopid
								};
					jsonData = JSON.stringify(jsonData);
					

					$.ajax({
						url: "managecart.php",
						method: "POST",
						data: { minus : jsonData },
						dataType: "JSON",
						success: function(data)
						{
							console.log(data);

							var totalElement = $("#minus" + productid).parent().parent().parent().children().last();

							if(data['quantity'] != null && data['price'] != null) {
								var quantity = data['quantity'];
								var price = parseFloat(data['price']);

								var quantityElement = $("#minus" + productid).parent().parent().get(0).children[3];
								quantityElement.innerText = quantity;
								

								updateTotal(totalElement, price, true, shopid);
							} else {

								var removeId = 0;

								if(data['removeProduct'] != null) {

									removeId = data['removeProduct'];
									price = parseFloat(data['price']);
									updateTotal(totalElement, price, true, shopid);
									$("#product" + removeId).fadeOut(1000);
								}

								if(data['removeShop'] != null) {

									removeId = data['removeShop'];
									$("#shop" + removeId).fadeToggle(3000);
								}

								$("#cartItemCounter").html(parseInt($("#cartItemCounter").html()) - 1);
							}
							
						}
						,
						error: function(err){
							console.log(err.responseText);
						}
					});
				});
			})(key, productShopKeyValue[key]);
		}


		$(".order").click(function() {
			
			var shopId = $(this).closest("div").parent().parent().attr("id");
				shopId = shopId.substr(4);
			var total = totals[shopId];

			var jsonData = { shop_id : shopId,
							 user_id : userId,
							 sum : total
						   };
					jsonData = JSON.stringify(jsonData);
					

					$.ajax({
						url: "managecart.php",
						method: "POST",
						data: { order : jsonData },
						dataType: "JSON",
						success: function(data) {
							if(data['ordered'] == true) {
								$("#shop" + shopId).fadeToggle(3000);
								$("#cartItemCounter").html(parseInt($("#cartItemCounter").html()) - data['shopItemsCount']);
							}
						}
						,
						error: function(err) {
							console.log(err.responseText);
						}
					});

		});

	});


	


</script>

<?php 

?>