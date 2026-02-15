<?php
// source:      /srv/phlo/libs/Files/JSON.phlo
// phlo:        1.0β
// version:     1.0
// creator:     q-ai.nl
// description: Generic JSON library
class JSON extends obj {
	public static function __handle(string $filename, ?string $path = null, $assoc = null){
		return "JSON/$path$filename".(is_bool($assoc) ? slash.(int)$assoc : void);
	}
	public function __construct(string $filename, ?string $path = null, $assoc = null){
		$path ??= data;
		$this->objFile = "$path$filename.json";
		if (is_readable($this->objFile)) $this->objRead($assoc);
	}
	public readonly string $objFile;
	public function objTouch(){
		return $this->objChanged = true;
	}
	public function objRead($assoc = null){
		return last($data = json_read($this->objFile, $assoc), $this->objData = $assoc || is_array($data) ? $data : get_object_vars($data), $this->objChanged = false, $this);
	}
	public function objWrite($data, $flags = null){
		return first($written = json_write($this->objFile, $data, $flags), $written && $this->objChanged = false);
	}
	public function __destruct(){
		return $this->objChanged && $this->objWrite($this->objData);
	}
}
