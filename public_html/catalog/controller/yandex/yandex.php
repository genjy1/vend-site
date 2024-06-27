<?php
class ControllerYandexYandex extends Controller {
	public function index() {

		$this->load->model('yandex/yandex');

		$shop = $this->model_yandex_yandex->getShop();
		$categories = $this->model_yandex_yandex->getCategories();
		$offers = $this->model_yandex_yandex->getOffers();

		$currencyId = "";

		$yml = "";

		header("Content-type: text/xml; charset=UTF-8");

		$yml .= "<?xml version='1.0' encoding='UTF-8'?><!DOCTYPE yml_catalog SYSTEM 'shops.dtd'><yml_catalog date='" . date("Y-m-d h:m") . "'>";

		$yml .= "<shop>";
		$yml .= "<name>" . $shop['name'] . "</name>";
		$yml .= "<company>" . $shop['company'] . "</company>";
		$yml .= "<url>" . $shop['url'] . "</url>";
		$yml .= "<currencies>";

		foreach ($shop['currencies'] as $c) {
			if($c['rate'] == 1){
				$currencyId = $c['code'];
				$rate = $c['rate'];
			} else {
				$rate = "CBRF";
			} 

			$yml .= "<currency id='" . $c['code'] . "' rate='" . $rate. "'/>";
		}

		$yml .= "</currencies>";

		$yml .= "<categories>";

		foreach ($categories as $c) {
			if($c['parent_id'] > 0){
				$parent_id = " parentId='" . $c['parent_id'] . "'";
			} else {
				$parent_id = "";
			}
			$yml .= "<category id='" . $c['category_id'] . "'" . $parent_id . ">" . $c['name'] . "</category>";
		}

		$yml .= "</categories>";

		$yml .= "<offers>";

		foreach ($offers as $product_id => $offer) {
			$yml .= "<offer id='" . $product_id . "' available='true'>";
			$yml .= "<url>" . $offer['url'] . "</url>";

			if(!$offer['special']){
				$yml .= "<price>" . $offer['price'] . "</price>";
			} else {
				$yml .= "<price>" . $offer['special'] . "</price>";
			}
			
			$yml .= "<currencyId>" . $currencyId . "</currencyId>";

			foreach ($offer['pictures'] as $pic) {
				$yml .= "<picture>" . $pic . "</picture>";
			}

			$yml .= "<name>" . $offer['name'] . "</name>";
			$yml .= "<description>" . $offer['description'] . "</description>";

			foreach ($offer['attribute_groups'] as $key => $attribute_group) {
				foreach ($attribute_group['attribute'] as $attribute) { 
					$yml .= "<param name='" .  $attribute['name'] . "'>" . $attribute['text'] . "</param>";
				}
			}
			$yml .= "</offer>";
		}

		$yml .= "</offers>";

		$yml .= "</shop>";
		$yml .= "</yml_catalog>";

		print $yml;
	}
}