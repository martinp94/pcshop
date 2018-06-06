<?php

class Product {

	private $_db = null,
			$_data = null,
			$_comments = null;



	public function __construct($shop) {

		$this->_db = DB::getInstance();

	}


	private function find($shop){
		
	}



}