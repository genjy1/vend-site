<?php
class ControllerCheckoutConfirm extends Controller {
    public function index() {
        try {
            $this->load->model('checkout/order');
            $this->load->model('localisation/currency');

            $json = [];

            if (!$this->cart->hasProducts()) {
                $json['error'] = 'Корзина пуста';
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
                return;
            }

            $order_data = [];

            // --- Пользователь ---
            if ($this->customer->isLogged()) {
                $order_data['customer_id']        = $this->customer->getId();
                $order_data['customer_group_id']  = $this->customer->getGroupId();
                $order_data['firstname']          = $this->customer->getFirstName();
                $order_data['lastname']           = $this->customer->getLastName();
                $order_data['email']              = $this->customer->getEmail();
                $order_data['telephone']          = $this->customer->getTelephone();
            } else {
                $order_data['customer_id']        = 0;
                $order_data['customer_group_id']  = 1;
                $order_data['firstname']          = $this->request->post['firstname'] ?? '';
                $order_data['lastname']           = '';
                $order_data['email']              = $this->request->post['email'] ?? '';
                $order_data['telephone']          = $this->request->post['telephone'] ?? '';
            }

            // --- Оплата / Доставка ---
            foreach (['payment', 'shipping'] as $type) {
                $order_data["{$type}_firstname"] = $order_data['firstname'];
                $order_data["{$type}_lastname"]  = $order_data['lastname'];
                $order_data["{$type}_address_1"] = '';
                $order_data["{$type}_city"]      = '';
                $order_data["{$type}_postcode"]  = '';
                $order_data["{$type}_country"]   = '';
                $order_data["{$type}_country_id"] = 0;
                $order_data["{$type}_zone"]      = '';
                $order_data["{$type}_zone_id"]   = 0;
            }

            $order_data['payment_method'] = 'Безналичная оплата';
            $order_data['payment_code']   = 'cod';
            $order_data['shipping_method'] = 'Без доставки';
            $order_data['shipping_code']   = 'none';

            // --- Валюта ---
            $currency_info = $this->model_localisation_currency->getCurrencyByCode($this->session->data['currency']);
            if ($currency_info) {
                $order_data['currency_id']    = $currency_info['currency_id'];
                $order_data['currency_code']  = $currency_info['code'];
                $order_data['currency_value'] = $currency_info['value'];
            } else {
                $order_data['currency_id']    = 0;
                $order_data['currency_code']  = $this->config->get('config_currency');
                $order_data['currency_value'] = 1.00000000;
            }

            // --- Магазин ---
            $order_data['store_id']    = $this->config->get('config_store_id');
            $order_data['store_name']  = $this->config->get('config_name');
            $order_data['store_url']   = $order_data['store_id'] ? $this->config->get('config_url') : HTTP_SERVER;
            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');

            // --- Прочее ---
            $order_data['language_id'] = $this->config->get('config_language_id');
            $order_data['ip'] = $this->request->server['REMOTE_ADDR'] ?? '';
            $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'] ?? '';
            $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'] ?? '';
            $order_data['comment'] = '';
            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';
            $order_data['total'] = $this->cart->getTotal();

            $order_data['date_added'] = date('Y-m-d H:i:s');
            $order_data['date_modified'] = date('Y-m-d H:i:s');

            // --- Товары ---
            $order_data['products'] = $this->cart->getProducts();

            // --- Totals ---
            $order_data['totals'] = [
                [
                    'code' => 'total',
                    'title' => 'Итого',
                    'value' => $order_data['total'],
                    'sort_order' => 1
                ]
            ];

            // --- Создание заказа ---
            $order_id = $this->model_checkout_order->addOrder($order_data);

            if (!$order_id) {
                throw new Exception('Не удалось создать заказ (addOrder вернул 0). Проверьте обязательные поля.');
            }

            $json['success'] = true;
            $json['order_id'] = $order_id;
            $json['redirect'] = $this->url->link('checkout/success');

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }  catch (Exception $exception) {
            $json = [
                'error'   => true,
                'message' => $exception->getMessage() ?: 'Неизвестная ошибка',
                'trace'   => $exception->getTraceAsString()
            ];

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json, JSON_UNESCAPED_UNICODE));
        }
    }
}
