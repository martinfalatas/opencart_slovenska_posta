<?php
class ControllerExtensionPaymentSlovenskaPostaCod extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');

        return $this->load->view('extension/payment/slovenska_posta_cod', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'slovenska_posta_cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('slovenska_posta_cod_order_status_id'));
		}
	}
}