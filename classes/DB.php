<?php
class DB {

	private static $_instance = null;
	
    private $_pdo,
   		    $_query, 
   		    $_error = false, 
   		    $_results, 
   		    $_count = 0;

    
    private function __construct(){
    	try {
    		$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
    	
    	} catch(PDOException $e) {
    		die($e->getMessage());
    	}
    }

    public static function getInstance() {
    	

    	if(!isset(self::$_instance)){
    		self::$_instance = new DB();
    	}

    	
    	return self::$_instance;
    }

    public function query($sql, $params = array()) {
    	$this->_error = false;

    	if($this->_query = $this->_pdo->prepare($sql)){
    		$x = 1;
    		if(count($params)){
    			foreach ($params as $value) {
    				$this->_query->bindValue($x,$value);
    				$x++;
    			}
    		}

    		if($this->_query->execute()){
    			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
    			$this->_count = $this->_query->rowCount();
    		}else{

    			print_r($this->_query->errorInfo());
    			$this->_error = true; 
    		}
    	}

    	return $this;
    }


    public function action($action,$table,$where = array(), $orderByColumns = null, $asc = true) {
    	if(count($where) === 3){
    		$operators = array('=', '!=', '>', '<', '>=', '<=');

    		$field = $where[0];
    		$operator = $where[1];
    		$value = $where[2];

    		if(in_array($operator, $operators)){

                if($orderByColumns != null) {

                    if($asc == true) {
                        $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$orderByColumns}";
                    } else {
                        $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$orderByColumns} DESC";
                    }
                } else {
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                }

    			

    			if(!$this->query($sql, array($value))->error()){
    				return $this;
    			}
    		}
    	}

    	return false;
    }

    public function get($table, $where){
    	return $this->action('SELECT *',$table,$where);
    }

    public function getOrderBy($table, $where, $orderByColumns, $asc = true) {
        return $this->action('SELECT *', $table, $where, $orderByColumns, $asc);
    }

    public function leftJoin($table1, $table2, $fkey, $where = array(), $action = 'SELECT *') {

        if(count($where) === 3){
            $operators = array('=', '!=', '>', '<', '>=', '<=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)) {

            }

            $sql = "{$action} FROM {$table1} LEFT JOIN {$table2} ON {$table1}.{$fkey} = {$table2}.id WHERE {$field} {$operator} ?";

            if(!$this->query($sql, array($value))->error()) {
                return $this;
            }
        } else {

            $sql = "SELECT * FROM {$table1} LEFT JOIN {$table2} ON {$table1}.{$fkey} = {$table2}.id";

            if(!$this->query($sql)->error()) {
                return $this;
            }
        }

    }

    public function delete($table, $where) {
    	return $this->action('DELETE',$table,$where);
    }

    public function insert($table, $fields = array()) {
    	if(count($fields)){
    		$keys = array_keys($fields);
    		$values = '';

    		foreach ($fields as $field) {
    			$values .= '?,';
    		}

    		$values = substr_replace($values, '', strripos($values, substr($values, -1)));
			    		
    		$sql = "INSERT INTO {$table} (`". implode('`, `', $keys) ."`)  VALUES ({$values})";

    		if(!$this->query($sql, $fields)->error()){
    			return true;
    		};
    	}

    	return false;
    }


    public function update($table,$id,$fields) {
    	$set = '';
    	
		foreach ($fields as $key => $value) {
    		$set .= "{$key} = ?,";
    		
    	}

    	$set = substr_replace($set, '', strripos($set, substr($set, -1)));

    	$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
    	echo $sql;
    	if(!$this->query($sql, $fields)->error()){
    		return true;
    	}

    	return false;

    }

    public function updateNew($table,$col,$colVal,$fields) {
        $set = '';
        
        foreach ($fields as $key => $value) {
            $set .= "{$key} = ?,";
            
        }

        $set = substr_replace($set, '', strripos($set, substr($set, -1)));

        $sql = "UPDATE {$table} SET {$set} WHERE {$col} = {$colVal}";
        echo $sql;
        if(!$this->query($sql, $fields)->error()){
            return true;
        }

        return false;

    }

    public function results(){
    	return $this->_results;
    }

    public function first()
    {
        return $this->results()[0];
    }


    public function error() {
    	return $this->_error;
    }

    public function count(){
    	return $this->_count;
    }

}