<?php
// source:      /srv/phlo/libs/DB/MySQL.phlo
// phlo:        1.0β
// version:     1.0
// creator:     q-ai.nl
// description: MySQL handler via DB class
// extends:     DB
// requires:    @DB creds:mysql
class MySQL extends DB {
	protected function _PDO(){
		return new PDO('mysql:host='.phlo('creds')->mysql->host.';dbname='.phlo('creds')->mysql->database, phlo('creds')->mysql->user, phlo('creds')->mysql->password);
	}
}
