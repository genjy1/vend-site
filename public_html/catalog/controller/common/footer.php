<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['scripts'] = $this->document->getScripts('footer');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		if(isset($this->request->get['_route_']) && $this->request->get['_route_'] == "biblioteka-vendora/"){
			$data['lib'] = "#";
		} else {
			$data['lib'] = "biblioteka-vendora/";
		}

		if ($this->request->server['HTTPS']) {
			$origin_server = $this->config->get('config_ssl');
		} else {
			$origin_server = $this->config->get('config_url');
		}

		$data['origin_server'] = $origin_server;

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/account', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['search'] = $this->load->controller('common/search');

    $this->document->addScript('/catalog/view/javascript/jqueryformstyler/dist/jquery.formstyler.js');
    $this->document->addStyle('/catalog/view/javascript/jqueryformstyler/dist/jquery.formstyler.css');
    $this->document->addStyle('/catalog/view/javascript/jqueryformstyler/dist/jquery.formstyler.theme.css');

    $data['help'] = [];

    // $categories = [16, 171, 279, 304, 205];
    $categories = [16, 332, 171, 279, 205];

    $this->load->model('catalog/category');

    foreach ($categories as $key => $cat_id) {
      $category_info = $this->model_catalog_category->getCategory($cat_id);

      $data['help']['categories'][] = [
        'name' => $category_info['name'],
        'href' => $this->url->link('product/category', 'path=' . $cat_id, true, true),
      ];
    }

    $data['categories'] = [];

    $filter_data = array(
      'order' => "cd.name",
    );

    $exclude = array(309,266, 293);
    $rename = array(
      'Штучный товар' => "Другой товар",
      "Кофейные автоматы" => "Кофе",
      "Снековые автоматы" => "Снеки",
    );



    $categories = $this->model_catalog_category->getCategories(2, $filter_data);

    foreach ($categories as $category) {

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

    $bu = array(
      'category_id' => 266,
      'name'     => "Б/У",
      'children' => [],
      'href'     => $this->url->link('product/category', 'path=' . 266),
    );

    array_unshift($data['categories'], $bu);

    $tmp = array_pop($data['categories']);
    $tmp1 = array_pop($data['categories']);
    $data['categories'][] = $tmp;
    $data['categories'][] = $tmp1;

    // $last = array_pop($data['categories']);
    // array_unshift($data['categories'], $last);

    $data['feedback'] =$this->load->view('common/feedback', []);
    $data['scripts'] =$this->load->view('common/scripts', []);


		return $this->load->view('common/footer', $data);
	}
}
