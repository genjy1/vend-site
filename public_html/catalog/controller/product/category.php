<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->document->addScript('/catalog/view/javascript/owlcarousel/owl.carousel.js');
		$this->document->addStyle('/catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');

		$this->document->addScript('/catalog/view/javascript/fancybox/jquery.fancybox.js');
		// $this->document->addScript('/catalog/view/javascript/fancybox/helpers/jquery.fancybox-media.js');
		$this->document->addStyle('/catalog/view/javascript/fancybox/jquery.fancybox.css');
		

		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
      $pathx = explode('_', $this->request->get['path']);
      $pathx = end($pathx);
      $this->document->addLink($this->url->link('product/category', 'path=' . $pathx ), 'canonical');
    } else {
      $page = 1;
    }

		$data['filter'] = $this->load->controller('module/filter');

		$url = '';

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}


		if (isset($this->request->get['price_filter'])) {
			$price_filter = $this->request->get['price_filter'];
			$url .= '&price_filter=' . $this->request->get['price_filter'];
		} else {
			$price_filter = '';
		}


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}



		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}



		$this->session->data['last_category_id'] = $category_id;

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if($category_id != 3){
			$data['avtomat'] = true;
		} else {
			$data['avtomat'] = false;
		}


		if ($category_info) {

      if(!$category_info['meta_title']){
        $category_info['meta_title'] = $category_info['name'];
      }

			$title = $this->subdomains->getCategoryTitle($category_info['meta_title'], $category_info['name']);
			$this->document->setTitle($title);
			$description = $this->subdomains->getCategoryDescription($category_info['meta_description'], $category_info['name']);
			$this->document->setDescription($description);
			$this->document->setKeywords($category_info['meta_keyword']);

			$category_name = $this->subdomains->getCategoryName($category_info['name']);

			$data['heading_title'] = $category_name;

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => /*'javascript:void(0)',*/$this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

      // $data['breadcrumbs'][count($data['breadcrumbs']) - 1]['href'] = 'javascript:void(0)';

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			// $data['description'] = $category_info['description'];

			$data['compare'] = $this->url->link('product/compare');

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}


      


			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);
				$children = array();
				$child = $this->model_catalog_category->getCategories($result['category_id']);

				foreach ($child as $key => $value) {
					$children[] = array(
						'name' => $value['name'],
						'href' => $this->url->link('product/category', 'path=' . $value['category_id'], true, true),
						);
				}
// echo $this->request->get['path'] . " ";
				$data['categories'][] = array(
					'name' => $result['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
					'children' => $children,
				);
			}

			$data['products'] = array();

			if(isset($this->request->get['relate'])){
				$relate = $this->request->get['relate'];
			} else {
				$relate = false;
			}

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'filter_sub_category' => true,
				'sort'               => $sort,
				'order'              => $order,
				'relate'             => $relate,
				'start'              => ((int)$page - 1) * (int)$limit,
				'limit'              => $limit,
				'price_filter'       => $price_filter,
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);
// print_r($results);
			foreach ($results as $result) {
				if ($result['image']) {
					if(file_exists(DIR_IMAGE . $result['image'])){
						$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
					}
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


				$promos = $this->model_catalog_product->getPromo($result['product_id']);
				$product_promo = array();
				if($promos){
					foreach ($promos as $k => $promo) {

					$product_promo[$k]['text'] = $promo['name'];
					$product_promo[$k]['image'] = $this->model_tool_image->resize($promo['image'], (int)$promo['width'], (int)$promo['height']);
					$product_promo[$k]['usename'] = $promo['usename'];

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
							$position = "left: 17px; bottom: 135px";
							break;
						case '8':
							$position = "left: 50%; bottom: 135px; margin-left:-".(int)($promo['width']/2)."px";
							break;
						case '9':
							$position = "right: 17px; bottom: 135px";
							break;
						default:
							$position = "left: 17px; top: 15px";
							break;
					}

					$product_promo[$k]['position'] = $position;
					$product_promo[$k]['spanposition'] = "position: absolute;left: 0px;top: 19px;text-align: center;width: 120px";
				}
				} else {
					$product_promo = array();
				}
				if($result['description'] != ''){
					$description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..';
				} else {
					$description = '';
				}
				//if ($result['product_id'] == 1438) print_r($promos);
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => $description,
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'promos' => $product_promo,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
			}

			if($this->model_catalog_category->inSpares($category_id)){
				$maps = $this->model_catalog_category->getMaps($category_id);
				foreach ($maps as $k => $m) {
					foreach ($m['items'] as $key => $item) {

						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$item['product_id'] . "'");
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$item['product_id'] . "'");

						// echo $item['image'] . " \n";
						$image = $this->model_tool_image->resize($item['image'], 100, 100);
						$bigimage =  $this->model_tool_image->resize($item['image'], 600, 600);
						$m['items'][$key]['image'] = $image;
						$m['items'][$key]['bigimage'] = $bigimage;
						$m['items'][$key]['description'] = utf8_substr(strip_tags(html_entity_decode($item['description'], ENT_QUOTES, 'UTF-8')), 0, 60);
						$m['items'][$key]['price'] = $this->currency->format($this->tax->calculate($item['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					}

					$thumb = $this->model_tool_image->resize($m['image'], 370, 712);
					$mini = $this->model_tool_image->resize($m['image'], 80, 80);
					$data['maps'][] = array(
						'thumb' => $thumb,
						'image' => $m['image'],
            'pins' => $m['pins'],
						'mini' => $mini,
						'sort_order' => $m['sort_order'],
						'map_id' => $m['map_id'],
						'items' => $m['items'],
						);
				}
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}


			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			if(isset($this->request->get['relate'])){
				$urlrelate = "&relate=" . $this->request->get['relate'];
			} else {
				$urlrelate = "";
			}

      $data['youtube'] = $this->model_catalog_category->getYoutubeBanner($category_id);

      if($data['youtube'] && $data['youtube']['link'] == ''){
        $data['youtube']['link'] = "#callme";
      }

      // var_dump($data['youtube']);

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}' . $urlrelate );

			$data['pagination'] = $pagination->render();

			$limit = (int)$limit;
			$page = (int)$page;

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1), true), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['issub'] = $this->subdomains->getSubDomain();

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if($this->model_catalog_category->inSpares($category_id)){
				$this->response->setOutput($this->load->view('product/spares2', $data));
			}elseif($category_id == 5 && !isset($this->request->get['relate'])){
				$this->response->setOutput($this->load->view('product/spares', $data));
			} else {
				if($category_id == 2 && !isset($this->request->get['price_filter']) && !isset($this->request->get['filter'])){
					$this->response->setOutput($this->load->view('product/catalog', $data));
				} else {
					$this->response->setOutput($this->load->view('product/category', $data));
				}
			}
		} else {

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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
				'href' => $this->url->link('product/category', $url)
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
}
