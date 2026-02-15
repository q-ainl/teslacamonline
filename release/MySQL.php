<?php
class MySQL extends DB {
	protected function _PDO(){
		return new PDO('mysql:host='.phlo('creds')->mysql->host.';dbname='.phlo('creds')->mysql->database, phlo('creds')->mysql->user, phlo('creds')->mysql->password);
	}
}
