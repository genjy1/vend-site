<?php
class ControllerCatalogSlider extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/slider');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/slider');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/slider');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_slider->addSlider($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/slider');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/slider');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_slider->editSlider($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/slider');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/slider');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_catalog_slider->delete($id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		$this->load->model('catalog/slider');
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
			'href' => $this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/slider/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/slider/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['sliders'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$slider_total = $this->model_catalog_slider->getTotal($filter_data);

		$results = $this->model_catalog_slider->getSliders($filter_data);

		foreach ($results as $result) {
			$data['sliders'][] = array(
				'id' => $result['slider_id'],
				'name'   => $result['name'],
				'edit'       => $this->url->link('catalog/slider/edit', 'token=' . $this->session->data['token'] . '&slider_id=' . $result['slider_id'] . $url, true)
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
		$pagination->total = $slider_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($slider_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($slider_total - $this->config->get('config_limit_admin'))) ? $slider_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $slider_total, ceil($slider_total / $this->config->get('config_limit_admin')));


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/slider_list', $data));
	}

	protected function getForm() {

		$this->document->addScript('/admin/view/javascript/dpicker/js/pickmeup.js');
		$this->document->addStyle('/admin/view/javascript/dpicker/css/pickmeup.css');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['slider_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
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
			'href' => $this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['slider_id'])) {
			$data['action'] = $this->url->link('catalog/slider/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/slider/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['slider_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/slider', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['slider_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {


		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if(isset($this->request->get['slider_id'])){
			$info = $this->model_catalog_slider->getSlider($this->request->get['slider_id']);
		}
		if(isset($this->request->get['slider_id'])){
			$slides = $this->model_catalog_slider->getSlides($this->request->get['slider_id']);
		} else{
			$slides = array();
		}
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($this->request->get['slider_id'])) {
			$data['name'] = $info['name'];
		} else {
			$data['name'] = '';
		}


		if (isset($this->request->post['effect'])) {
			$data['effect'] = $this->request->post['effect'];
		} elseif (isset($this->request->get['slider_id'])) {
			$data['effect'] = $info['effect'];
		} else {
			$data['effect'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['values'] = array();
		if(isset($this->request->get['slider_id'])){

			foreach ($slides as $key => $value) {
				if (is_file(DIR_IMAGE . $value['image'])) {
					$image = $value['image'];
					$thumb = $value['image'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}


				if (is_file(DIR_IMAGE . $value['background'])) {
					$backgimage = $value['background'];
					$backg = $value['background'];
				} else {
					$backgimage = '';
					$backg = 'no_image.png';
				}


				$description = $this->model_catalog_slider->getDescription($value['slide_id']);

				$data['values'][$key] = array(
					'id'         => $value['slide_id'],
					'slider_id'  => $value['slider_id'],
					'value'      => $image,
					'timer'      => $value['timer'],
					'links'      => explode(",",$value['links']),
					'color_caption' => $value['color_caption'],
					'color_button' => $value['color_button'],
					'color_text'    => $value['color_text'],
					'description' => $description,
					'position'   => $value['position'],
					'backg'      => $this->model_tool_image->resize($backg, 100, 100),
					'backgimage'      => $backgimage,
					'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
					'sort_order'       => $value['sort_order'],
				);
			}
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/slider_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
