<?php

class Messenger {

	private $_db,
			$_sentMessages = array(),
			$_receivedMessages = array();


	public function __construct($user_id){

		$this->_db = DB::getInstance();

		if(is_numeric($user_id)){
			$this->getFrom($user_id);
			$this->getTo($user_id);
		}
		

	}




	private function getFrom($from){

		$query = $this->_db->get('messages', array('from_user', '=', $from));
		if($query->count()) {
			$this->_receivedMessages = $query->results();
			
		} 


	}

	private function getTo($to){
		$query = $this->_db->get('messages', array('to_user', '=', $to));
		if($query->count()) {
			$this->_sentMessages = $query->results();
			
		} 


	}

	public function sendMessage($from, $to, $message){


		if($to){

			$userId = '';

			$shop = $this->_db->get('shops', array('url_name', '=', $to));

				if($shop->count()) {
					$userId = $shop->first()->user_id;
				} else {

					$userId = $this->_db->get('users', array('username', '=', $to));
					if($userId->count()) {

					$userId = $userId->first()->id;

					} else {

					echo 'ne postoji korisnik taj';
					return false;
					
					}
				}

			

			

			echo $userId;

			if($from && $message) {

				$fields = array(
					'to_user' => $userId,
					'from_user' => $from,
					'text' => $message,
					'date' => date('Y-m-d H:i:s')
				);

				if($this->_db->insert('messages', $fields)) {
					return true;
				}
			}

		}

		
		echo 'mrs u pizdu';
		return false;

	}


	public function receivedMessages(){
		return $this->_receivedMessages;
	}

	public function sentMessages()
	{
		return $this->_sentMessages;
	}

}