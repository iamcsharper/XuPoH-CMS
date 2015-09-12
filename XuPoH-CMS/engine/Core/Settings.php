<?php
abstract class Settings {
	abstract protected function getName();
	
	private function getPath() {
		$path = FileHelper::getRoot(). "config/" . $this->getName() . "/config.json";
		
		if (!file_exists($path)) {
			throw new Exception("Can't load config from path " . $path . " local dir is " . __FILE__ . " and global is " . ROOT_DIR);
		}
		
		return $path;
	}
	
	private $fields = [];
	
	// getTest
	// return test
	
	public function __call($name, $args) {	
		if (stripos($name, 'get') !== false) {
			$name = str_replace('get', "", $name);
			$name[0] = strtolower($name[0]);
			
			if ($this->{$name} != null) {
				return $this->{$name};
			}
			
			return null;
		} else if (stripos($name, 'set') !== false) {
			$name = str_replace('set', '', $name);
			$name[0] = strtolower($name[0]);
			
			$this->{$name} = $args[0];
			$this->fields[$name] = $args[0];
		} else {
			if (method_exists($this, $name)) {
				return $this->$name();
			}
		}
    }
	
	public function load() {
		$path = $this->getPath();
		
		$fp = fopen($path, "r");
		$contents = fread($fp, filesize($path));
		fclose($fp);
		
		$data = @json_decode($contents, true);
		
		if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception("Invalid config JSON: " . json_last_error());
		}
		
		foreach ($data as $field => $value) {	
			$this->{$field} = $value;
			$this->fields[$field] = $value;
		}
	}
	
	public function save() {
		$path = $this->getPath();
		
		$fp = fopen($path, "w");
		$contents = fwrite($fp, json_encode($this->fields, JSON_PRETTY_PRINT));
		fclose($fp);
	}
}