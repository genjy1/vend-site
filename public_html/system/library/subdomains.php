<?php
class SubDomains {
	public $data = array();
	private $subdomain = '';
	private $registry = null;

	public function __construct($registry) {
		$this->registry = $registry;
		// print_r($_SERVER);
		if(isset($_SERVER['DOMAIN_SUB'])){

			$this->subdomain = $_SERVER['DOMAIN_SUB'];
			// header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
      
		}
		//die("ddd");
	}
	public function getSubDomain(){
		if($this->subdomain == ''){
			return '';
		}
		return $this->subdomain . ".";
	}

	public function getMainPageDescription(){
		if($this->subdomain != ''){
			$subdomain = $this->subdomain;
		} else {
			$subdomain = 'main';
		}
		$query = $this->registry->get('db')->query("SELECT mainpage FROM " . DB_PREFIX . "subdomains WHERE sub ='" . $subdomain . "'");
		if(isset($query->row['mainpage']) && $query->row['mainpage']!=''){
			return $query->row['mainpage'];
		}

		$query = $this->registry->get('db')->query("SELECT mainpage FROM " . DB_PREFIX . "subdomains WHERE sub ='default'");
		return $query->row['mainpage'];
	}

	public function getMainTitle($title='')
	{
		if($this->subdomain == ''){
			return $title;
		}

		$query = $this->registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "subdomains WHERE sub ='" . $this->subdomain . "'");


		$pad = $query->row['pad'] ?? '';
		$country = $query->row['country'] ?? '';
		$country2 = $query->row['country2'] ?? '';
		$title = "Торговые автоматы $pad, купить вендинговые аппараты в $pad $country2";
		return $title;
	}

	public function getMainDescription($description='')
	{
		if($this->subdomain == ''){
			return $description;
		}

		$query = $this->registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "subdomains WHERE sub ='" . $this->subdomain . "'");


		$pad = $query->row['pad'] ?? '';
		$country = $query->row['country'] ?? '';
		$country2 = $query->row['country2'] ?? '';
		$description = "Вендинговый бизнес $pad №❶, торговые автоматы $pad – продажа торгового оборудования собственного производства – «ВЕНДПРОМ»";
		return $description;
	}

	public function getCategoryTitle($title='', $category_name)
	{
		if($this->subdomain == ''){
			return $title;
		}

		$query = $this->registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "subdomains WHERE sub ='" . $this->subdomain . "'");


		$pad = $query->row['pad'];
		$country = $query->row['country'];
		$country2 = $query->row['country2'];
		$telephone = explode(",", $this->registry->get('config')->get('config_telephone'));
		$telephone = $telephone[0];
		$title = "Торговые аппараты – $category_name $pad, купить $category_name $pad $telephone";
		return $title;
	}

	public function getCategoryDescription($description='', $category_name)
	{
		if($this->subdomain == ''){
			return $description;
		}

		$query = $this->registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "subdomains WHERE sub ='" . $this->subdomain . "'");


		$pad = $query->row['pad'];
		$country = $query->row['country'];
		$country2 = $query->row['country2'];
		$description = "$category_name $pad ✔продажа торговых автоматов $pad собственного производства - «ВЕНДПРОМ»";
		return $description;
	}

	public function getCategoryName($category_name)
	{
		if($this->subdomain == ''){
			return $category_name;
		}

		$query = $this->registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "subdomains WHERE sub ='" . $this->subdomain . "'");


		$pad = $query->row['pad'];

		$category_name = $category_name . " " . $pad;
		return $category_name;
	}
}