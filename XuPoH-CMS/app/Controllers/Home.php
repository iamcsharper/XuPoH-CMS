<?php
class Home extends AbstractController {
	public function index() {
		$config = Core::getSettings();
		
		echo $this->view("home", [
			"testVar" => "testVal",
			"config" => $config
		]);
	}
}

?>
