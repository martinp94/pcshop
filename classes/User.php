<?php

class User
{

	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn,
			$_phoneNums = array(),
			$_ban = array(),
			$_shop = null;

	public function __construct($user = null){
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user){
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					// process logout
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function update($fields = array(), $id = null)
	{

		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
 
		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception("Problem sa apdejtovanjem");
			
		}
	}

	public function addPhone($fields = array()) {

		
			if(!$this->_db->insert('tel', $fields)) {
				throw new Exception('There was a problem with adding phone number');
			}
		
	}

	public function create($fields = array())
	{
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function find($user = null){
		if($user){
			$field = is_numeric($user) ? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()){
				$this->_data = $data->first();

				return true;
			}
		}

		return false;
	}

	public function login($username = null, $password = null, $remember = false){
		
		
		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {

		$user = $this->find($username);
			if($user) {
				if($this->data()->password === Hash::make($password, $this->data()->salt)) {
				
				Session::put($this->_sessionName, $this->data()->id);

				if($remember) {
					$hash = Hash::unique();
					$hashCheck = $this->_db->get('sessions', array('user_id', '=', $this->data()->id));

					if(!$hashCheck->count()) {
						$this->_db->insert('sessions', array(
							'user_id' => $this->data()->id,
							'hash' => $hash
						));
						
					} else {
						$hash = $hashCheck->first()->hash;

					}

					Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
				}

				return true;

				}	
			} 
		}



		return false;
	}

	public function hasPermission($key)
	{	
		$group = $this->_db->get('users_group', array('id', '=', $this->data()->permissions));
		
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);

			if($permissions[$key] === 1) {
				return true;
			}
		}

		return false;
	}

	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}

	public function logout()
	{

		$this->_db->delete('sessions', array('user_id', '=', $this->data()->id));

		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data(){
		return $this->_data;
	}

	public function isLoggedIn()
	{
		return $this->_isLoggedIn;
	}

	public function phoneNumbers(){

		$phonesResult = $this->_db->get('tel', array('user_id', '=', $this->data()->id));

		if($phonesResult->count()) {
			$this->_phoneNums = $phonesResult->results();
		} else {
			$this->_phoneNums = array();
		}

		return $this->_phoneNums;
	}

	public function isBan(){

		$banResult = $this->_db->get('users_bans', array('user_id', '=' , $this->data()->id));
		if($banResult->count()) {
			$this->_ban = $banResult->results();
			return true;
		} else {
			$this->_ban = array();
		}

		return false;
	}

	public function ban(){
		return $this->_ban;
	}

	public function unban(){


		if($this->_db->delete('users_bans', array('user_id','=',$this->data()->id))){

			return true;
		}

		return false;

	}

	public function hasShop(){

		$shop = new Shop($this->data()->id);
		if($shop->data() != null){
			$this->_shop = $shop;
			return true;
		}

		return false;
	}

	public function shop(){
		return $this->_shop;
	}


	
}