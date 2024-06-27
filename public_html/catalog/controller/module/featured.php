<?php
class ControllerModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('module/featured');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

                    $promos = $this->model_catalog_product->getPromo($product_info['product_id']);

                    if($promos){
                        foreach ($promos as $k => $promo) {

                        $product_promo[$k]['text'] = $promo['name'];
                        $product_promo[$k]['image'] = $this->model_tool_image->resize($promo['image'], (int)$promo['width'], (int)$promo['height']);
                        $product_promo[$k]['usename'] = $promo['usename'];

                        switch ($promo['position']) {
                            case '1':
                                $position = "left: 17px; top: 15px";
                                break;
                            case '2':
                                $position = "left: 50%; top: 15px; margin-left:-".(int)($promo['width']/2)."px";
                                break;
                            case '3':
                                $position = "right: 17px; top: 15px";
                                break;
                            case '4':
                                $position = "left: 17px; top: 50%; margin-top:-".(int)($promo['height']/2)."px";
                                break;
                            case '5':
                                $position = "left: 50%; top: 50% ; margin-left:-".(int)($promo['width']/2)."px;; margin-top:-".(int)($promo['height']/2)."px";
                                break;
                            case '6':
                                $position = "right: 17px; top: 50%; margin-top:-".(int)($promo['height']/2)."px";
                                break;
                            case '7':
                                $position = "left: 17px; bottom: 135px";
                                break;
                            case '8':
                                $position = "left: 50%; bottom: 135px; margin-left:-".(int)($promo['width']/2)."px";
                                break;
                            case '9':
                                $position = "right: 17px; bottom: 135px";
                                break;
                            default:
                                $position = "left: 17px; top: 15px";
                                break;
                        }

                        $product_promo[$k]['position'] = $position;
                        $product_promo[$k]['spanposition'] = "position: absolute;left: 0px;top: 19px;text-align: center;width: 120px";
                    }
                    } else {
                        $product_promo = array();
                    }

					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
                        'promos'      => $product_promo,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
            if(isset($setting['is_home']) && $setting['is_home']){
                return $this->load->view('module/featured_home', $data);
            } else {
                return $this->load->view('module/featured', $data);
            }
			
		}
	}
}