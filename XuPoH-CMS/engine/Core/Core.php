<?php
define("ROOT_DIR", __DIR__ . "/../../");
define("ENGINE_DIR", __DIR__ . "/../");

class FileHelper {
	public static function getRoot() {
		return ROOT_DIR;
	}
	
	public static function loadConfigs() {
		foreach (scandir(ROOT_DIR . "app/Settings/") as $file) {
			if ($file == '.' || $file == '..')
				continue;
			
			require_once(ROOT_DIR . "app/Settings/" . $file);
		}
	}
	
	public static function findFileSoft($name, $haystack) {
		$name = strtolower($name);
		
		foreach ($haystack as $file) {
			if ($file == '..' || $file == '.')
				continue;
			
			$cf = explode('.', $file)[0];
			$cf = strtolower($cf);
			
			if ($cf == $name) {
				return $file;
			}
		}
		
		return false;
	}
}

require("Settings.php");
FileHelper::loadConfigs();

include("AbstractController.php");
include(FileHelper::getRoot() . "engine/Helpers/URLParser.php");
include(FileHelper::getRoot() . "engine/Helpers/Debugger.php");

class Core {
	private static $defaultController = <<<EOL
<?php\nclass Home extends AbstractController {\n\tpublic function render() {\n\t\treturn '<h4>Test page!</h4><p>It works! <small>Powered by XuPoH</small></p>';\n\t}\n}\n\n?>
EOL;
	
	// @type Settings
	private static $settings;
	
	public static function getSettings() {
		return self::$settings;
	}
	
	/**
	 * Хранит экземпляр класса URLParser
	 **/
	private $urlParser;
	
	private static $startTime;
	
	public function __construct($stngs) {
		self::$startTime = self::msec();
		
		self::$settings = $stngs;
		self::$settings->load();
		$sn = self::$settings->getSiteName();
		
		if (stripos($sn, "by xupoh") === false) {
			$sn .= " (pwd by XuPoH)";
			
			self::$settings->setSiteName($sn);
			self::$settings->save();
		}
		
		$this->urlParser = new URLParser();
	}
	
	private function findControllerFile($path, $name) {
		$files = scandir($path);
		$find = FileHelper::findFileSoft($name, $files);
		
		if ($find == false) {
			$find = "Home.php";
		
			if (!file_exists($path . $find)) {
				$fp = fopen($path . $find, "w+");
				fwrite($fp, self::$defaultController);
				fclose($fp);
			}
		}
		
		return $find;
	}
	
	/**
	 * Implements routing data
	 */
	public function start() {
		$ctr = $this->urlParser->getControllerName();
		$mtd = $this->urlParser->getControllerMethod();
		$mtd = (empty($mtd)) ? "index" : $mtd;
		$pms = $this->urlParser->getControllerParams();
		
		$path = ROOT_DIR . "app/Controllers/";
		
		$ctr = $this->findControllerFile($path, $ctr);
		require_once($path . $ctr);
		
		$ctr = substr($ctr, 0, (strlen($ctr))-(strlen(strrchr($ctr, '.'))));
		
		if (!class_exists($ctr)) {
			throw new Exception("Couldn't find default controller! Please repair this file if you had changed this for some reasons.");
		}
		
		$controller = new $ctr;
		
		if (!method_exists($controller, "index")) {
			throw new Exception("Couldn't find default controller's <i>index</i> method! Please repair this file if you had changed this for some reasons.");
		}
		
		$reflection = new ReflectionMethod($controller, $mtd);
		if ($reflection == null || !$reflection->isPublic()) {
			throw new Exception("The called method is not public.");
			
			// TODO: 404
		}
		
		call_user_func_array([$controller, $mtd], $pms);
	}
	
	public static function getExecutionTime() {
		return self::msec() - self::$startTime;
	}
	
	private static function msec() {
		$mt = microtime();
		$exp = explode(' ', $mt);
		$uTime = reset($exp);
		$mSec = round($uTime, 3); // If you don't want a decimal you could use ceil($uTime * 1000);
	 
		return $mSec;
	}
}