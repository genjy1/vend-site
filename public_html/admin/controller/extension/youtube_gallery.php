<?php
class ControllerExtensionYoutubeGallery extends Controller {
    private $error = array();

    public function index() {

        $this->document->setTitle("Youtube Gallery");

        $this->load->model('extension/youtube');

        $this->getList();
    }


    public function getList() {
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/youtube_gallery', 'token=' . $this->session->data['token'], true)
        );

        $url = "";

        $data['heading_title'] = "Галерея";

        $data['text_list'] = "Список";

        $data['column_image'] = "Изображения";

        $data['column_title'] = "Название";

        $data['button_add'] = "Добавить";

        $data['button_delete'] = "Удалить";

        $data['column_action'] = "Действие";

        $data['add'] = $this->url->link('extension/youtube_gallery/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['delete'] = $this->url->link('extension/youtube_gallery/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_edit'] = $this->language->get('button_edit');

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
          $page = $this->request->get['page'];
        } else {
          $page = 1;
        }

        if(isset($this->request->post['selected'])){
          $data['selected'] = $this->request->post['selected'];
        } else {
          $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['page'])) {
          $url .= '&page=' . $this->request->get['page'];
        }

        $filter_data = array(
          'start' => ($page - 1) * $this->config->get('config_limit_admin'),
          'limit' => $this->config->get('config_limit_admin')
        );

        $data['posts'] = array();

        $post_total = $this->model_extension_youtube->getTotalPosts();

        $results = $this->model_extension_youtube->getPosts($filter_data);

        $this->load->model('tool/image');

        foreach ($results as $result) {

        if (isset($result['image']) && is_file(DIR_IMAGE . $result['image'])) {
          $thumb = $this->model_tool_image->resize($result['image'], 100, 100);
        } elseif (!empty($result) && is_file(DIR_IMAGE . $result['image'])) {
          $thumb = $this->model_tool_image->resize($result['image'], 100, 100);
        } else {
          $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['posts'][] = array(
          'post_id' => $result['id'],
          'image'   => $thumb,
          'title'          => $result['name'],
          'edit'           => $this->url->link('extension/youtube_gallery/edit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
        );
      }




        $pagination = new Pagination();
        $pagination->total = $post_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/youtube_list', $data));
    }

    public function add(){

        $this->document->setTitle("Youtube gallery");

        $this->load->model('extension/youtube');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_youtube->addPost($this->request->post);

            $this->session->data['success'] = "Добавлено";

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/youtube_gallery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('extension/youtube');

        $this->document->setTitle($this->language->get('heading_title'));


        $this->load->model('extension/youtube');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_extension_youtube->editPost($this->request->get['id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/youtube_gallery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/youtube');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/post');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_extension_youtube->deletePost($id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getForm(){
      $this->load->language('extension/youtube');
      $data['heading_title'] = $this->language->get('heading_title');
        
      $data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
      $data['text_default'] = $this->language->get('text_default');
      $data['text_enabled'] = $this->language->get('text_enabled');
      $data['text_disabled'] = $this->language->get('text_disabled');

      $data['entry_name'] = $this->language->get('entry_name');
      $data['entry_link'] = $this->language->get('entry_link');
      $data['entry_description'] = $this->language->get('entry_description');
      $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
      $data['entry_image'] = $this->language->get('entry_image');
        
      
      $data['button_save'] = $this->language->get('button_save');
      $data['button_cancel'] = $this->language->get('button_cancel');

      
      $data['ckeditorplus_language'] = 'en';
      $data['ckeditorplus_skin'] = 'icy_orange';
      $data['ckeditorplus_status'] = 0;
      $data['ckeditorplus_height'] = $this->config->get('ckeditorplus_height') ? $this->config->get('ckeditorplus_height') : '300';

      if ($this->config->get('ckeditorplus_status')) {
        $data['ckeditorplus_status'] = $this->config->get('ckeditorplus_status');
        if ($this->config->get('ckeditorplus_language')) {$data['ckeditorplus_language'] = $this->config->get('ckeditorplus_language');}
        if ($this->config->get('ckeditorplus_skin')) {$data['ckeditorplus_skin'] = $this->config->get('ckeditorplus_skin');}
      }

        
      $url = '';

      if (isset($this->request->get['page'])) {
          $url .= '&page=' . $this->request->get['page'];
      }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/youtube_gallery', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        if (!isset($this->request->get['id'])) {
            $data['action'] = $this->url->link('extension/youtube_gallery/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('extension/youtube_gallery/edit', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['cancel'] = $this->url->link('extension/youtube_gallery', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $info = $this->model_extension_youtube->getPost($this->request->get['id']);
        }

        $data['token'] = $this->session->data['token'];


        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (isset($this->request->get['id'])) {
            $data['description'] = $info['text'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['link'])) {
            $data['link'] = $this->request->post['link'];
        } elseif (isset($this->request->get['id'])) {
            $data['link'] = $info['link'];
        } else {
            $data['link'] = '';
        }

        $this->load->model('catalog/category');

        if (isset($this->request->post['category_ids'])) {
          $categories = $this->request->post['category_ids'];
        } elseif (isset($this->request->get['id'])) {
          $categories = explode(",", $info['category_ids']);
        } else {
          $categories = array();
        }

        $data['category_ids'] = array();

    foreach ($categories as $category_id) {
      $category_info = $this->model_catalog_category->getCategory($category_id);

      if ($category_info) {
        $data['category_ids'][] = array(
          'category_id' => $category_info['category_id'],
          'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
        );
      }
    }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (isset($this->request->get['id'])) {
            $data['name'] = $info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($info)) {
            $data['image'] = $info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($info) && is_file(DIR_IMAGE . $info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }



        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/youtube_form.tpl', $data));
    }

    private function validateForm(){
      // here validate
      return true;
    }
}