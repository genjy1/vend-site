<?php
class ControllerGalleryGalleries extends Controller {

	public function index() {
		$this->load->language('gallery/gallery');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/gallery/dist/themes/tiles/ug-theme-tiles.js');
		$this->document->addScript('catalog/view/javascript/gallery/dist/js/unitegallery.min.js');
		$this->document->addStyle('catalog/view/javascript/gallery/dist/css/unite-gallery.css');
		$this->document->addStyle('catalog/view/javascript/gallery/gallery.css');

    $this->document->addScript('catalog/view/javascript/isotope/isotope.pkgd.min.js');

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
			'href' => $this->url->link('gallery/gallery')
		);

		$data['heading_title'] = $this->language->get('heading_title');



		$this->load->model('tool/image');
		$this->load->model('gallery/gallery');

		$galleries = $this->model_gallery_gallery->getSets();
		$data['galleries'] = array();
		foreach ($galleries as $key => $value) {
			if($value['image'] == '' || !$value['image']){
				continue;
			}
			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $value['image']);

			if($width_orig > 667){
				$height_orig = 667 / ($width_orig / $height_orig);
				$width_orig = 667;
			}


			$image = $this->model_tool_image->resize($value['image'], $width_orig, $height_orig);

			if($this->model_gallery_gallery->countVideo($value['id']) > 0){
				$type = 'video';
				$count = $this->model_gallery_gallery->countVideo($value['id']);
			} else {
				$count = 0;
				$type = 'image';
			}

			if($value['sort'] > 9){
				$width_orig = 386;
			}

			$data['galleries'][] = array(
				'image' => $image,
				'title' => $value['name'],
				'description' => $value['description'],
				'width' => $width_orig,
				'height' => $height_orig,
				'type' => $type,
				'count' => $count,
				'href'   => $this->url->link('gallery/gallery', 'gallery_id='.$value['id']),
				);
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
