<?php
class ControllerCatalogSpares extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/spares');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/spares');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/spares');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$id = $this->model_catalog_spares->addMap($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');


			$this->response->redirect($this->url->link('catalog/spares/edit', 'token=' . $this->session->data['token'] . "id=".$id, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/spares');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/spares');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_catalog_spares->editMap($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/spares/edit', 'token=' . $this->session->data['token'] . "&id=".$this->request->get['id'], true));

		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/spares');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/spares');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_spares->delete($id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/spares', 'token=' . $this->session->data['token'], true));
		}

		$this->getList();
	}

	protected function getList() {

		$this->load->model('catalog/spares');
		$this->load->language('catalog/spares');

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
			'href' => $this->url->link('catalog/spares', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('catalog/spares/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('catalog/spares/delete', 'token=' . $this->session->data['token'], true);

		$data['categories'] = array();

		$this->load->model('tool/image');

		// $spares_total = $this->model_catalog_spares->getTotal($filter_data);

		$results = $this->model_catalog_spares->getCategories();

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 80, 80);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 80, 80);
			}
			$data['categories'][] = array(
				'id' => $result['category_id'],
				'name'   => $result['name'],
				'edit'       => $this->url->link('catalog/spares/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['category_id'], true)
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



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/spares_list', $data));
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/spares', 'token=' . $this->session->data['token'], true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('catalog/spares/add', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('catalog/spares/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], true);
		}

		$data['cancel'] = $this->url->link('catalog/spares', 'token=' . $this->session->data['token'], true);

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['maps'] = array();

		if(isset($this->request->get['id'])){
			$maps = $this->model_catalog_spares->getMaps($this->request->get['id']);
		} else {
			$maps = array();
		}

		$this->load->model('tool/image');

		foreach ($maps as $k => $m) {
			$thumb = $this->model_tool_image->resize($m['image'], 370, 712);
			$data['maps'][] = array(
				'thumb' => $thumb,
				'image' => $m['image'],
        'pins' => $m['pins'],
				'sort_order' => $m['sort_order'],
				'map_id' => $m['map_id'],
				'items' => $m['items'],
				);
		}
// print_r($data['maps']);
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/spares_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/spares')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/spares')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/spares')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/spares');
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

			$results = $this->model_catalog_spares->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_spares->getProductOptions($result['id']);

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
}
