<?php
// source:      /srv/phlo/libs/session.phlo
// phlo:        1.0β
// version:     1.0
// creator:     q-ai.nl
// description: Session data object
class session extends obj {
	protected function controller(){
		session_start();
		$this->objData = $_SESSION;
	}
	public function __set($key, $value){
		return $_SESSION[$key] = $this->objData[$key] = $value;
	}
	public function __unset($key){
		unset($this->objData[$key], $_SESSION[$key]);
	}
}
