<?php


class Shop {

	private $_db = null,
			$_data = null,
			$_phoneNums = null,
			$_products = null;


	public function __construct($moderator = null, $shopId = null) {
		$this->_db = DB::getInstance();
		$this->find($moderator, $shopId);
	}

	public function isAccepted(){

		if($this->data()->accepted == 0){
			return false;
		} else {
			return true;
		}
	}



	public function find($moderator = null, $shopId = null){
		if($moderator) {
			
				$data = $this->_db->get('shops', array('user_id', '=', $moderator));

				if($data->count()){
					$this->_data = $data->first();

					return true;
				}
		}

		if($shopId) {
			$data = $this->_db->get('shops', array('id', '=', $shopId));

			if($data->count()) {
				$this->_data = $data->first();

				return true;
			}
		}

		return false;
	}

	public function phoneNumbers(){

		$phonesResult = $this->_db->get('tel', array('shop_id', '=', $this->data()->id));

		if($phonesResult->count()) {
			$this->_phoneNums = $phonesResult->results();
		} else {
			$this->_phoneNums = array();
		}

		return $this->_phoneNums;
	}

	public function data(){
		return $this->_data;
	}


	public function addProduct($shop, $brand, $model, $price, $type){
		

		$fields = array(

			'shop_id' => $shop,
			'brand' => $brand,
			'model' => $model,
			'price' => $price,
			'type' => $type,
			'date_created' => date('Y-m-d H:i:s')

		);

		if(!Session::exists('productAddStep')){


			if($this->_db->insert('products', $fields)) {

				Session::put('productAddStep', 2);
				return true;
			}
		}

		



		return false;
	}

	public function lastAddedProduct($shop){

		$sql = "SELECT type, id as 'lastid' FROM products WHERE id in (SELECT MAX(id) from products WHERE shop_id=" . $shop . ")";

		$query = $this->_db->query($sql);

		if(!$query->error()) {
			return $query->first();
		}

		return null;
	}

	public function products(){

		$products = $this->_db->get('products', array('shop_id', '=', $this->data()->id));

		if($products->count()) {
			$this->_products = $products->results();
		}



		return $this->_products;
	}



}