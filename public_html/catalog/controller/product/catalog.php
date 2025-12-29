<?php
class ControllerProductCatalog extends Controller {
	public function index() {
		
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_catalog'),
			'href' => $this->url->link('product/catalog')
		);

		$category_info = $this->model_catalog_category->getCategory($category_id);
		$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
		$data['issub'] = $this->subdomains->getSubDomain();

		$title = $this->subdomains->getCategoryTitle($category_info['meta_title'], $category_info['name']);
		$this->document->setTitle($title);
		$description = $this->subdomains->getCategoryDescription($category_info['meta_description'], $category_info['name']);
		$this->document->setDescription($description);
		$this->document->setKeywords($category_info['meta_keyword']);


		$data['heading_title'] = $this->language->get('text_catalog');
		$this->document->setTitle($this->language->get('text_catalog'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('product/catalog', $data));
	}
}
