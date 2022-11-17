<?php
class ControllerExtensionPaymentSlovenskaPostaCod extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/slovenska_posta_cod');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('slovenska_posta_cod', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_extension'] = $this->language->get('text_extension');


		$data['entry_order_status'] = $this->language->get('entry_order_status');

		$data['entry_show_price'] = $this->language->get('entry_show_price');

        $data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/slovenska_posta_cod', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/slovenska_posta_cod', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);



		if (isset($this->request->post['slovenska_posta_cod_order_status_id'])) {
			$data['slovenska_posta_cod_order_status_id'] = $this->request->post['slovenska_posta_cod_order_status_id'];
		} else {
			$data['slovenska_posta_cod_order_status_id'] = $this->config->get('slovenska_posta_cod_order_status_id');
		}
		if (isset($this->request->post['slovenska_posta_cod_show_price'])) {
			$data['slovenska_posta_cod_show_price'] = $this->request->post['slovenska_posta_cod_show_price'];
		} else {
			$data['slovenska_posta_cod_show_price'] = $this->config->get('slovenska_posta_cod_show_price');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();



		if (isset($this->request->post['slovenska_posta_cod_status'])) {
			$data['slovenska_posta_cod_status'] = $this->request->post['slovenska_posta_cod_status'];
		} else {
			$data['slovenska_posta_cod_status'] = $this->config->get('slovenska_posta_cod_status');
		}

		if (isset($this->request->post['slovenska_posta_cod_sort_order'])) {
			$data['slovenska_posta_cod_sort_order'] = $this->request->post['slovenska_posta_cod_sort_order'];
		} else {
			$data['slovenska_posta_cod_sort_order'] = $this->config->get('slovenska_posta_cod_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/slovenska_posta_cod.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/slovenska_posta_cod')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}