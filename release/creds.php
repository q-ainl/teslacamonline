<?php
class creds extends obj {
	public function __construct(?array $values = null){
		$values ??= parse_ini_file(data.'creds.ini', true, INI_SCANNER_RAW);
		foreach ($values AS $key => $value){
			$this->$key = is_array($value) ? new static($value) : new SensitiveParameterValue($value);
		}
	}
	public function objGet($key){
		if ($key === 'toArray') return loop($this->objData, fn($value) => is_a($value, 'SensitiveParameterValue') ? $value->getValue() : $value);
		if (isset($this->objData[$key]) && is_a($this->objData[$key], 'SensitiveParameterValue')) return $this->objData[$key]->getValue();
	}
	public function objInfo(){
		return loop($this->objData, fn($value) => is_a($value, 'SensitiveParameterValue') ? str_repeat('*', strlen($value->getValue())) : $value);
	}
}
