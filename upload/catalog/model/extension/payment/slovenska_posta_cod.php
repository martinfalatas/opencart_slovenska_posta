<?php
class ModelExtensionPaymentSlovenskaPostaCod extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/slovenska_posta_cod');

        //status dobierky pre službu
        $shipping_cod_status=isset($this->session->data['shipping_method']['sp_cod_status']) ? $this->session->data['shipping_method']['sp_cod_status'] : false;

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('slovenska_posta_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('slovenska_posta_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

        // payment works only with total
        if(!$this->config->get('slovenska_posta_cod_fee_status')){
            $status = false;
        }

		$method_data = array();

        if($status){
            // order/edit
            if(!isset($this->session->data['shipping_method']['code'])){

                $method_data = array(
                    'code'       => 'slovenska_posta_cod',
                    'title'      => $this->language->get('text_title'),
                    'terms'      => '',
                    'sort_order' => $this->config->get('slovenska_posta_cod_sort_order')
                );

            //checkout
            }elseif($shipping_cod_status){

                if($this->config->get('slovenska_posta_cod_show_price')){
                    $cod_fee = " (+" . $this->currency->format($this->tax->calculate($this->session->data['shipping_method']['sp_cod_fee'], $this->config->get('slovenska_posta_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']) . ")";
                }else{
                    $cod_fee = '';
                }

                $method_data = array(
                    'code'       => 'slovenska_posta_cod',
                    'title'      => $this->language->get('text_title') . $cod_fee,
                    'terms'      => '',
                    'sort_order' => $this->config->get('slovenska_posta_cod_sort_order')
                );
            }
        }


		return $method_data;
	}
}