<?php
class ControllerExtensionNotifications extends Controller {
  private $error = array();

  public function index() {

    $this->document->setTitle("Notifications");

    $this->load->model('extension/notifications');

    $this->load->language('extension/notifications');
      
    $data['label'] = $this->language->all();

    $data['error_warning'] = array();

    $data['push_subscribers'] = $this->model_extension_notifications->getTotalPushSubscribers();
    $data['email_subscribers'] = $this->model_extension_notifications->getTotalMailSubscribers();

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/notifications', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['cancel'] = $this->url->link('extension/notifications', 'token=' . $this->session->data['token'], 'SSL');

    $data['token'] = $this->session->data['token'];

    $this->load->model('tool/image');

    $image = $this->config->get("notification_image");

    if (isset($image) && is_file(DIR_IMAGE . $image)) {
      $data['thumb'] = $this->model_tool_image->resize($image, 100, 100);
      $data['image'] = $image;
    } else {
      $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
      $data['image'] = "";
    }


    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/notifications.tpl', $data));
  }


  public function push_history() {

    $this->document->setTitle("History of push notifications");

    $this->load->model('extension/notifications');

    $this->load->language('extension/notifications');
      
    $data['label'] = $this->language->all();

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/notifications', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => "История уведомлений",
      'href' => $this->url->link('extension/notifications/push_history', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['token'] = $this->session->data['token'];

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    $url = "";

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $this->load->model('tool/image');

    $filter_data = array(
      'start' => ($page - 1) * $this->config->get('config_limit_admin'),
      'limit' => $this->config->get('config_limit_admin'),
    );

    $data['notifications'] = $this->model_extension_notifications->getPushHistory($filter_data);

    foreach ($data['notifications'] as $key => $value) {
      $image = $value['icon'];

      if (isset($image) && is_file(DIR_IMAGE . $image)) {
        $data['notifications'][$key]['icon'] = $this->model_tool_image->resize($image, 128, 128);
      } else {
        $data['notifications'][$key]['icon'] = $this->model_tool_image->resize('no_image.png', 128, 128);
      }
    }

    $total = $this->model_extension_notifications->getTotalPushHistory();

    $pagination = new Pagination();
    $pagination->total = $total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('extension/notifications/push_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/notifications_push_history.tpl', $data));
  }

  public function mail_history() {

    $this->document->setTitle("History of mail notifications");

    $this->load->model('extension/notifications');

    $this->load->language('extension/notifications');
      
    $data['label'] = $this->language->all();

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/notifications', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => "История уведомлений",
      'href' => $this->url->link('extension/notifications/mail_history', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['token'] = $this->session->data['token'];

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    $url = "";

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $filter_data = array(
      'start' => ($page - 1) * $this->config->get('config_limit_admin'),
      'limit' => $this->config->get('config_limit_admin'),
    );

    $data['notifications'] = $this->model_extension_notifications->getMailHistory($filter_data);

    $total = $this->model_extension_notifications->getTotalMailHistory();

    $pagination = new Pagination();
    $pagination->total = $total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('extension/notifications/mail_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/notifications_mail_history.tpl', $data));
  }

  public function sendPush(){
    $this->load->language('extension/notifications');
    $this->load->model('setting/setting');
    $this->load->model('extension/notifications');

    $labels = $this->language->all();

    $json = array();

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      // if (!$this->request->post['subject']) {
      //   $json['error']['subject'] = $this->language->get('error_subject');
      // }

      if (!$json) 
      {
        $this->model_extension_notifications->addHistory($this->request->post);

        $response = $this->pusher->send($this->request->post);

        $json['success'] = "Уведомление отправлено " . $response['subscribers'] . " подписчикам";

      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function sendMail(){
    $this->load->language('extension/notifications');

    $labels = $this->language->all();

    $json = array();

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (!$this->user->hasPermission('modify', 'marketing/contact')) {
        $json['error']['warning'] = $this->language->get('error_permission');
      }

      if (!$this->request->post['subject']) {
        $json['error']['subject'] = $this->language->get('error_subject');
      }

      if (!$this->request->post['message']) {
        $json['error']['message'] = $this->language->get('error_message');
      }

      if (!$json) {
        
        $store_name = $this->config->get('config_name');

        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting('config', 0);
        $store_email = isset($setting['config_email']) ? $setting['config_email'] : $this->config->get('config_email');

        $this->load->model('extension/notifications');

        $emails = $this->model_extension_notifications->getEmails();

        // $emails = [
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        //     "dedix@mail.ru",
        // ];

        if ($emails) {

          $json['success'] = "Уведомления отправлено " . count($emails) . " подписчикам";

          $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json));

          $message  = '<html dir="ltr" lang="en">' . "\n";
          $message .= '  <head>' . "\n";
          $message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
          $message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
          $message .= '  </head>' . "\n";
          $message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
          $message .= '</html>' . "\n";

          $emails_chunk = array_chunk($emails, 10);

          foreach ($emails_chunk as $key => $emails) {
              foreach ($emails as $key => $email) {

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $mail = new Mail();
                  $mail->protocol = $this->config->get('config_mail_protocol');
                  $mail->parameter = $this->config->get('config_mail_parameter');
                  $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                  $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                  $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                  $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                  $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                  $mail->setTo($email);
                  // $mail->setFrom("info@vend-shop.com");
                  $mail->setFrom($this->config->get('config_email'));
                  $mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
                  $mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
                  $mail->setHtml($message);
                  $mail->send();


                }
            }
            sleep(1);
          }

          

          $this->model_extension_notifications->addMailHistory($this->request->post, count($emails));

        }
      }
    }

    // $this->response->addHeader('Content-Type: application/json');
    // $this->response->setOutput(json_encode($json));
  }

  public function settings(){
    $this->load->model('tool/image');
    $this->load->model('setting/setting');
    $this->load->model('extension/notifications');
    $this->load->language('extension/notifications');

    $this->document->setTitle("Settings notifications");

    $data['label'] = $this->language->all();

    $data['error_warning'] = array();

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/notifications', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['cancel'] = $this->url->link('extension/notifications', 'token=' . $this->session->data['token'], 'SSL');

    $data['token'] = $this->session->data['token'];

    $this->load->model('tool/image');

    $key = $this->model_setting_setting->getSetting('notifications_key');
    $config = $this->model_setting_setting->getSetting('notifications_config');
    $click_action = $this->model_setting_setting->getSetting('notifications_click_action');

    $data['key'] = $this->config->get("notification_key");
    $data['click_action'] = $this->config->get("notification_click_action");
    $data['config'] = $this->config->get("notification_config");

    $image = $this->config->get("notification_image");

    if (isset($image) && is_file(DIR_IMAGE . $image)) {
      $data['thumb'] = $this->model_tool_image->resize($image, 512, 512);
      $data['image'] = $image;
    } else {
      $data['thumb'] = $this->model_tool_image->resize('no_image.png', 512, 512);
      $data['image'] = "";
    }

    $data['topic_values'] = $this->model_extension_notifications->getTopics();


    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/notifications_settings.tpl', $data));
  }

  public function saveSettings()
  {
    $this->load->model('setting/setting');

    $json['success'] = false;

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->model_setting_setting->editSetting('notification', $this->request->post);

      $json['success'] = true;
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function saveTopics()
  {
    $this->load->model('extension/notifications');

    $json['success'] = false;

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->model_extension_notifications->saveTopics($this->request->post['topic_value']);

      $json['success'] = true;
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function autocomplete(){
    $json = array();

    if (isset($this->request->get['filter_name'])) {
      $this->load->model('catalog/post');

      $filter_data = array(
        'filter_name' => $this->request->get['filter_name'],
        'sort'        => 'name',
        'order'       => 'ASC',
        'start'       => 0,
        'limit'       => 5
      );

      $results = $this->model_catalog_post->getPosts($filter_data);

      foreach ($results as $result) {
        $json[] = array(
          'link' => $this->url->link('post/posts', 'post_category_id=' . $result['post_id']),
          'name'        => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
        );
      }

      // $this->load->model("catalog/product");

      // $filter_data = array(
      //   'filter_name'         => $this->request->get['filter_name'],
      //   'start'               => 0,
      //   'limit'               => 5
      // );

      // $results = $this->model_catalog_product->getProducts($filter_data);

      // foreach ($results as $result) {
      //   $json[] = array(
      //     'url' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
      //     'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
      //   );
      // }

      // $this->load->model("catalog/category");

      // $filter_data = array(
      //   'filter_name'         => $this->request->get['filter_name'],
      //   'start'               => 0,
      //   'limit'               => 5
      // );

      // $results = $this->model_catalog_category->getCategories($filter_data);

      // foreach ($results as $result) {
      //   $json[] = array(
      //     'url' => $this->url->link('product/category', 'category_id=' . $result['category_id']),
      //     'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
      //   );
      // }
    }

    $sort_order = array();

    foreach ($json as $key => $value) {
      $sort_order[$key] = $value['name'];
    }

    array_multisort($sort_order, SORT_ASC, $json);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
}