<?php

class Cart
{
	
	protected $cartMaxItem = 0,
			  $itemMaxQuantity = 0,
			  $shopId = 0,
			  $useCookie = false;
	
	private $items = array();
	private $_db = null;

	public function __construct($shop, $options = []) {

		if (isset($options['cartMaxItem']) && preg_match('/^\d+$/', $options['cartMaxItem'])) {
			$this->cartMaxItem = $options['cartMaxItem'];
		}
		if (isset($options['itemMaxQuantity']) && preg_match('/^\d+$/', $options['itemMaxQuantity'])) {
			$this->itemMaxQuantity = $options['itemMaxQuantity'];
		}
		if (isset($options['useCookie']) && $options['useCookie']) {
			$this->useCookie = true;
		}
		
		$this->_db = DB::getInstance();
		$this->shopId = $shop;
		$this->read();
	}

	public function getShopId() {
		return $this->shopId;
	}
	
	public function getItems() {
		return $this->items;
	}

	public function getShopItems() {
		return $this->items[$this->shopId];
	}
	
	public function isEmpty() {
		return empty(array_filter($this->items));
	}
	
	
	public function add($id, $brand, $model, $price, $image) {
		$quantity = 1;


		$this->items[$this->shopId][$id][] = array(
			'brand' => $brand,
			'model' => $model,
			'price' => $price,
			'quantity' => ($quantity > $this->itemMaxQuantity) ? $this->itemMaxQuantity : $quantity
			
		);

		$this->write();
		return true;
	}
	
	public function update($id, $quantity = 1) {
		
		if($quantity != -1) {
			$quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
		}
		
		if ($this->items[$this->shopId][$id][0]['quantity'] == 1) {
			if($quantity == -1) {
				
				return $this->removeCartItem($id);
				
			}
		}

		if (isset($this->items[$this->shopId][$id])) {
			$index = 0;
			
			$this->items[$this->shopId][$id][0]['quantity'] += $quantity;
			$this->write();
			return true;
		} else {
			echo 'Greska';
		}
		return false;
	}
	
	public function removeCartItem($id) {

		if (!isset($this->items[$this->shopId][$id])) {
			return false;
		}

		$remove = array('removeProduct' => $id, 'price' => $this->items[$this->shopId][$id][0]['price']);
		
		unset($this->items[$this->shopId][$id]);

		if(empty($this->items[$this->shopId])) {
			unset($this->items[$this->shopId]);
			$remove = array('removeShop' => (string)$this->shopId);
		} 

		echo json_encode($remove);

		if(empty($this->items)) {
			unset($this->items);
			Session::delete('cart');
		} 
		$this->write();
		return true;
		

		return false;
	}

	public function purchase($user, $total) {

		$fields = array(
			'user_id' => $user,
			'shop_id' => $this->shopId,
			'order_time' => date('Y-m-d H:i:s'),
			'total' => $total

		);

		if(!$this->_db->insert('orders', $fields)) {
			throw new Exception('Greska! Narudzbina nije poslata!');
			echo json_encode(array('ordered' => false));
			return false;
		}

		$orderId = $this->_db->query("SELECT MAX(id) AS maxid from orders");

		if(!$orderId->error()) {
			$orderId = $orderId->first()->maxid;

			$shopItems = $this->getShopItems();

			foreach($shopItems as $key => $item) {
				
				$fields = array(
					'order_id' => $orderId,
					'product_id' => $key,
					'quantity' => $item[0]['quantity'],
					'price' => $item[0]['price']
				);

				if(!$this->_db->insert('order_line', $fields)) {
					throw new Exception('Greska! Narudzbina nije poslata!');
					echo json_encode(array('ordered' => false));
					return false;
				}
			}
		}

		echo json_encode(array('ordered' => true, 'shopItemsCount' => count($shopItems)));

		unset($this->items[$this->shopId]);
		if(empty($this->items)) {
			unset($this->items);
			Session::delete('cart');
		} 
		$this->write();

		return true;
	}
	
	private function read()	{
		$this->items = ($this->useCookie) ? json_decode(Cookie::exists('cart') ? Cookie::get('cart') : 'array()', true) : json_decode(Session::exists('cart') ? Session::get('cart') : 'array()', true);
	}
	
	private function write() {
		if ($this->useCookie) {
			setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800);
		} else {
			if(!empty($this->items)) Session::put('cart', json_encode(($this->items)));
		}
	}
}