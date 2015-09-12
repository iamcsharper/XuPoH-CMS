<?
class Debugger {
	public static function printException(Exception $e) {
		$message = $e->getMessage();
		echo "<pre style=\"background: #2b2b2b; color: #DAD7D7;padding: 35px 30px; font-family: Consolas, Open Sans Regular, Arial, sans-serif\"><h2 style='display:block;margin-top:6px; margin-bottom: 4px;color: #fff;'>{$message}</h2><p>Caused in <b>{$e->getFile()}</b>:<b>{$e->getLine()}</b></p>Stack trace: <br>";
		
		foreach ($e->getTrace() as $stack) {
			echo print_r($stack) . "<br>";
		}
		
		echo "</pre>";
	}
}