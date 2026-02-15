<?php
// source:      /srv/phlo/libs/cookies.phlo
// phlo:        1.0β
// version:     1.0
// creator:     q-ai.nl
// description: Cookies data object
class cookies extends obj {
	protected function controller(){
		$this->objData = $_COOKIE;
	}
	public $lifetimeDays = 180;
	public function __set($key, $value){
		$this->objData[$key] = $value;
		$_COOKIE[$key] = $value;
		setcookie($key, $value, time() + $this->lifetimeDays * 86400, slash, $_SERVER['HTTP_HOST'] ?? host, true, true);
	}
	public function __unset($key){
		unset($this->objData[$key], $_COOKIE[$key]);
		setcookie($key, void, time() - 86400, slash, $_SERVER['HTTP_HOST'] ?? host, true, true);
	}
}
