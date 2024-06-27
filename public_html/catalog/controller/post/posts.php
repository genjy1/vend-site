<?php
class ControllerPostPosts extends Controller {
	public function index() {
		$this->load->language('post/post');

		$this->load->model('post/post');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['heading_title'] = $this->language->get('heading_title');

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
      if(isset($this->request->get['path'])){
        $pathx = explode('_', $this->request->get['path']);
        $pathx = end($pathx);
        $this->document->addLink($this->url->link('product/category', 'path=' . $pathx ), 'canonical');
      }
    } else {
      $page = 1;
    }


		// $this->document->addScript('catalog/view/javascript/fcm/compiled/flipclock.min.js');
		// $this->document->addStyle('catalog/view/javascript/fcm/compiled/flipclockposts.css');


		// $this->document->addScript('catalog/view/javascript/moc/demo/js/kinetic.js');
		// $this->document->addScript('catalog/view/javascript/moc/jquery.final-countdown.min.js');
		// $this->document->addStyle('catalog/view/javascript/moc/bootstrap.min.css');


		$this->document->addScript('catalog/view/javascript/timer/TimeCircles.js');
		$this->document->addStyle('catalog/view/javascript/timer/TimeCircles.css');


		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$url = '';

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$url .= '&page=' . $this->request->get['page'];
		} else {
			$page = 1;
		}


		$limit = 9;

		if (isset($this->request->get['post_category_id'])) {
			$post_category_id = $this->request->get['post_category_id'];
		} else {
			$post_category_id = 0;
		}

		$this->document->addLink($this->url->link('post/posts', '&post_category_id='.$post_category_id), "canonical");

		$category_info = $this->model_post_post->getCategory($post_category_id);

		$data['heading_title'] = $category_info['name'];
		$this->document->setTitle($category_info['name']);

		$data['breadcrumbs'][] = array(
			'text' => $category_info['name'],
			'href' => $this->url->link('post/posts&post_category_id='.$post_category_id )
		);


		if($post_category_id == 13){
			$data['cats'] = true;
			$posts = array();
			$results = $this->model_post_post->getCategories(array(16,18,19));
			$this->load->model('tool/image');
			foreach ($results as $result) {

				if($result['image']){
					$image = $this->model_tool_image->resize($result['image'], 360, 190);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 360, 190);
				}
				$posts[] = array(
					'id' => $result['category_id'],
					'image' => $image,
					'title' => $result['name'],
					'timer' => array(),
					'date' => "",
					'description' => '',
					'href' => $this->url->link('post/posts', 'post_category_id=' . $result['category_id']),
				);
			}

			$data['posts'] = $posts;


		$post_total = $this->model_post_post->getTotalPosts($post_category_id);

		$pagination = new Pagination();
		$pagination->total = $post_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('post/posts', '&post_category_id='.$post_category_id.'&page={page}');

		$data['pagination'] = $pagination->render();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/post/posts.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/post/posts.tpl', $data));
				
		} else {
			$this->response->setOutput($this->load->view('/post/posts.tpl', $data));
		}
		return true;

		}




		$data_filter = array(
			'start' => ($page - 1),
			'limit' => $limit,
			'page' => $page,
			);

		$posts = array();

		$this->load->model('tool/image');
		$results = $this->model_post_post->getPosts($data_filter, $post_category_id);

		foreach ($results as $result) {
			$date = explode("-", $result['post_date']);
			$date = $date[2].".".$date[1].".".$date[0];
			if($result['image']){
				$image = $this->model_tool_image->resize($result['image'], 360, 190);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 360, 190);
			}

			$timer = explode("-", $result['timer']);
			// echo $result['timer'];
			if(count($timer)>2){
				$time_start = mktime(0,0,0,(int)$date[1],((int)$date[2] + 1),(int)$date[0]);
				$time_now = time();
				$time_end = mktime(0,0,0,$timer[1],($timer[2] + 1),$timer[0]);
			} else {
				$time_start = '';
				$time_now = '';
				$time_end = '';
			}


			$posts[] = array(
				'id' => $result['post_id'],
				'image' => $image,
				'title' => $result['title'],
				'time_start' => $time_start,
				'time_now' => $time_now,
				// 'time_end' => $time_end,
				'time_end' => $result['timer'],
				'date' => $date,
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...',
				'href' => $this->url->link('post/post', 'post_id=' . $result['post_id']),
				);
		}

		$data['posts'] = $posts;

		$post_total = $this->model_post_post->getTotalPosts($post_category_id);

		$pagination = new Pagination();
		$pagination->total = $post_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('post/posts', '&post_category_id='.$post_category_id.'&page={page}');

		$data['pagination'] = $pagination->render();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/post/posts.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/post/posts.tpl', $data));
				
		} else {
			$this->response->setOutput($this->load->view('/post/posts.tpl', $data));
		}
	}
}