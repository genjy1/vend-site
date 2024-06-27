<?php
class ControllerExtensionSub extends Controller {
	private $error = array();

	public function index() {

		$this->document->setTitle('Subdomains');

		$this->load->model('extension/sub');

		$this->getList();
	}

	public function add() {

		$this->document->setTitle('Subdomains');

		$this->load->model('extension/sub');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_sub->add($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/sub');

		$this->document->setTitle('Subdomains');

		$this->load->model('extension/sub');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_sub->edit($this->request->get['sub_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/sub');

		$this->document->setTitle('Subdomains');

		$this->load->model('extension/sub');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $sub_id) {
				$this->model_extension_sub->delete($sub_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cgd.name';
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

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
			$url .= '&filter=' . $this->request->get['filter'];
		} else {
			$filter = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Subdomains',
			'href' => $this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('extension/sub/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/sub/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['subs'] = array();

		$data['filter'] = $filter;

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin'),
			'filter' => $filter,
		);

		$sub_total = $this->model_extension_sub->getTotalSubs($filter_data);

		$results = $this->model_extension_sub->getSubs($filter_data);

		foreach ($results as $result) {
			$data['subs'][] = array(
				'sub_id' => $result['id'],
				'sub'              => $result['sub'],
				'edit'              => $this->url->link('extension/sub/edit', 'token=' . $this->session->data['token'] . '&sub_id=' . $result['id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = 'Subdomains';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = 'Поддомен';
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = 'action';

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/sub', 'token=' . $this->session->data['token'] . '&sort=cgd.name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('extension/sub', 'token=' . $this->session->data['token'] . '&sort=cg.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $sub_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($sub_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($sub_total - $this->config->get('config_limit_admin'))) ? $sub_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $sub_total, ceil($sub_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/sub_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = 'Subdomains';
		
		$data['text_form'] = !isset($this->request->get['sub_id']) ? $this->language->get('Добавить') : $this->language->get('Редактирование');
		$data['text_yes'] = 'да';
		$data['text_no'] = 'нет';


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['sub'])) {
			$data['error_name'] = $this->error['sub'];
		} else {
			$data['error_name'] = array();
		}

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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Subdomains',
			'href' => $this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['sub_id'])) {
			$data['action'] = $this->url->link('extension/sub/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/sub/edit', 'token=' . $this->session->data['token'] . '&sub_id=' . $this->request->get['sub_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/sub', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['sub_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$sub = $this->model_extension_sub->get($this->request->get['sub_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['sub'])) {
			$data['sub'] = $this->request->post;
		} elseif (isset($this->request->get['sub_id'])) {
			$data['sub'] = $sub;
		} else {
			$data['sub_description'] = '';
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/sub_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/sub')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/sub')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter'])) {
			$this->load->model('extension/sub');

			if (isset($this->request->get['filter'])) {
				$filter_name = $this->request->get['filter'];
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
				'filter'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_extension_sub->getSubs($filter_data);

			foreach ($results as $result) {

				$json[] = array(
					'name'       => strip_tags(html_entity_decode($result['sub'], ENT_QUOTES, 'UTF-8')),
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}