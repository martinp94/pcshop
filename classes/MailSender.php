<?php

class MailSender {

private $_error = '';

public function send_mail($email, $message)
{
	if(!mail($email, 'Aktivacija naloga', $message)){
		$this->_error =  'Error sending email';
	}
}



public function error(){
	return $this->_error;
}

}