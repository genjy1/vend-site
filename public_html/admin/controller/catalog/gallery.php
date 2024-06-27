<?php
class ControllerCatalogGallery extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if(isset($this->request->files['printfiles']) && !empty($this->request->files['printfiles'])){
				$this->uploadImages();
			}
			// print_r($this->request->post);
			// exit;
			$this->model_catalog_gallery->addSet($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if(isset($this->request->files['printfiles']) && !empty($this->request->files['printfiles'])){
				$this->uploadImages();
			}

			$this->model_catalog_gallery->editSet($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_gallery->delete($id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		$this->load->model('catalog/gallery');
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = $this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/gallery/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/gallery/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['gallery_sets'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$gallery_total = $this->model_catalog_gallery->getTotal($filter_data);

		$results = $this->model_catalog_gallery->getSets($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 80, 80);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 80, 80);
			}
			$data['gallery_sets'][] = array(
				'id' => $result['id'],
				'image'      => $image,
				'name'   => $result['name'],
				'edit'       => $this->url->link('catalog/gallery/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_add'] = $this->language->get('text_add');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_delete'] = $this->language->get('text_delete');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');


		$data['token'] = $this->session->data['token'];

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


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $gallery_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gallery_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gallery_total - $this->config->get('config_limit_admin'))) ? $gallery_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gallery_total, ceil($gallery_total / $this->config->get('config_limit_admin')));


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_name'] = $this->language->get('text_name');
		$data['name_placeholder'] = $this->language->get('name_placeholder');
		$data['text_image'] = $this->language->get('text_image');

		$data['description_placeholder'] = $this->language->get('description_placeholder');
		$data['text_description'] = $this->language->get('text_description');

		$data['entry_additional_image'] = $this->language->get('entry_additional_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_caption'] = $this->language->get('entry_caption');
		$data['entry_value_videoid'] = $this->language->get('entry_value_videoid');
		$data['entry_type'] = $this->language->get('entry_type');

		$data['img_type'] = $this->language->get('img_type');
		$data['video_type'] = $this->language->get('video_type');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_image_add'] = $this->language->get('button_image_add');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('catalog/gallery/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/gallery/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {


		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if(isset($this->request->get['id'])){
			$info = $this->model_catalog_gallery->getSet($this->request->get['id']);
		}

    $data['folders'] = $this->model_catalog_gallery->getFolders();

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($this->request->get['id'])) {
			$data['name'] = $info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($this->request->get['id'])) {
			$data['name'] = $info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (isset($this->request->get['id'])) {
			$data['description'] = $info['description'];
		} else {
			$data['description'] = '';
		}


		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($info) && is_file(DIR_IMAGE . $info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($info['image'], 100, 100);
			$data['image'] = $info['image'];
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			$data['image'] = 'no_image.png';
		}


		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// Images
		// if (isset($this->request->post['product_image'])) {
		// 	$product_images = $this->request->post['product_image'];
		// } elseif (isset($this->request->get['id'])) {
		// 	$product_images = $this->model_catalog_gallery->getProductImages($this->request->get['id']);
		// } else {
		// 	$product_images = array();
		// }

		if(isset($this->request->get['id'])){
			$data['seo'] = $this->model_catalog_gallery->getSeo($this->request->get['id']);
		} else {
			$data['seo'] = '';
		}

		$data['values'] = array();
		if(isset($this->request->get['id'])){

			$data['values'] = $this->model_catalog_gallery->getSetItems($this->request->get['id']);


			foreach ($data['values'] as $key => $value) {
				if (is_file(DIR_IMAGE . $value['value'])) {
					$image = $value['value'];
					$thumb = $value['value'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}

				$data['values'][$key] = array(
					'value'      => $image,
					'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
					'caption'    => $value['caption'],
					'videoid'    => $value['videoid'],
					'sort'       => $value['sort'],
				);
			}
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// foreach ($this->request->post['description'] as $language_id => $value) {
		// 	if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
		// 		$this->error['name'][$language_id] = $this->language->get('error_name');
		// 	}

		// 	if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
		// 		$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
		// 	}
		// }


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/gallery');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_gallery->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_gallery->getProductOptions($result['id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'id' => $result['id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function uploadImages(){
		if(!isset($this->request->post['value'])){
			$this->request->post['value'] = array();
		}
		for ($i=0; $i<count($this->request->files['printfiles']['name']); $i++) {

			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['printfiles']['name'][$i], ENT_QUOTES, 'UTF-8')));
			if($filename == ''){
				continue;
			}
			if ((utf8_strlen($filename) < 1) || (utf8_strlen($filename) > 512)) {
					$this->error = $this->language->get('error_filename');
					continue;
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['printfiles']['tmp_name'][$i]);

			if (preg_match('/\<\?php/i', $content)) {
				$this->error = $this->language->get('error_filetype');
				continue;
			}

			// Return any upload error
			if ($this->request->files['printfiles']['error'][$i] != UPLOAD_ERR_OK) {
				$this->error = $this->language->get('error_upload');
				continue;
			}

				$file = token(32) . $filename;

				// move_uploaded_file($this->request->files['printfiles']['tmp_name'][$i], DIR_IMAGE . "gall/" . $file);

        move_uploaded_file($this->request->files['printfiles']['tmp_name'][$i], DIR_IMAGE . $this->request->post['folder'] . "/" . $file);

        
				$value = array();
				$value['value'] = $this->request->post['folder'] . "/" . $file;
				$value['sort_order'] = 0;
				$value['caption'] = "caption " . $i;
				$value['videoid'] = "";

				array_push($this->request->post['value'], $value);
			
		}
	}
}
