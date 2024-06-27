<?php
class ControllerPostPost extends Controller {
	public function index() {
		$this->load->language('post/post');

		$this->load->model('post/post');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
      $pathx = explode('_', $this->request->get['path']);
      $pathx = end($pathx);
      $this->document->addLink($this->url->link('product/category', 'path=' . $pathx ), 'canonical');
    } else {
      $page = 1;
    }

		$data['heading_title'] = $this->language->get('heading_title');

		$post_category_id = $this->model_post_post->getPostCategory($this->request->get['post_id']);

		// $this->document->addScript('catalog/view/javascript/fcm/compiled/flipclock.min.js');
		// $this->document->addStyle('catalog/view/javascript/fcm/compiled/flipclock.css');

		// $this->document->addScript('catalog/view/javascript/moc/demo/js/kinetic.js');
		// $this->document->addScript('catalog/view/javascript/moc/jquery.final-countdown.min.js');
		// $this->document->addStyle('catalog/view/javascript/moc/bootstrap.min.css');

		$this->document->addScript('catalog/view/javascript/timer/TimeCircles.js');
		$this->document->addStyle('catalog/view/javascript/timer/TimeCircles.css');


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('post/posts&post_category_id='.$post_category_id)
		);

		$data['button_continue'] = $this->language->get('button_continue');

		$this->load->model('tool/image');

		$data['post_id'] = $this->request->get['post_id'];

		$result = $this->model_post_post->getPost($this->request->get['post_id']);
		if(empty($result)){
			$this->response->redirect($this->url->link('post/posts'));
		}

		if(isset($result['post_date'])) {
			$date = explode("-", $result['post_date']);
			$data['date'] = $date[2].".".$date[1].".".$date[0];

		} else {
			$data['date'] = "";
		}
		
		if($result['image']){
			$data['image'] = $this->model_tool_image->resize($result['image'], 760, 400);
		} else {
			$data['image'] = '';
		}

        if($result['form']){
            $data['form'] = true;
        } else {
            $data['form'] = false;
        }


		$this->document->addLink($this->url->link('post/post', '&post_id='.$this->request->get['post_id']), "canonical");

		if(isset($result['meta_title']) && $result['meta_title'] != ''){
			$title = $result['meta_title'];
		} else {
			$title = $result['title'];
		}

		$this->document->setTitle($title);

		$data['title'] = $result['title'];

		$data['timer'] = explode("-", $result['timer']);
		if(count($data['timer'])>2){
			$data['time_start'] = mktime(0,0,0,(int)$date[1],((int)$date[2] + 1),(int)$date[0]);
			$data['time_now'] = time();
			$data['time_end'] = mktime(0,0,0,$data['timer'][1],($data['timer'][2] + 1),$data['timer'][0]);
		} else {
			$data['timer'] = '';
			$data['time_start'] = '';
			$data['time_now'] = '';
			$data['time_end'] = '';
		}

		$data['time_end'] = $result['timer'];


		$data['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');


		$data['breadcrumbs'][] = array(
			'text' => $data['title'],
			'href' => $this->url->link('post/post', 'post_id=' . $this->request->get['post_id']),
		);

		$this->load->model('catalog/product');

		$data['products'] = array();

		foreach ($result['products'] as $key => $product_id) {
			$result = $this->model_catalog_product->getProduct($product_id);

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

				// $top = $this->model_catalog_product->getTopCategories($result['category_id']);
				// if(!$top){
				// 	continue;
				// }

				$data['products'][] = array(
					'product_id'  => $product_id,
					'thumb'       => $image,
					'name'        => utf8_substr(strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')), 0, 60) . '..',
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $product_id)
				);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/post/post.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/post/post.tpl', $data));
				
		} else {
			$this->response->setOutput($this->load->view('/post/post.tpl', $data));
		}
	}
}