<?php
class ControllerModuleFilter extends Controller {
	public function index() {

		$this->document->addScript("catalog/view/javascript/slider/bootstrap-slider.min.js");
		$this->document->addStyle("catalog/view/javascript/slider/css/bootstrap-slider.css");

		$data['filtered'] = "";
		$data['price_filter'] = "";

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = end($parts);
		} else {
			$category_id = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('module/filter');

		$data['category_id'] = $category_id;

		$data['min'] = $this->model_catalog_category->getMinPriceInCategory($category_id);
		$data['max'] = $this->model_catalog_category->getMaxPriceInCategory($category_id);

		if(($data['min'] == $data['max']) || ($category_id == 3) || ($category_id == 255) || ($category_id == 253)  || ($category_id == 278) ){
			$data['showprice'] = false;
		} else {
			$data['showprice'] = true;
		}

		if (isset($this->request->get['filter'])) {
			$data['filter'] = $this->request->get['filter'];
		}
		if (isset($this->request->get['price_filter'])) {
			$data['price_filter'] = $this->request->get['price_filter'];
			$data['price_filter'] = str_replace("/", "", $data['price_filter']);
			$price_filter = explode("_", $data['price_filter']);
			$data['start'] = $price_filter[0];
			$data['end'] = $price_filter[1];
		}else{
			$data['start'] = $data['min'];
			$data['end'] = $data['max'];
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$exclude = array(309,266, 293);
		$rename = array(
			'Штучный товар' => "Другой товар",
			"Кофейные автоматы" => "Кофе",
			"Снековые автоматы" => "Снеки",
			);


		$filter_category_id = 2;
		$data['type'] = true;

		if($category_id == 3 || $category_id == 255 || $category_id == 278 || $category_id == 253){
			$filter_category_id = 3;
			$data['type'] = false;
		}

		if($category_id == 250 || $category_id == 60){
			$data['isfilter'] = false;
		} else {
			$data['isfilter'] = true;
		}

		$filter_data = array(
			'order' => "cd.name",
			);

		$categories = $this->model_catalog_category->getCategories($filter_category_id, $filter_data);

		foreach ($categories as $category) {
			// if (!$category['top'] || $category['category_id'] == 242) {
			if(in_array($category['category_id'], $exclude)){
				continue;
			}
			if (1) {

				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					if(!$child['top']){
						continue;
					}
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'],// . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
				// немного костылей
				if($category['category_id'] == 66){
					continue;
					$href = $this->url->link('information/information', 'information_id=' . 10);
				} else {
					$href = $this->url->link('product/category', 'path=2_' . $category['category_id']);
				}

				if(isset($rename[strtoupper($category['name'])])){
					$name = $rename[strtoupper($category['name'])];
				} else {;
					$name = $category['name'];
				}

				// Level 1
				$data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'     => $name,
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $href,
				);
			}
		}

		$tmp = array_pop($data['categories']);
		$tmp1 = array_pop($data['categories']);
		$data['categories'][] = $tmp;
		$data['categories'][] = $tmp1;

		$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}


		$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

		return $this->load->view('module/filter', $data);

	}

}