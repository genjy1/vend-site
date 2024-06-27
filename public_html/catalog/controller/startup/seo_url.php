<?php
class ControllerStartupSeoUrl extends Controller {
	public function index() {

		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			if($parts[0] == 'category'){
				array_shift($parts);
			}
			if(isset($parts[1]) && $parts[0] == 'blog' && $parts[1] == 'aktsii'){
				array_shift($parts);
			}
			// print_r($parts);
			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

				if ($query->num_rows) {

					$url = explode('=', $query->row['query']);

					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}

					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}

          if ($url[0] == 'tag_id') {
                    $this->request->get['tag_id'] = $url[1];
                }

					if ($url[0] == 'post_id') {
						$this->request->get['post_id'] = $url[1];
					}

					if ($url[0] == 'post_category_id') {
						$this->request->get['post_category_id'] = $url[1];
					}

					if ($url[0] == 'gallery_id') {
						$this->request->get['gallery_id'] = $url[1];
					}

					if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id' && $url[0] != 'post_id' && $url[0] != 'post_category_id' && $url[0] != 'gallery_id' && $url[0] != 'tag_id') {

						$this->request->get['route'] = $query->row['query'];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}

			if (!isset($this->request->get['route'])) {
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
				} elseif (isset($this->request->get['tag_id'])) {  
                $this->request->get['route'] = 'product/tags';
        } elseif (isset($this->request->get['path'])) {
					$this->request->get['route'] = 'product/category';
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
				} elseif (isset($this->request->get['post_id'])) {
					$this->request->get['route'] = 'post/post';
				} elseif (isset($this->request->get['post_category_id'])) {
					$this->request->get['route'] = 'post/posts';
				} elseif (isset($this->request->get['gallery_id'])) {
					$this->request->get['route'] = 'gallery/gallery';
				}
			}

			if (isset($this->request->get['route'])) {
				return new Action($this->request->get['route']);
			}
		}
	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id') || ($data['route'] == 'gallery/gallery' && $key == 'gallery_id') || ($data['route'] == 'post/posts' && $key == 'post_category_id') || ($data['route'] == 'post/post' && $key == 'post_id') ) {
		
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
					if($data['route'] == 'gallery/gallery'){
						$url = "/photos" . $url;
					}
					if($data['route'] == 'post/post'){
						$url = "/blog" . $url;
					}
					// if (($data['route'] == 'product/product' && $key == 'product_id')) {

					// }
          } elseif ($data['route'] == 'product/tags' && $key == 'tag_id'){
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }
				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $k => $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
						if($k==0){
							$url = "/category" . $url;
						}
					}

					$url .= "/";

					unset($data[$key]);
				} elseif($data['route'] == 'checkout/success'){
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'checkout/success'");

				if ($query->num_rows && $query->row['keyword']) {
					$url .= '/' . $query->row['keyword'];
				} else {
					$url = '';

					break;
				}

				$url .= "/";
				}
			}	
		} 

		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			$link = $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
            
            return rtrim($link, '/') . '/';
		} else {
			return rtrim($link, '/') . '/';
		}
	}
}
