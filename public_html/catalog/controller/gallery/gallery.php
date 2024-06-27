<?php
class ControllerGalleryGallery extends Controller {

	public function index() {
		$this->load->language('gallery/gallery');
		if(!isset($this->request->get['gallery_id'])){
			$this->galleries();
			return;
		}

		$this->document->setTitle($this->language->get('heading_title'));
		//$this->document->addScript('catalog/view/javascript/gallery/dist/themes/tiles/ug-theme-tiles.js');
		$this->document->addStyle('catalog/view/javascript/gallery/dist/css/unite-gallery.css');
		$this->document->addStyle('catalog/view/javascript/gallery/dist/themes/default/ug-theme-default.css');
		$this->document->addScript('catalog/view/javascript/gallery/dist/themes/tilesgrid/ug-theme-tilesgrid.js');
		$this->document->addStyle('catalog/view/javascript/gallery/gallery.css');
		$this->document->addScript('catalog/view/javascript/gallery/dist/js/unitegallery.min.js');
    	$this->document->addScript('catalog/view/javascript/isotope/packery-mode.pkgd.js');
    	$this->document->addScript('catalog/view/javascript/isotope/isotope.pkgd.min.js');


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('gallery/gallery')
		);


		$this->load->model('tool/image');
		$this->load->model('gallery/gallery');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$images = $this->model_gallery_gallery->getSet($this->request->get['gallery_id'], (($page - 1)  * 15), 15);

		$data['images'] = array();
		foreach ($images as $key => $value) {
			if($value['value'] == '' || $value['value'] == ' '){
				continue;
			}
			echo "<div style='display:none'>".DIR_IMAGE . $value['value']."</div>\n";
			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $value['value']);
			if($width_orig > 667){
				$height_orig = 667 / ($width_orig / $height_orig);
				$width_orig = 667;
			}
			$image = $this->model_tool_image->resize($value['value'], $width_orig, $height_orig);
			$thumb = $this->model_tool_image->resize($value['value'], 208, 180);

			$data['images'][] = array(
				'image' => $image,
				'thumb' => $thumb,
				'title' => $value['caption'],
				'videoid' => $value['videoid'],
				'width' => 208,
				'height' => 180,
				);
		}

		$images_total = $this->model_gallery_gallery->getTotalImages($this->request->get['gallery_id']);

		$gallery_name = $this->model_gallery_gallery->getSetName($this->request->get['gallery_id']);

		$data['heading_title'] = $gallery_name;

		$data['breadcrumbs'][] = array(
			'text' => $gallery_name,
			'href' => $this->url->link('gallery/gallery', 'gallery_id='.$this->request->get['gallery_id']),
		);

		$pagination = new Pagination();
		$pagination->total = $images_total;
		$pagination->page = $page;
		$pagination->limit = 15;
		$pagination->url = $this->url->link('gallery/gallery', 'gallery_id='.$this->request->get['gallery_id'] . '&page={page}' );

		$data['pagination'] = $pagination->render();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('gallery/gallery', $data));
	}

	public function galleries() {
		$this->load->language('gallery/gallery');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/gallery/dist/themes/tiles/ug-theme-tiles.js');
		$this->document->addScript('catalog/view/javascript/gallery/dist/js/unitegallery.min.js');
		$this->document->addStyle('catalog/view/javascript/gallery/dist/css/unite-gallery.css');
		$this->document->addStyle('catalog/view/javascript/gallery/gallery.css');

    $this->document->addScript('catalog/view/javascript/isotope/imagesloaded.pkgd.min.js');

    $this->document->addScript('catalog/view/javascript/isotope/isotope.pkgd.min.js');
    $this->document->addScript('catalog/view/javascript/isotope/packery-mode.pkgd.js');
		// if(isset($this->request->get['gallery_id'])){
		// 	$this->response->redirect($this->url->link('gallery/gallery', 'gallery_id='.$this->request->get['gallery_id']));
		// }


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

		$data['heading_title'] = $this->language->get('heading_title');



		$this->load->model('tool/image');
		$this->load->model('gallery/gallery');

		if($this->detect->isTablet()){
			$tablet = true;
		} else {
			$tablet = false;
		}

		$galleries = $this->model_gallery_gallery->getSets();
		$data['galleries'] = array();
		foreach ($galleries as $key => $value) {
			if($value['image'] == '' || !$value['image']){
				continue;
			}
			if($tablet){
				
				if(file_exists(DIR_IMAGE . "tablet/".$value['image'])){
					$img = "tablet/".$value['image'];
				} else {
					$img = $value['image'];
				}

				list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $img);
				if($width_orig > 667){
					$height_orig = 667 / ($width_orig / $height_orig);
					$width_orig = 667;
				}
				$image = $this->model_tool_image->resize($img, $width_orig, $height_orig);

			} else {
				list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $value['image']);
				$image = $this->model_tool_image->resize($value['image'], $width_orig, $height_orig);
			}
			
		
			

			if($this->model_gallery_gallery->countVideo($value['id']) > 0){
				$type = 'video';
				$count = $this->model_gallery_gallery->countVideo($value['id']);
			} else {
				$count = 0;
				$type = 'image';
			}

			if($value['sort'] > 9){
				$width_orig = 366;
			}
			if($value['sort'] > 12){
				$width_orig = 386;
			}

			$data['galleries'][] = array(
				'image' => $image,
				'title' => $value['name'],
				'description' => $value['description'],
				'width' => $width_orig,
				'height' => $height_orig,
				'type' => $type,
				'sort' => $value['sort'],
				'count' => $count,
				'href'   => $this->url->link('gallery/gallery', 'gallery_id='.$value['id']),
				);
		}

    if($data['galleries']){
      $array1 = array_slice($data['galleries'], 0, 5);
      $array2 = array_slice($data['galleries'], 5);

      $array2 = array_reverse($array2);

      $data['galleries'] = array_merge($array1, $array2);
    }
    

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('gallery/galleries', $data));
	}
}
