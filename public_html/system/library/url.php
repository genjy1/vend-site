<?php
class Url {
	private $ssl;
	private $rewrite = array();

	public function __construct($ssl = false) {
		$this->ssl = $ssl;
	}
	
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = true, $origin = false) {
		// echo $args;
		$server = $_SERVER['HTTP_HOST'];
		if(!preg_match("/(path=)/", $args) && isset($_SERVER['DOMAIN_SUB'])){
			$server = preg_replace("/^" . $_SERVER['DOMAIN_SUB'] . "\./", "", $server);
		}
		if($origin && isset($_SERVER['DOMAIN_SUB'])){ 
			$server = preg_replace("/^" . $_SERVER['DOMAIN_SUB'] . "\./", "", $server);
		}
		if ($this->ssl && $secure) {
			$url = 'https://' . $server . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/index.php?route=' . $route;
		} else {
			$url = 'http://' . $server . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/index.php?route=' . $route;
		}
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
		if ($route == "common/home") {
			$url = "https://vend-shop.com/";
		}
		if ($route == "information/contact") {
			$url = "https://vend-shop.com/kontakty/";
		}

		return $url;
	}
}