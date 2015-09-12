<?php
class Home extends AbstractController {
	public function index() {
		$config = Core::getSettings();
		
		echo '<title>';
		echo $config->getSiteName();
		echo '</title>';
		
		echo '<h4>Test page!</h4><p>It works! <small>Powered by XuPoH</small></p>';	
		
		echo 'Execution time: ' . Core::getExecutionTime();
	}
}

?>