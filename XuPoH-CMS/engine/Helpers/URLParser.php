<?php
class URLParser {
	private $url;
	public function __construct() {
		$this->url = $this->parseUrl();
	}		public function getURL() {		return $this->url;
	}
	public function getControllerName() {
		return $this->url[0];
	}
	public function getControllerMethod() {
		return $this->url[1];
	}
	public function getControllerParams() {
		return $this->url[3] ? array_values($this->url) : [];
	}
	private function parseUrl() {
		if (isset($_GET['url'])) {
			return explode('/', rtrim($_GET['url'], '/'));
		}
	}
}