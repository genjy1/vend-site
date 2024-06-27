<?php
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/product');

		$this->document->addScript('catalog/view/javascript/fancybox/jquery.fancybox.js');
		// $this->document->addScript('catalog/view/javascript/fancybox/helpers/jquery.fancybox-media.js');
		$this->document->addStyle('catalog/view/javascript/fancybox/jquery.fancybox.css');
		
		$this->document->addScript('catalog/view/javascript/slider.js');

		$this->document->addScript('catalog/view/javascript/owlcarousel/owl.carousel.js');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');

		$this->document->addScript('catalog/view/javascript/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery-ui-1.9.2.custom/js/css/base/jquery-ui-1.9.2.custom.min.css');

		$this->document->addScript('catalog/view/javascript/del.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$user_ip = ($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getenv("HTTP_X_FORWARDED_FOR");

		// $data['city'] = $this->geoip->getCity($user_ip);

		// if($data['city'] == "Москва"){
			$data['city'] = "г. Москва";
		// }

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		if(isset($this->request->get['variant'])){
			$variant = $this->request->get['variant'];
		} else {
			$variant = false;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			if ($product_info['manual'] != '') {
				$manuals = unserialize($product_info['manual']);
				$manual_names = unserialize($product_info['manual_name']);

				if(is_array($manual_names)){
					foreach ($manuals as $key => $manual) {
						if($manual == "") continue;
						$data['downloads'][] = array(
							'date_added' => false,
							'name'       => $manual_names[$key],
    	        			'ext'        => '',
							'size'       => '',
							'href'       => $manual,
						);
					}
				}

			}

			$title = $product_info['meta_title'] != ''?$product_info['meta_title']:$product_info['name'];

			$this->document->setTitle($title);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $product_info['name'];

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			if(!$variant){
				$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
				if(substr($data['description'], 0, 2) != "<p"){
					$data['description'] = "<p>" . $data['description'] . "</p>";
				}
			} else {
				$data['description'] = "";
			}

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			if ($product_info['video'] != '') {
				$data['video'] = $product_info['video'];
			} else {
				if($this->config->get("config_video_url") != ""){
					$data['video'] = $this->config->get("config_video_url");
				} else{
					$data['video'] = false;
				}
			}

			$data['video'] = preg_replace("/(watch\?v=)/Usi", "embed/", $data['video']);

			if(strpos($data['video'], "&")){
				$data['video'] = explode("&", $data['video']);
				$data['video'] = $data['video'][0];
			}

			if ($product_info['v3d'] != '') {
				$data['v3d'] = htmlspecialchars_decode($product_info['v3d']);
			} else {
				$data['v3d'] = false;
			}
			
			if ($product_info['manual'] != '') {
				$data['manual'] = $product_info['manual'];
			} else {
				$data['manual'] = false;
			}

			$this->load->model('tool/image');

			$promos = $this->model_catalog_product->getPromo($this->request->get['product_id']);

				if(!empty($promos)){
					foreach ($promos as $k => $promo) {

					$data['promo'][$k]['text'] = $promo['name'];
					$data['promo'][$k]['image'] = $this->model_tool_image->resize($promo['image'], (int)$promo['width'], (int)$promo['height']);
					$data['promo'][$k]['usename'] = $promo['usename'];

					switch ($promo['position']) {
						case '1':
							$position = "left: 17px; top: 15px";
							break;
						case '2':
							$position = "left: 50%; top: 15px; margin-left:-".(int)($promo['width']/2)."px";
							break;
						case '3':
							$position = "right: 17px; top: 15px";
							break;
						case '4':
							$position = "left: 17px; top: 50%; margin-top:-".(int)($promo['height']/2)."px";
							break;
						case '5':
							$position = "left: 50%; top: 50% ; margin-left:-".(int)($promo['width']/2)."px;; margin-top:-".(int)($promo['height']/2)."px";
							break;
						case '6':
							$position = "right: 17px; top: 50%; margin-top:-".(int)($promo['height']/2)."px";
							break;
						case '7':
							$position = "left: 17px; bottom: 35px";
							break;
						case '8':
							$position = "left: 50%; bottom: 35px; margin-left:-".(int)($promo['width']/2)."px";
							break;
						case '9':
							$position = "right: 17px; bottom: 35px";
							break;
						default:
							$position = "left: 17px; top: 15px";
							break;
					}

					$data['promo'][$k]['position'] = $position;
					$data['promo'][$k]['spanposition'] = "position: absolute;left: 20px;top: 18px;";
				}
				} else {
					$data['promo'] = array();
				}
			if($variant){
				$product_info['image'] = $this->model_catalog_product->getVariantImage($variant);
			}

			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));

        $this->document->addGraph('image', $data['popup']);
			} else {
				$data['popup'] = '';
			}

			if ($product_info['image']) {
				$data['popup2'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
			} else {
				$data['popup2'] = '';
			}

			if ($product_info['image']) {
				if(file_exists(DIR_IMAGE . $product_info['image'])){
						$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
						$data['mini'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
					} else {
						$data['thumb'] = $this->model_tool_image->resize("placeholder.png", $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
						$data['mini'] = $this->model_tool_image->resize("placeholder.png", 100, 100);
					}
				
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			$data['images'][] = array(
				'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
				'popup2' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
				'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
			);

			foreach ($results as $result) {
				if($variant){
					break;
				}
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
					'popup2' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
				);
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price') && $product_info['price'] > 0) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['price'] = false;
			}

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'description'             => $option_value['description'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 70, 70),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}


			$check_ids = array();
			$remove = array();

			foreach ($data['options'] as $key => $option) {
				if(!isset($check_ids[$option['option_id']])){
					$check_ids[$option['option_id']] = $key;
				} else {
					$arr_orig = $data['options'][$check_ids[$option['option_id']]]['product_option_value'];
					$arr_copy = $option['product_option_value'];
					$data['options'][$check_ids[$option['option_id']]]['product_option_value'] = array_merge($arr_orig, $arr_copy);
					$remove[] = $key;
				}
			}

			foreach ($remove as $key) {
				unset($data['options'][$key]);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
// print_r($results);
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 255,380);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$top = $this->model_catalog_product->getTopCategories($result['category_id']);
				if(!$top){
					$top = "related";
				}
				$data['products'][$top][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => utf8_substr(strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')), 0, 60) . '..',
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
// print_r($data['products']);
			if(isset($category_id)){
				$data['category_id'] = $category_id;
			} else {
				$data['category_id'] = 0;
			}


			$product_categories = $this->model_catalog_product->getProductCategories($product_id);

			// print_r($product_categories);

			if(in_array(2, $product_categories)){
				$data['avtomat'] = true;
			} else {
				$data['avtomat'] = false;
				// $data['video'] = false;
			}

			$data['similar'] = array();

			if(isset($this->session->data['last_category_id'])){
				$category_id = $this->session->data['last_category_id'];
			} else {
				if(isset($product_categories[0])){
					$category_id = $product_categories[0];
				} else {
					$category_id = 0;
				}
			}

			if(!in_array($category_id, $product_categories)){
				$category_id = isset($product_categories[0]) ? $product_categories[0] : 0 ;//$product_info['category_id'];
			}

			if($category_id){
				$category_info = $this->model_catalog_category->getCategory($category_id);

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $category_id . $url)
				);
			}
// echo $category_id;
			$filter = array(
				'filter_category_id' => $category_id,
				'exclude_id' => $this->request->get['product_id'],
				'similar' => 1,
				);
			if($this->model_catalog_product->inVariants($this->request->get['product_id']) && !isset($this->session->data['last_category_id'])){ 
				$results = $this->model_catalog_product->getNeighborsVariants($this->request->get['product_id']);
			} else {
				$results = $this->model_catalog_product->getProducts($filter);
			}
echo "<div style='display:'none'>";
// print_r($results);
echo "</div>";
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 217, 315);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price') && $result['price'] > 0) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}


				$promo = false;//= $this->model_catalog_product->getPromo($result['product_id']);

				if($promo){
					$data['promo']['text'] = $promo['name'];
					$data['promo']['image'] = $this->model_tool_image->resize($promo['image'], (int)$promo['width'], (int)$promo['height']);
					$data['promo']['usename'] = $promo['usename'];

					switch ($promo['position']) {
						case '1':
							$position = "left: 17px; top: 15px";
							break;
						case '2':
							$position = "left: 50%; top: 15px; margin-left:-".(int)($promo['width']/2)."px";
							break;
						case '3':
							$position = "right: 17px; top: 15px";
							break;
						case '4':
							$position = "left: 17px; top: 50%; margin-top:-".(int)($promo['height']/2)."px";
							break;
						case '5':
							$position = "left: 50%; top: 50% ; margin-left:-".(int)($promo['width']/2)."px;; margin-top:-".(int)($promo['height']/2)."px";
							break;
						case '6':
							$position = "right: 17px; top: 50%; margin-top:-".(int)($promo['height']/2)."px";
							break;
						case '7':
							$position = "left: 17px; bottom: 110px";
							break;
						case '8':
							$position = "left: 50%; bottom: 110px; margin-left:-".(int)($promo['width']/2)."px";
							break;
						case '9':
							$position = "right: 17px; bottom: 110px";
							break;
						default:
							$position = "left: 17px; top: 15px";
							break;
					}

					$data['promo']['position'] = $position;
					$data['promo']['spanposition'] = "position: absolute;left: 20px;top: 18px;";
				} else {
					// $data['promo'] = false;
				}


				$data['similar'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => utf8_substr(strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')), 0, 60) . '..',
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'promo' => $data['promo'],
					'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] . $url)
				);
			}


			$data['examples'] = array();

			$results = $this->model_catalog_product->getExampleImages($product_id);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 300, 235);
					$full = $this->model_tool_image->resize($result['image'], 600, 600);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
					$full = $image;
				}
				$data['examples'][] = array(
					'thumb'  => $image,
					'full'   => $full,
				);
			}


			$data['variants'] = array();

			$results = $this->model_catalog_product->getVariants($product_id, $category_id);

			foreach ($results as $result) {
				// print_r($result);
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 226, 335);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
				$data['variants'][] = array(
					'id'          => $result['product_id'],
					'thumb'       => $image,
					'text'        => $result['name'],
					'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'])
				);
			}
	

      $this->load->model('catalog/tags');

      $data['tags'] = [];

      $tags = $this->model_catalog_tags->getProductTags($product_id);

      if ($tags) {

        foreach ($tags as $tag) {
          $data['tags'][] = array(
            'tag'  => trim($tag['name']),
            'href' => $this->url->link('product/tags', 'tag_id=' . trim($tag['tag_id']))
          );
        }
      }


			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$this->load->model('catalog/category');
			$category_info = $this->model_catalog_category->getCategory($product_info['category_id']);
			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => /*'javascript:void(0)',**/$this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);
			if(isset($data['category_description'])){
				$data['category_description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			} else {
				$data['category_description'] = '';
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/product', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => /*'javascript:void(0)',*/$this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function update(){
		$json = array();

		$json['total'] = $this->request->post;

		$this->load->model('catalog/product');

		if(isset($this->request->post['option'])){
			$options = $this->request->post['option'];
		} else {
			$options = array();
		}
		
		$price = 0;
		$product_price = 0;

		foreach ($this->model_catalog_product->getProductOptions($this->request->post['product_id']) as $option) {

			foreach ($option['product_option_value'] as $option_value) {
				if(!$this->in_multiarray($option_value['product_option_value_id'], $options)){
					continue;
				}

				if (true) {
					if ($option_value['price']) {
						// $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
						$price += $option_value['price'];
					} else {
						$price = false;
					}

				}
			}

		}


		$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

		if ($product_info) {
			$product_price = $product_info['price'];
		}

		$price = $product_price + $price;

		$price = $this->currency->format($this->tax->calculate($price, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

		$json['price'] = $price;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function in_multiarray( $e, $a )
{

	foreach ($a as $key => $v) {
		if($v == $e){
			return true;
		} elseif(is_array($v)){
			if($this->in_multiarray($e, $v)){
				return true;
			}
		}
	}

	return false;

}
}

// if ($product_info['manual'] != '') {
// 				$manuals = unserialize($product_info['manual']);
// 				$manual_names = unserialize($product_info['manual_name']);

// 				foreach ($manuals as $key => $manual) {
// 					if($manual == "") continue;
// 					$data['downloads'][] = array(
// 						'date_added' => false,
// 						'name'       => $manual_names[$key],
//             			'ext'        => '',
// 						'size'       => '',
// 						'href'       => $manual,
// 					);
// 				}

// 			}