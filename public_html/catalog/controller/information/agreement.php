<?php
class ControllerInformationAgreement extends Controller {

    public function index()
    {
// Заголовок страницы
        $this->document->setTitle('Согласие на обработку персональных данных');

        // Хлебные крошки
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/agreement')
        );

        // Основной контент
        $data['heading_title'] = $this->language->get('heading_title');
        $data['description'] = $this->language->get('text_description');

        // Загружаем общие части
        $data['column_left']   = $this->load->controller('common/column_left');
        $data['column_right']  = $this->load->controller('common/column_right');
        $data['content_top']   = $this->load->controller('common/content_top');
        $data['content_bottom']= $this->load->controller('common/content_bottom');
        $data['footer']        = $this->load->controller('common/footer');
        $data['header']        = $this->load->controller('common/header');

        // Рендерим шаблон
        $this->response->setOutput($this->load->view('information/agreement.tpl', $data));


    }
    
}