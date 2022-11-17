<?php
class ModelExtensionTotalSlovenskaPostaCodFee extends Model {
	public function getTotal($total) {
		if ($this->config->get('slovenska_posta_cod_fee_status') && isset($this->session->data['payment_method']) && $this->session->data['payment_method']['code'] == 'slovenska_posta_cod') {
			
			$this->load->language('extension/total/slovenska_posta_cod_fee');


            //status dobierky pre službu
            $shipping_cod_status=isset($this->session->data['shipping_method']['sp_cod_status'])?$this->session->data['shipping_method']['sp_cod_status']: false;

            //ak nemá povolenú dobierku, ukončiť
            if(!$shipping_cod_status) return ;

            //cena za dobierku
            $shipping_cod_fee = $this->session->data['shipping_method']['sp_cod_fee'];

			
			$fee_amount = 0;
            if ($shipping_cod_status){
                $fee_amount = $shipping_cod_fee;
            }

			$tax_rates = $this->tax->getRates($fee_amount, $this->config->get('slovenska_posta_tax_class_id'));

            foreach ($tax_rates as $tax_rate) {
                if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
                    $total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                } else {
                    $total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                }
            }

            $total['totals'][] = array(
				'code'       => 'slovenska_posta_cod_fee',
				'title'      => $this->language->get('text_slovenska_posta_cod_fee'),
				'value'      => $fee_amount,
				'sort_order' => $this->config->get('slovenska_posta_cod_fee_sort_order')
			);

            $total['total'] += $fee_amount;
		}
	}
}
