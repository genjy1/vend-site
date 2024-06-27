<?php 
class ControllerModulePosts extends Controller {

	public function index($setting) {

		$this->language->load('module/posts');
		
		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		
		$this->load->model('module/posts');

		$data['heading_title'] = $this->language->get('heading_title_personal');
		
		$limit = html_entity_decode($setting['limit']);

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['heading_title'] = $setting['title'];

		$data['posts'] = array();

		$results = $this->model_module_posts->getPosts($setting);

		foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}


				$data['posts'][] = array(
					'post_id'  => $result['post_id'],
					'thumb'       => $image,
					'title'       => $result['title'],
					'date'        => preg_replace("/-/",".",$result['post_date']),
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'href'        => $this->url->link('post/post', 'post_id=' . $result['post_id'], true)
				);
		}

		// echo "<div style='display:none'>";
		// print_r($data['posts']);
		// echo "</div>";

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/posts.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/posts.tpl', $data);
		} else {
			return $this->load->view('module/posts', $data);
		}
	}
}
?>