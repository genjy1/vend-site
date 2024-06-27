<?php
class ControllerCatalogPost extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_post->addPost($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));


		$this->load->model('catalog/post');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_post->editPost($this->request->get['post_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $post_id) {
				$this->model_catalog_post->deletePost($post_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('catalog/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/post/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['posts'] = array();

		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$post_total = $this->model_catalog_post->getTotalPosts();

		$results = $this->model_catalog_post->getPosts($filter_data);

		$this->load->model('tool/image');

		foreach ($results as $result) {

		if (isset($result['image']) && is_file(DIR_IMAGE . $result['image'])) {
			$thumb = $this->model_tool_image->resize($result['image'], 100, 100);
		} elseif (!empty($result) && is_file(DIR_IMAGE . $result['image'])) {
			$thumb = $this->model_tool_image->resize($result['image'], 100, 100);
		} else {
			$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
			$data['posts'][] = array(
				'post_id' => $result['post_id'],
				'image'   => $thumb,
				'title'          => $result['title'],
				'post_date'     => $result['post_date'],
				'edit'           => $this->url->link('catalog/post/edit', 'token=' . $this->session->data['token'] . '&post_id=' . $result['post_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_date'] = $this->language->get('column_date');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_image'] = $this->language->get('column_image');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';


		$pagination = new Pagination();
		$pagination->total = $post_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($post_total - $this->config->get('config_limit_admin'))) ? $post_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $post_total, ceil($post_total / $this->config->get('config_limit_admin')));


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/post_list.tpl', $data));
	}

	protected function getForm(){
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['post_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_bottom'] = $this->language->get('entry_bottom');
		$data['entry_post_date'] = $this->language->get('entry_post_date');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_date'] = $this->language->get('entry_date');

		$data['help_keyword'] = $this->language->get('help_keyword');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');

		$data['categories_entry'] = $this->language->get('categories_entry');

		$data['ckeditorplus_language'] = 'en';
              $data['ckeditorplus_skin'] = 'icy_orange';
              $data['ckeditorplus_status'] = 0;
			  $data['ckeditorplus_height'] = $this->config->get('ckeditorplus_height') ? $this->config->get('ckeditorplus_height') : '300';

              if ($this->config->get('ckeditorplus_status')) {
                  $data['ckeditorplus_status'] = $this->config->get('ckeditorplus_status');
                  if ($this->config->get('ckeditorplus_language')) {$data['ckeditorplus_language'] = $this->config->get('ckeditorplus_language');}
                  if ($this->config->get('ckeditorplus_skin')) {$data['ckeditorplus_skin'] = $this->config->get('ckeditorplus_skin');}
              } 


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}
		
		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['post_id'])) {
			$data['action'] = $this->url->link('catalog/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/post/edit', 'token=' . $this->session->data['token'] . '&post_id=' . $this->request->get['post_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$post_info = $this->model_catalog_post->getPost($this->request->get['post_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['post_description'])) {
			$data['post_description'] = $this->request->post['post_description'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['post_description'] = $this->model_catalog_post->getPostDescriptions($this->request->get['post_id']);
		} else {
			$data['post_description'] = array();
		}

		$this->load->model('setting/store');
		$this->load->model('catalog/product');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['post_store'])) {
			$data['post_store'] = $this->request->post['post_store'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['post_store'] = $this->model_catalog_post->getPostStores($this->request->get['post_id']);
		} else {
			$data['post_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($post_info)) {
			$data['keyword'] = $post_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

        if (isset($this->request->post['form'])) {
            $data['form'] = $this->request->post['form'];
        } elseif (!empty($post_info)) {
            $data['form'] = $post_info['form'];
        } else {
            $data['form'] = false;
        }

		if (isset($this->request->post['timer'])) {
			$data['timer'] = $this->request->post['timer'];
		} elseif (!empty($post_info)) {
			$data['timer'] = $post_info['timer'];
		} else {
			$data['timer'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($post_info)) {
			$data['image'] = $post_info['image'];
		} else {
			$data['image'] = '';
		}

		$data['products'] = array();

		if (isset($this->request->post['products'])) {
			$products = $this->request->post['products'];
		} elseif (!empty($post_info)) {
			$products = $post_info['products'];
		} else {
			$products = array();
		}

		foreach ($products as $key => $product_id) {
			$product = $this->model_catalog_product->getProduct($product_id);
			$data['products'][] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name']
			);
		}

    $data['categories'] = array();

    if(!empty($post_info)) {
      $data['categories'] = $post_info['categories'];
    } else {
      $data['categories'] = array();
    }


		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($post_info) && is_file(DIR_IMAGE . $post_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($post_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		// $data['categories'] = array();

		// $categories = $this->model_catalog_post->getCategories();

		// foreach ($categories as $key => $value) {
		// 	$description = $this->model_catalog_post->getCategoryDescription($value['category_id']);
		// 	$data['categories'][] = array(
		// 		'category_id' => $value['category_id'],
		// 		'name' => $description['name'],
		// 		);
		// }

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($post_info)) {
			$data['status'] = $post_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['post_date'])) {
			$data['post_date'] = $this->request->post['post_date'];
		} elseif (!empty($post_info)) {
			$data['post_date'] = $post_info['post_date'];
		} else {
			$data['post_date'] = '';
		}

		if (isset($this->request->post['post_layout'])) {
			$data['post_layout'] = $this->request->post['post_layout'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['post_layout'] = $this->model_catalog_post->getPostLayouts($this->request->get['post_id']);
		} else {
			$data['post_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/post_form.tpl', $data));
	}

	public function category(){
		$this->load->model('catalog/post');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCategoriesForm()) {
			$this->model_catalog_post->editCategories($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('catalog/post/category', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->language('catalog/post');

		$this->document->setTitle($this->language->get('category_title'));

		$data['category_title'] = $this->language->get('category_title');
		
		$data['text_form'] = !isset($this->request->get['post_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');

		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['text_add'] = $this->language->get('text_add');
		$data['text_remove'] = $this->language->get('text_remove');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');


		$data['categories'] = array();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/post', 'token=' . $this->session->data['token'], 'SSL')
		);
		

		$data['action'] = $this->url->link('catalog/post/category', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'], 'SSL');


		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$categories = $this->model_catalog_post->getCategories();
// print_r($categories);
		foreach ($categories as $key => $value) {
			if($value['image'] != ''){
				$thumb = $this->model_tool_image->resize($value['image'], 100, 100);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			$description = $this->model_catalog_post->getCategoryDescriptions($value['category_id']);
			$data['categories'][] = array(
				'category_id' => $value['category_id'],
				'category_description' => $description,
				'image' => $value['image'],
				'thumb' => $thumb,
				'sort_order' => $value['sort_order'],
				);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/post_category.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['post_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['post_id']) && $url_alias_info['query'] != 'post_id=' . $this->request->get['post_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['post_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');

		foreach ($this->request->post['selected'] as $post_id) {
			if ($this->config->get('config_account_id') == $post_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}

		}

		return !$this->error;
	}

	protected function validateCategoriesForm(){
		// print_r($this->request->post);exit;
		foreach ($this->request->post['category'] as $language_id => $value) {
			foreach ($value['category_description'] as $key => $description) {
				if ((utf8_strlen($description['name']) < 1) || (utf8_strlen($description['name']) > 128)) {
					$this->error['name'][$language_id] = $this->language->get('error_name');
				}
			}
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/post');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_post->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}