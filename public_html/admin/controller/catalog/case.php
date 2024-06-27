<?php
class ControllerCatalogCase extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_case->addCase($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_case->editCase($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/case');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/case');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_case->delete($id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		$this->load->model('catalog/case');
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
			'href' => $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/case/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/case/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['cases'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$case_total = $this->model_catalog_case->getTotal($filter_data);

		$results = $this->model_catalog_case->getCases($filter_data);

		foreach ($results as $result) {
			$data['cases'][] = array(
				'id' => $result['id'],
				'name'   => $result['name'],
				'edit'       => $this->url->link('catalog/case/edit', 'token=' . $this->session->data['token'] . '&case_id=' . $result['id'] . $url, true)
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
		$pagination->total = $case_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($case_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($case_total - $this->config->get('config_limit_admin'))) ? $case_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $case_total, ceil($case_total / $this->config->get('config_limit_admin')));


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/case_list', $data));
	}

	protected function getForm() {

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['case_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_name'] = $this->language->get('text_name');
		$data['name_placeholder'] = $this->language->get('name_placeholder');
		$data['text_image'] = $this->language->get('text_image');

		$data['description_placeholder'] = $this->language->get('description_placeholder');
		$data['text_description'] = $this->language->get('text_description');

		$data['entry_additional_image'] = $this->language->get('entry_additional_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');


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
			'href' => $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['case_id'])) {
			$data['action'] = $this->url->link('catalog/case/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/case/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['case_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/case', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['case_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {


		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		if(isset($this->request->get['case_id'])){
			$info = $this->model_catalog_case->getCase($this->request->get['case_id']);
		}

		if(isset($this->request->get['case_id'])){
			$slides = $this->model_catalog_case->getCaseSlides($this->request->get['case_id']);
		} else{
			$slides = array();
		}
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($this->request->get['case_id'])) {
			$data['name'] = $info['name'];
		} else {
			$data['name'] = '';
		}


		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['values'] = array();
		if(isset($this->request->get['case_id'])){

			foreach ($slides as $key => $value) {
				if (is_file(DIR_IMAGE . $value['image'])) {
					$image = $value['image'];
					$thumb = $this->model_tool_image->resize($value['image'], 100, 100);
				} else {
					$image = '';
					$thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
				}


				$data['values'][$key] = array(
					'id'         => $value['id'],
					'case_id'  => $value['case_id'],
					'value'      => $image,
                    'thumb'      => $thumb,
					'description' => $value['description'],
					'sort_order'       => $value['sort_order'],
				);
			}
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/case_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/case')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/case')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
