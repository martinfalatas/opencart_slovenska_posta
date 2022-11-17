<?php

class ControllerExtensionShippingSlovenskaPosta extends Controller {
    private $error = array();

    public function fill($tag){
            if (isset($this->request->post[$tag])) {
                $data = $this->request->post[$tag];
            } else {
                $data = $this->config->get($tag);
            }
        return $data;
    }

    public function fillAndSet($tag, $default){
        if (isset($this->request->post[$tag])) {
            $data = $this->request->post[$tag];
        } elseif ($this->config->has($tag)) {
            $data = $this->config->get($tag);
        } else {
            $data = $default;
        }
        return $data;
    }



    public function index() {

        $this->load->language('extension/shipping/slovenska_posta');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('slovenska_posta', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
        }

        $data['heading_title']              = $this->language->get('heading_title');

        $data['text_tab_main']              = $this->language->get('text_tab_main');
        $data['text_tab_sk']                = $this->language->get('text_tab_sk');
        $data['text_tab_cz']                = $this->language->get('text_tab_cz');
        $data['text_tab_eu']                = $this->language->get('text_tab_eu');
        $data['text_tab_world']             = $this->language->get('text_tab_world');

        $data['text_edit']                  = $this->language->get('text_edit');
        $data['text_enabled_sp']            = $this->language->get('text_enabled_sp');
        $data['text_disabled_sp']           = $this->language->get('text_disabled_sp');
        $data['text_all_zones']             = $this->language->get('text_all_zones');
        $data['text_none']                  = $this->language->get('text_none');
        $data['text_tab_general']           = $this->language->get('text_tab_general');
        $data['text_tab_list']              = $this->language->get('text_tab_list');
        $data['text_tab_dop_list']          = $this->language->get('text_tab_dop_list');
        $data['text_tab_poistenie_list']    = $this->language->get('text_tab_poistenie_list');
        $data['text_tab_balik_posta']       = $this->language->get('text_tab_balik_posta');
        $data['text_tab_easy']              = $this->language->get('text_tab_easy');
        $data['text_tab_kurier']            = $this->language->get('text_tab_kurier');
        $data['text_tab_epg']               = $this->language->get('text_tab_epg');
        $data['text_tab_ems']               = $this->language->get('text_tab_ems');
        $data['text_tab_free']              = $this->language->get('text_tab_free');


        $data['entry_display_weight']       = $this->language->get('entry_display_weight');
        $data['entry_display_insurance']    = $this->language->get('entry_display_insurance');
        $data['entry_max_insurance']        = $this->language->get('entry_max_insurance');
        $data['entry_disable_insurance']    = $this->language->get('entry_disable_insurance');
        $data['entry_weight_class']         = $this->language->get('entry_weight_class');
        $data['entry_tax_class']            = $this->language->get('entry_tax_class');
        $data['entry_geo_zone']             = $this->language->get('entry_geo_zone');
        $data['entry_status']               = $this->language->get('entry_status');
        $data['entry_sort_order']           = $this->language->get('entry_sort_order');
        $data['entry_free_status']          = $this->language->get('entry_free_status');
        $data['entry_free_cena']            = $this->language->get('entry_free_cena');
        $data['entry_free_vaha']            = $this->language->get('entry_free_vaha');
        $data['entry_rate']                 = $this->language->get('entry_rate');
        $data['entry_balik_posta_cena']     = $this->language->get('entry_balik_posta_cena');
        $data['entry_balik_adresa_cena']    = $this->language->get('entry_balik_adresa_cena');
        $data['entry_sk_balik_posta_status'] = $this->language->get('entry_sk_balik_posta_status');
        $data['entry_sk_balik_adresa_status'] = $this->language->get('entry_sk_balik_adresa_status');
        $data['entry_sk_balik_posta_status'] = $this->language->get('entry_sk_balik_posta_status');
        $data['entry_sk_balik_poistenie_status'] = $this->language->get('entry_sk_balik_poistenie_status');
        $data['entry_sk_balik_poistenie_sadzba'] = $this->language->get('entry_sk_balik_poistenie_sadzba');
        $data['entry_box_vaha']             = $this->language->get('entry_box_vaha');
        $data['entry_krehke']               = $this->language->get('entry_krehke');
        $data['entry_krehke_cena']          = $this->language->get('entry_krehke_cena');
        $data['entry_neskladne']            = $this->language->get('entry_neskladne');
        $data['entry_neskladne_cena']       = $this->language->get('entry_neskladne_cena');
        $data['entry_dorucenka']            = $this->language->get('entry_dorucenka');
        $data['entry_dorucenka_cena']       = $this->language->get('entry_dorucenka_cena');
        $data['entry_dobierka']             = $this->language->get('entry_dobierka');
        $data['entry_dobierka_cena']        = $this->language->get('entry_dobierka_cena');
        $data['entry_do_ruk']               = $this->language->get('entry_do_ruk');
        $data['entry_do_ruk_cena']          = $this->language->get('entry_do_ruk_cena');
        $data['entry_sk_list_1_trieda_sadzba'] = $this->language->get('entry_sk_list_1_trieda_sadzba');
        $data['entry_sk_list_2_trieda_sadzba'] = $this->language->get('entry_sk_list_2_trieda_sadzba');
        $data['entry_sk_list_1_status']     = $this->language->get('entry_sk_list_1_status');
        $data['entry_sk_list_2_status']     = $this->language->get('entry_sk_list_2_status');
        $data['entry_sk_poistenie_list_1_30_sadzba'] = $this->language->get('entry_sk_poistenie_list_1_30_sadzba');
        $data['entry_sk_poistenie_list_2_30_sadzba'] = $this->language->get('entry_sk_poistenie_list_2_30_sadzba');
        $data['entry_cz_poistenie_list_2_100_sadzba'] = $this->language->get('entry_cz_poistenie_list_2_100_sadzba');
        $data['entry_cz_poistenie_list_1_100_sadzba'] = $this->language->get('entry_cz_poistenie_list_1_100_sadzba');
        $data['entry_cz_poistenie_list_1_500_sadzba'] = $this->language->get('entry_cz_poistenie_list_1_500_sadzba');
        $data['entry_cz_poistenie_list_1_1000_sadzba'] = $this->language->get('entry_cz_poistenie_list_1_1000_sadzba');
        $data['entry_sk_easy_plast_status'] = $this->language->get('entry_sk_easy_plast_status');
        $data['entry_sk_easy_plast_sadzba'] = $this->language->get('entry_sk_easy_plast_sadzba');
        $data['entry_sk_easy_karton_status'] = $this->language->get('entry_sk_easy_karton_status');
        $data['entry_sk_easy_karton_sadzba'] = $this->language->get('entry_sk_easy_karton_sadzba');
        $data['entry_do_desiatej_cena']     = $this->language->get('entry_do_desiatej_cena');
        $data['entry_do_strnastej_cena']    = $this->language->get('entry_do_strnastej_cena');
        $data['entry_dorucenie_info']       = $this->language->get('entry_dorucenie_info');
        $data['entry_doruciť_sobota_cena']  = $this->language->get('entry_doruciť_sobota_cena');
        $data['entry_dorucenie_info_cena']  = $this->language->get('entry_dorucenie_info_cena');
        $data['entry_sk_kurier_posta_status'] = $this->language->get('entry_sk_kurier_posta_status');
        $data['entry_sk_kurier_posta_sadzba'] = $this->language->get('entry_sk_kurier_posta_sadzba');
        $data['entry_sk_kurier_adresa_status'] = $this->language->get('entry_sk_kurier_adresa_status');
        $data['entry_sk_kurier_adresa_cena'] = $this->language->get('entry_sk_kurier_adresa_cena');
        $data['entry_sk_kurier_podaj_kurier_status'] = $this->language->get('entry_sk_kurier_podaj_kurier_status');
        $data['entry_sk_kurier_podaj_kurier_sadzba'] = $this->language->get('entry_sk_kurier_podaj_kurier_sadzba');
        $data['entry_sk_kurier_vdenpodania_cena'] = $this->language->get('entry_sk_kurier_vdenpodania_cena');
        $data['entry_delivery_time']        = $this->language->get('entry_delivery_time');
        $data['help_rate']                  = $this->language->get('help_rate');
        $data['help_display_weight']        = $this->language->get('help_display_weight');
        $data['help_display_insurance']     = $this->language->get('help_display_insurance');
        $data['help_disable_insurance']     = $this->language->get('help_disable_insurance');
        $data['help_max_insurance']         = $this->language->get('help_max_insurance');
        $data['help_krehke']                = $this->language->get('help_krehke');
        $data['help_box_vaha']              = $this->language->get('help_box_vaha');
        $data['help_balik_poistenie_sadzba'] = $this->language->get('help_balik_poistenie_sadzba');
        $data['help_free_delivery']         = $this->language->get('help_free_delivery');
        $data['button_save']                = $this->language->get('button_save');
        $data['button_cancel']              = $this->language->get('button_cancel');
        $data['text_yes']                   = $this->language->get('text_yes');
        $data['text_no']                    = $this->language->get('text_no');

        //notifikacia
        $data['text_loading_notifications'] = $this->language->get( 'text_loading_notifications' );
        $data['error_notifications'] = $this->language->get( 'error_notifications' );
        $data['text_retry'] = $this->language->get( 'text_retry' );
        $data['error_no_news'] = $this->language->get( 'error_no_news' );
        $data['token'] = $this->session->data['token'];

        //egp ems
        $data['entry_rate_tar_1']                    = $this->language->get('entry_rate_tar_1');
        $data['entry_rate_tar_2']                    = $this->language->get('entry_rate_tar_2');
        $data['entry_rate_tar_3']                    = $this->language->get('entry_rate_tar_3');
        $data['entry_rate_tar_4']                    = $this->language->get('entry_rate_tar_4');
        $data['entry_rate_tar_5']                    = $this->language->get('entry_rate_tar_5');
        $data['entry_rate_tar_eu0']                  = $this->language->get('entry_rate_tar_eu0');
        $data['entry_rate_tar_eu1']                  = $this->language->get('entry_rate_tar_eu1');
        $data['entry_rate_tar_eu2']                  = $this->language->get('entry_rate_tar_eu2');

        $data['entry_free_vaha']                    = $this->language->get('entry_free_vaha');
        $data['help_free_delivery_vaha']            = $this->language->get('help_free_delivery_vaha');


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
        );


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/slovenska_posta', 'token=' . $this->session->data['token'], true)
        );


        $data['action'] = $this->url->link('extension/shipping/slovenska_posta', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);



        $data['slovenska_posta_display_weight'] = $this->fill('slovenska_posta_display_weight');
        $data['slovenska_posta_display_insurance'] = $this->fill('slovenska_posta_display_insurance');
        $data['slovenska_posta_allow_max_insurance'] = $this->fill('slovenska_posta_allow_max_insurance');
        $data['slovenska_posta_disable_insurance'] = $this->fill('slovenska_posta_disable_insurance');

        $data['slovenska_posta_weight_class_id'] = $this->fill('slovenska_posta_weight_class_id');
        $this->load->model('localisation/weight_class');
        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        $this->load->model('localisation/tax_class');
        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
        $data['slovenska_posta_tax_class_id'] = $this->fill('slovenska_posta_tax_class_id');

        $data['slovenska_posta_geo_zone_id'] = $this->fill('slovenska_posta_geo_zone_id');
        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['slovenska_posta_status'] = $this->fill('slovenska_posta_status');
        $data['slovenska_posta_sort_order'] = $this->fill('slovenska_posta_sort_order');


        /*doprava zdarma dobierka*/
        $data['slovenska_posta_sk_free_dobierka_status'] = $this->fill('slovenska_posta_sk_free_dobierka_status');
        $data['slovenska_posta_sk_free_dobierka_cena'] = $this->fillAndSet('slovenska_posta_sk_free_dobierka_cena', '0.00');

        $data['slovenska_posta_cz_free_dobierka_status'] = $this->fill('slovenska_posta_cz_free_dobierka_status');
        $data['slovenska_posta_cz_free_dobierka_cena'] = $this->fillAndSet('slovenska_posta_cz_free_dobierka_cena', '0.00');

        $data['slovenska_posta_eu_free_dobierka_status'] = $this->fill('slovenska_posta_eu_free_dobierka_status');
        $data['slovenska_posta_eu_free_dobierka_cena'] = $this->fillAndSet('slovenska_posta_eu_free_dobierka_cena', '0.00');

        $data['slovenska_posta_world_free_dobierka_status'] = $this->fill('slovenska_posta_world_free_dobierka_status');
        $data['slovenska_posta_world_free_dobierka_cena'] = $this->fillAndSet('slovenska_posta_world_free_dobierka_cena', '0.00');


        $data['slovenska_posta_sk_status'] = $this->fill('slovenska_posta_sk_status');
        $data['slovenska_posta_cz_status'] = $this->fill('slovenska_posta_cz_status');
        $data['slovenska_posta_eu_status'] = $this->fill('slovenska_posta_eu_status');
        $data['slovenska_posta_world_status'] = $this->fill('slovenska_posta_world_status');
        $data['slovenska_posta_sk_free_status'] = $this->fill('slovenska_posta_sk_free_status');
        $data['slovenska_posta_sk_free_cena'] = $this->fillAndSet('slovenska_posta_sk_free_cena', '0.00');
        $data['slovenska_posta_cz_free_status'] = $this->fill('slovenska_posta_cz_free_status');
        $data['slovenska_posta_cz_free_cena'] = $this->fillAndSet('slovenska_posta_cz_free_cena', '0.00');
        $data['slovenska_posta_eu_free_status'] = $this->fill('slovenska_posta_eu_free_status');
        $data['slovenska_posta_eu_free_cena'] = $this->fillAndSet('slovenska_posta_eu_free_cena', '0.00');
        $data['slovenska_posta_world_free_status'] = $this->fill('slovenska_posta_world_free_status');
        $data['slovenska_posta_world_free_cena'] = $this->fillAndSet('slovenska_posta_world_free_cena', '0.00');
        $data['slovenska_posta_sk_list_1_status'] = $this->fill('slovenska_posta_sk_list_1_status');
        $data['slovenska_posta_sk_list_1_sadzba'] = $this->fillAndSet('slovenska_posta_sk_list_1_sadzba', '0.05:0.70,0.1:0.85,0.5:1.15,1:1.85,2:2.80');
        $data['slovenska_posta_sk_list_2_status'] = $this->fill('slovenska_posta_sk_list_2_status');
        $data['slovenska_posta_sk_list_2_sadzba'] = $this->fillAndSet('slovenska_posta_sk_list_2_sadzba', '0.05:0.50,0.1:0.65,0.5:0.95,1:1.65,2:2.60');
        $data['slovenska_posta_sk_list_box_vaha'] = $this->fillAndSet('slovenska_posta_sk_list_box_vaha', '0.00');
        $data['slovenska_posta_sk_list_status'] = $this->fill('slovenska_posta_sk_list_status');
        $data['slovenska_posta_cz_list_1_sadzba'] = $this->fillAndSet('slovenska_posta_cz_list_1_sadzba', '0.05:0.95,0.1:1.30,0.5:3.00,1:6.80,2:11.50');
        $data['slovenska_posta_cz_list_box_vaha'] = $this->fillAndSet('slovenska_posta_cz_list_box_vaha', '0.00');
        $data['slovenska_posta_cz_list_status'] = $this->fill('slovenska_posta_cz_list_status');
        $data['slovenska_posta_eu_list_1_sadzba'] = $this->fillAndSet('slovenska_posta_eu_list_1_sadzba', '0.05:1.10,0.1:1.70,0.5:4.20,1:8.00,2:13.00');
        $data['slovenska_posta_eu_list_box_vaha'] = $this->fillAndSet('slovenska_posta_eu_list_box_vaha', '0.00');
        $data['slovenska_posta_eu_list_status'] = $this->fill('slovenska_posta_eu_list_status');
        $data['slovenska_posta_world_list_1_sadzba'] = $this->fillAndSet('slovenska_posta_world_list_1_sadzba', '0.05:1.30,0.1:2.10,0.5:6.00,1:14.50,2:27.00');
        $data['slovenska_posta_world_list_box_vaha'] = $this->fillAndSet('slovenska_posta_world_list_box_vaha', '0.00');
        $data['slovenska_posta_world_list_status'] = $this->fill('slovenska_posta_world_list_status');
        $data['slovenska_posta_sk_dop_list_1_status'] = $this->fill('slovenska_posta_sk_dop_list_1_status');
        $data['slovenska_posta_sk_dop_list_1_sadzba'] = $this->fillAndSet('slovenska_posta_sk_dop_list_1_sadzba', '0.05:1.45,0.1:1.60,0.5:1.90,1:2.60,2:3.55');
        $data['slovenska_posta_sk_dop_list_2_status'] = $this->fill('slovenska_posta_sk_dop_list_2_status');
        $data['slovenska_posta_sk_dop_list_2_sadzba'] = $this->fillAndSet('slovenska_posta_sk_dop_list_2_sadzba', '0.05:1.25,0.1:1.40,0.5:1.70,1:2.40,2:3.35');
        $data['slovenska_posta_sk_dop_list_dobierka_status'] = $this->fill('slovenska_posta_sk_dop_list_dobierka_status');
        $data['slovenska_posta_sk_dop_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_sk_dop_list_dobierka_cena', '1.20');
        $data['slovenska_posta_sk_dop_list_dorucenka_status'] = $this->fill('slovenska_posta_sk_dop_list_dorucenka_status');
        $data['slovenska_posta_sk_dop_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_sk_dop_list_dorucenka_cena', '0.45');
        $data['slovenska_posta_sk_dop_list_do_ruk_status'] = $this->fill('slovenska_posta_sk_dop_list_do_ruk_status');
        $data['slovenska_posta_sk_dop_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_sk_dop_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_sk_dop_list_box_vaha'] = $this->fillAndSet('slovenska_posta_sk_dop_list_box_vaha', '0.00');
        $data['slovenska_posta_sk_dop_list_status'] = $this->fill('slovenska_posta_sk_dop_list_status');
        $data['slovenska_posta_cz_dop_list_sadzba'] = $this->fillAndSet('slovenska_posta_cz_dop_list_sadzba', '0.05:2.35,0.1:2.90,0.5:4.80,1:8.90,2:14.00');
        $data['slovenska_posta_cz_dop_list_dobierka_status'] = $this->fill('slovenska_posta_cz_dop_list_dobierka_status');
        $data['slovenska_posta_cz_dop_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_cz_dop_list_dobierka_cena', '0.20');
        $data['slovenska_posta_cz_dop_list_dorucenka_status'] = $this->fill('slovenska_posta_cz_dop_list_dorucenka_status');
        $data['slovenska_posta_cz_dop_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_cz_dop_list_dorucenka_cena', '0.90');
        $data['slovenska_posta_cz_dop_list_do_ruk_status'] = $this->fill('slovenska_posta_cz_dop_list_do_ruk_status');
        $data['slovenska_posta_cz_dop_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_cz_dop_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_cz_dop_list_box_vaha'] = $this->fillAndSet('slovenska_posta_cz_dop_list_box_vaha', '0.00');
        $data['slovenska_posta_cz_dop_list_status'] = $this->fill('slovenska_posta_cz_dop_list_status');
        $data['slovenska_posta_eu_dop_list_sadzba'] = $this->fillAndSet('slovenska_posta_eu_dop_list_sadzba', '0.05:2.50,0.1:3.20,0.5:6.10,1:9.50,2:15.40');
        $data['slovenska_posta_eu_dop_list_dobierka_status'] = $this->fill('slovenska_posta_eu_dop_list_dobierka_status');
        $data['slovenska_posta_eu_dop_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_eu_dop_list_dobierka_cena', '0.20');
        $data['slovenska_posta_eu_dop_list_dorucenka_status'] = $this->fill('slovenska_posta_eu_dop_list_dorucenka_status');
        $data['slovenska_posta_eu_dop_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_eu_dop_list_dorucenka_cena', '0.90');
        $data['slovenska_posta_eu_dop_list_do_ruk_status'] = $this->fill('slovenska_posta_eu_dop_list_do_ruk_status');
        $data['slovenska_posta_eu_dop_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_eu_dop_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_eu_dop_list_box_vaha'] = $this->fillAndSet('slovenska_posta_eu_dop_list_box_vaha', '0.00');
        $data['slovenska_posta_eu_dop_list_status'] = $this->fill('slovenska_posta_eu_dop_list_status');
        $data['slovenska_posta_world_dop_list_sadzba'] = $this->fillAndSet('slovenska_posta_world_dop_list_sadzba', '0.05:2.80,0.1:3.60,0.5:8.10,1:17.00,2:29.50');
        $data['slovenska_posta_world_dop_list_dobierka_status'] = $this->fill('slovenska_posta_world_dop_list_dobierka_status');
        $data['slovenska_posta_world_dop_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_world_dop_list_dobierka_cena', '0.20');
        $data['slovenska_posta_world_dop_list_dorucenka_status'] = $this->fill('slovenska_posta_world_dop_list_dorucenka_status');
        $data['slovenska_posta_world_dop_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_world_dop_list_dorucenka_cena', '0.90');
        $data['slovenska_posta_world_dop_list_do_ruk_status'] = $this->fill('slovenska_posta_world_dop_list_do_ruk_status');
        $data['slovenska_posta_world_dop_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_world_dop_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_world_dop_list_box_vaha'] = $this->fillAndSet('slovenska_posta_world_dop_list_box_vaha', '0.00');
        $data['slovenska_posta_world_dop_list_status'] = $this->fill('slovenska_posta_world_dop_list_status');
        $data['slovenska_posta_sk_poistenie_list_1_status'] = $this->fill('slovenska_posta_sk_poistenie_list_1_status');
        $data['slovenska_posta_sk_poistenie_list_1_sadzba'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_1_sadzba', '0.05:1.75,0.1:1.90,0.5:2.20,1:2.90,2:3.85');
        $data['slovenska_posta_sk_poistenie_list_2_status'] = $this->fill('slovenska_posta_sk_poistenie_list_2_status');
        $data['slovenska_posta_sk_poistenie_list_2_sadzba'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_2_sadzba', '0.05:1.55,0.1:1.70,0.5:2.00,1:2.70,2:3.65');
        $data['slovenska_posta_sk_poistenie_list_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_poistenie_sadzba', '30:0.00,150:0.20,300:0.40,500:0.60');
        $data['slovenska_posta_sk_poistenie_list_dobierka_status'] = $this->fill('slovenska_posta_sk_poistenie_list_dobierka_status');
        $data['slovenska_posta_sk_poistenie_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_dobierka_cena', '1.20');
        $data['slovenska_posta_sk_poistenie_list_dorucenka_status'] = $this->fill('slovenska_posta_sk_poistenie_list_dorucenka_status');
        $data['slovenska_posta_sk_poistenie_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_dorucenka_cena', '0.45');
        $data['slovenska_posta_sk_poistenie_list_do_ruk_status'] = $this->fill('slovenska_posta_sk_poistenie_list_do_ruk_status');
        $data['slovenska_posta_sk_poistenie_list_box_vaha'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_box_vaha', '0.00');
        $data['slovenska_posta_sk_poistenie_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_sk_poistenie_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_sk_poistenie_list_status'] = $this->fill('slovenska_posta_sk_poistenie_list_status');
        $data['slovenska_posta_cz_poistenie_list_1_100_sadzba'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_1_100_sadzba', '0.05:2.95,0.1:3.40,0.5:5.20,1:9.30,2:14.30');
        $data['slovenska_posta_cz_poistenie_list_1_500_sadzba'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_1_500_sadzba', '0.05:5.15,0.1:5.70,0.5:7.60,1:11.50,2:16.50');
        $data['slovenska_posta_cz_poistenie_list_1_1000_sadzba'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_1_1000_sadzba', '0.05:7.85,0.1:8.40,0.5:10.30,1:14.00,2:18.50');
        $data['slovenska_posta_cz_poistenie_list_dobierka_status'] = $this->fill('slovenska_posta_cz_poistenie_list_dobierka_status');
        $data['slovenska_posta_cz_poistenie_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_dobierka_cena', '0.20');
        $data['slovenska_posta_cz_poistenie_list_dorucenka_status'] = $this->fill('slovenska_posta_cz_poistenie_list_dorucenka_status');
        $data['slovenska_posta_cz_poistenie_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_dorucenka_cena', '0.90');
        $data['slovenska_posta_cz_poistenie_list_do_ruk_status'] = $this->fill('slovenska_posta_cz_poistenie_list_do_ruk_status');
        $data['slovenska_posta_cz_poistenie_list_box_vaha'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_box_vaha', '0.00');
        $data['slovenska_posta_cz_poistenie_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_cz_poistenie_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_cz_poistenie_list_status'] = $this->fill('slovenska_posta_cz_poistenie_list_status');
        $data['slovenska_posta_eu_poistenie_list_1_100_sadzba'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_1_100_sadzba', '0.05:3.10,0.1:3.80,0.5:6.50,1:10.40,2:15.90');
        $data['slovenska_posta_eu_poistenie_list_1_500_sadzba'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_1_500_sadzba', '0.05:5.30,0.1:6.00,0.5:8.80,1:12.50,2:17.90');
        $data['slovenska_posta_eu_poistenie_list_1_1000_sadzba'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_1_1000_sadzba', '0.05:8.10,0.1:8.80,0.5:11.60,1:15.40,2:20.50');
        $data['slovenska_posta_eu_poistenie_list_dobierka_status'] = $this->fill('slovenska_posta_eu_poistenie_list_dobierka_status');
        $data['slovenska_posta_eu_poistenie_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_dobierka_cena', '0.20');
        $data['slovenska_posta_eu_poistenie_list_dorucenka_status'] = $this->fill('slovenska_posta_eu_poistenie_list_dorucenka_status');
        $data['slovenska_posta_eu_poistenie_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_dorucenka_cena', '0.90');
        $data['slovenska_posta_eu_poistenie_list_do_ruk_status'] = $this->fill('slovenska_posta_eu_poistenie_list_do_ruk_status');
        $data['slovenska_posta_eu_poistenie_list_box_vaha'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_box_vaha', '0.00');
        $data['slovenska_posta_eu_poistenie_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_eu_poistenie_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_eu_poistenie_list_status'] = $this->fill('slovenska_posta_eu_poistenie_list_status');
        $data['slovenska_posta_world_poistenie_list_1_100_sadzba'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_1_100_sadzba', '0.05:3.30,0.1:4.20,0.5:8.60,1:17.50,2:30.00');
        $data['slovenska_posta_world_poistenie_list_1_500_sadzba'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_1_500_sadzba', '0.05:5.60,0.1:6.40,0.5:10.50,1:19.40,2:31.90');
        $data['slovenska_posta_world_poistenie_list_1_1000_sadzba'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_1_1000_sadzba', '0.05:8.40,0.1:9.20,0.5:13.50,1:22.00,2:34.00');
        $data['slovenska_posta_world_poistenie_list_dobierka_status'] = $this->fill('slovenska_posta_world_poistenie_list_dobierka_status');
        $data['slovenska_posta_world_poistenie_list_dobierka_cena'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_dobierka_cena', '0.20');
        $data['slovenska_posta_world_poistenie_list_dorucenka_status'] = $this->fill('slovenska_posta_world_poistenie_list_dorucenka_status');
        $data['slovenska_posta_world_poistenie_list_dorucenka_cena'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_dorucenka_cena', '0.90');
        $data['slovenska_posta_world_poistenie_list_do_ruk_status'] = $this->fill('slovenska_posta_world_poistenie_list_do_ruk_status');
        $data['slovenska_posta_world_poistenie_list_box_vaha'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_box_vaha', '0.00');
        $data['slovenska_posta_world_poistenie_list_do_ruk_cena'] = $this->fillAndSet('slovenska_posta_world_poistenie_list_do_ruk_cena', '0.20');
        $data['slovenska_posta_world_poistenie_list_status'] = $this->fill('slovenska_posta_world_poistenie_list_status');
        $data['slovenska_posta_sk_balik_posta_status'] = $this->fill('slovenska_posta_sk_balik_posta_status');
        $data['slovenska_posta_sk_balik_posta_sadzba'] = $this->fillAndSet('slovenska_posta_sk_balik_posta_sadzba', '5:2.70,10:3.70');
        $data['slovenska_posta_sk_balik_adresa_status'] = $this->fill('slovenska_posta_sk_balik_adresa_status');
        $data['slovenska_posta_sk_balik_adresa_sadzba'] = $this->fillAndSet('slovenska_posta_sk_balik_adresa_sadzba', '5:3.90,10:4.90');
        $data['slovenska_posta_sk_balik_poistenie_status'] = $this->fill('slovenska_posta_sk_balik_poistenie_status');
        $data['slovenska_posta_sk_balik_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_sk_balik_poistenie_sadzba', '30:0.40,150:0.60,300:0.80,500:1.00');
        $data['slovenska_posta_sk_balik_dobierka_status'] = $this->fill('slovenska_posta_sk_balik_dobierka_status');
        $data['slovenska_posta_sk_balik_dobierka_cena'] = $this->fillAndSet('slovenska_posta_sk_balik_dobierka_cena', '1.20');
        $data['slovenska_posta_sk_balik_krehke_status'] = $this->fill('slovenska_posta_sk_balik_krehke_status');
        $data['slovenska_posta_sk_balik_krehke_cena'] = $this->fillAndSet('slovenska_posta_sk_balik_krehke_cena', '2.00');
        $data['slovenska_posta_sk_balik_neskladne_status'] = $this->fill('slovenska_posta_sk_balik_neskladne_status');
        $data['slovenska_posta_sk_balik_neskladne_cena'] = $this->fillAndSet('slovenska_posta_sk_balik_neskladne_cena', '2.00');
        $data['slovenska_posta_sk_balik_box_vaha'] = $this->fillAndSet('slovenska_posta_sk_balik_box_vaha', '0.00');
        $data['slovenska_posta_sk_balik_status'] = $this->fill('slovenska_posta_sk_balik_status');
        $data['slovenska_posta_cz_balik_1_sadzba'] = $this->fillAndSet('slovenska_posta_cz_balik_1_sadzba', '1:9.00,2:11.00,3:11.50,4:12.00,5:12.50,6:13.00,7:14.00,8:15.00,9:16.00,10:17.00');
        $data['slovenska_posta_cz_balik_poistenie_status'] = $this->fill('slovenska_posta_cz_balik_poistenie_status');
        $data['slovenska_posta_cz_balik_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_cz_balik_poistenie_sadzba', '100:0.50,500:2.50,1000:5.00');
        $data['slovenska_posta_cz_balik_dobierka_status'] = $this->fill('slovenska_posta_cz_balik_dobierka_status');
        $data['slovenska_posta_cz_balik_dobierka_cena'] = $this->fillAndSet('slovenska_posta_cz_balik_dobierka_cena', '0.20');
        $data['slovenska_posta_cz_balik_krehke_status'] = $this->fill('slovenska_posta_cz_balik_krehke_status');
        $data['slovenska_posta_cz_balik_krehke_cena'] = $this->fillAndSet('slovenska_posta_cz_balik_krehke_cena', '2.00');
        $data['slovenska_posta_cz_balik_neskladne_status'] = $this->fill('slovenska_posta_cz_balik_neskladne_status');
        $data['slovenska_posta_cz_balik_neskladne_cena'] = $this->fillAndSet('slovenska_posta_cz_balik_neskladne_cena', '2.00');
        $data['slovenska_posta_cz_balik_box_vaha'] = $this->fillAndSet('slovenska_posta_cz_balik_box_vaha', '0.00');
        $data['slovenska_posta_cz_balik_status'] = $this->fill('slovenska_posta_cz_balik_status');
        $data['slovenska_posta_eu_balik_1_sadzba'] = $this->fillAndSet('slovenska_posta_eu_balik_1_sadzba', '1:17.00,2:19.00,3:21.50,4:24.00,5:26.00,6:28.50,7:31.00,8:33.50,9:36.00,10:38.50');
        $data['slovenska_posta_eu_balik_poistenie_status'] = $this->fill('slovenska_posta_eu_balik_poistenie_status');
        $data['slovenska_posta_eu_balik_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_eu_balik_poistenie_sadzba', '100:0.50,500:2.50,1000:5.00');
        $data['slovenska_posta_eu_balik_dobierka_status'] = $this->fill('slovenska_posta_eu_balik_dobierka_status');
        $data['slovenska_posta_eu_balik_dobierka_cena'] = $this->fillAndSet('slovenska_posta_eu_balik_dobierka_cena', '0.20');
        $data['slovenska_posta_eu_balik_krehke_status'] = $this->fill('slovenska_posta_eu_balik_krehke_status');
        $data['slovenska_posta_eu_balik_krehke_cena'] = $this->fillAndSet('slovenska_posta_eu_balik_krehke_cena', '2.00');
        $data['slovenska_posta_eu_balik_neskladne_status'] = $this->fill('slovenska_posta_eu_balik_neskladne_status');
        $data['slovenska_posta_eu_balik_neskladne_cena'] = $this->fillAndSet('slovenska_posta_eu_balik_neskladne_cena', '2.00');
        $data['slovenska_posta_eu_balik_box_vaha'] = $this->fillAndSet('slovenska_posta_eu_balik_box_vaha', '0.00');
        $data['slovenska_posta_eu_balik_status'] = $this->fill('slovenska_posta_eu_balik_status');
        $data['slovenska_posta_world_balik_1_sadzba'] = $this->fillAndSet('slovenska_posta_world_balik_1_sadzba', '1:17.50,2:25.00,3:31.00,4:38.00,5:45.00,6:51.00,7:57.00,8:64.00,9:70.00,10:77.00');
        $data['slovenska_posta_world_balik_poistenie_status'] = $this->fill('slovenska_posta_world_balik_poistenie_status');
        $data['slovenska_posta_world_balik_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_world_balik_poistenie_sadzba', '100:0.50,500:2.50,1000:5.00');
        $data['slovenska_posta_world_balik_dobierka_status'] = $this->fill('slovenska_posta_world_balik_dobierka_status');
        $data['slovenska_posta_world_balik_dobierka_cena'] = $this->fillAndSet('slovenska_posta_world_balik_dobierka_cena', '0.20');
        $data['slovenska_posta_world_balik_krehke_status'] = $this->fill('slovenska_posta_world_balik_krehke_status');
        $data['slovenska_posta_world_balik_krehke_cena'] = $this->fillAndSet('slovenska_posta_world_balik_krehke_cena', '2.00');
        $data['slovenska_posta_world_balik_neskladne_status'] = $this->fill('slovenska_posta_world_balik_neskladne_status');
        $data['slovenska_posta_world_balik_neskladne_cena'] = $this->fillAndSet('slovenska_posta_world_balik_neskladne_cena', '2.00');
        $data['slovenska_posta_world_balik_box_vaha'] = $this->fillAndSet('slovenska_posta_world_balik_box_vaha', '0.00');
        $data['slovenska_posta_world_balik_status'] = $this->fill('slovenska_posta_world_balik_status');
        $data['slovenska_posta_sk_easy_plast_status'] = $this->fill('slovenska_posta_sk_easy_plast_status');
        $data['slovenska_posta_sk_easy_plast_sadzba'] = $this->fillAndSet('slovenska_posta_sk_easy_plast_sadzba', '1:5.90');
        $data['slovenska_posta_sk_easy_karton_status'] = $this->fill('slovenska_posta_sk_easy_karton_status');
        $data['slovenska_posta_sk_easy_karton_sadzba'] = $this->fillAndSet('slovenska_posta_sk_easy_karton_sadzba', '10:6.30');
        $data['slovenska_posta_sk_easy_poistenie_status'] = $this->fill('slovenska_posta_sk_easy_poistenie_status');
        $data['slovenska_posta_sk_easy_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_sk_easy_poistenie_sadzba', '1000:0.00,1500:1.50,2500:3.00,5000:7.50,10000:15.00,20000:30.00');
        $data['slovenska_posta_sk_easy_dobierka_status'] = $this->fill('slovenska_posta_sk_easy_dobierka_status');
        $data['slovenska_posta_sk_easy_dobierka_cena'] = $this->fillAndSet('slovenska_posta_sk_easy_dobierka_cena', '1.44');
        $data['slovenska_posta_sk_easy_delivery_time_id'] = $this->fill('slovenska_posta_sk_easy_delivery_time_id');
        $data['slovenska_posta_sk_easy_dodesiatej_cena'] = $this->fillAndSet('slovenska_posta_sk_easy_dodesiatej_cena', '3.00');
        $data['slovenska_posta_sk_easy_sobota_cena'] = $this->fillAndSet('slovenska_posta_sk_easy_sobota_cena', '3.00');
        $data['slovenska_posta_sk_easy_dorucenie_info_status'] = $this->fill('slovenska_posta_sk_easy_dorucenie_info_status');
        $data['slovenska_posta_sk_easy_dorucenie_info_cena'] = $this->fillAndSet('slovenska_posta_sk_easy_dorucenie_info_cena', '0.80');
        $data['slovenska_posta_sk_easy_doruk_status'] = $this->fill('slovenska_posta_sk_easy_doruk_status');
        $data['slovenska_posta_sk_easy_doruk_cena'] = $this->fillAndSet('slovenska_posta_sk_easy_doruk_cena', '0.24');
        $data['slovenska_posta_sk_easy_box_vaha'] = $this->fillAndSet('slovenska_posta_sk_easy_box_vaha', '0.00');
        $data['slovenska_posta_sk_easy_status'] = $this->fill('slovenska_posta_sk_easy_status');
        $data['slovenska_posta_sk_kurier_posta_status'] = $this->fill('slovenska_posta_sk_kurier_posta_status');
        $data['slovenska_posta_sk_kurier_posta_sadzba'] = $this->fillAndSet('slovenska_posta_sk_kurier_posta_sadzba',
            '1:5.00,3:5.15,5:5.30,10:5.60,15:5.90,20:6.50,25:7.25,30:9.00,40:13.00,50:17.60,75:22.80,100:29.20,125:38.00,150:45.00,200:55.00,250:65.00,300:75.00,350:88.00,400:98.00,450:108.00,500:118.00,600:134.00,700:150.00,800:166.00,900:182.00,1000:198.00');
        $data['slovenska_posta_sk_kurier_adresa_status'] = $this->fill('slovenska_posta_sk_kurier_adresa_status');
        $data['slovenska_posta_sk_kurier_adresa_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_adresa_cena', '1.00');
        $data['slovenska_posta_sk_kurier_podaj_kurier_status'] = $this->fill('slovenska_posta_sk_kurier_podaj_kurier_status');
        $data['slovenska_posta_sk_kurier_podaj_kurier_sadzba'] = $this->fillAndSet('slovenska_posta_sk_kurier_podaj_kurier_sadzba',
            '1:0.60,3:1.05,5:1.50,10:1.80,15:2.10,20:2.10,25:1.95,30:1.80,40:2.40,50:2.40,75:2.40,100:1.80,125:1.50,150:1.50,200:1.50,250:3.00,300:4.50');
        $data['slovenska_posta_sk_kurier_poistenie_status'] = $this->fill('slovenska_posta_sk_kurier_poistenie_status');
        $data['slovenska_posta_sk_kurier_poistenie_sadzba'] = $this->fillAndSet('slovenska_posta_sk_kurier_poistenie_sadzba',
            '1000:0.00,1500:1.50,2500:3.00,5000:7.50,10000:15.00,20000:30.00');
        $data['slovenska_posta_sk_kurier_dobierka_status'] = $this->fill('slovenska_posta_sk_kurier_dobierka_status');
        $data['slovenska_posta_sk_kurier_dobierka_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_dobierka_cena', '1.44');
        $data['slovenska_posta_sk_kurier_krehke_status'] = $this->fill('slovenska_posta_sk_kurier_krehke_status');
        $data['slovenska_posta_sk_kurier_krehke_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_krehke_cena', '2.40');
        $data['slovenska_posta_sk_kurier_neskladne_status'] = $this->fill('slovenska_posta_sk_kurier_neskladne_status');
        $data['slovenska_posta_sk_kurier_neskladne_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_neskladne_cena', '7.20');
        $data['slovenska_posta_sk_kurier_dorucenie_info_status'] = $this->fill('slovenska_posta_sk_kurier_dorucenie_info_status');
        $data['slovenska_posta_sk_kurier_dorucenie_info_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_dorucenie_info_cena', '0.80');
        $data['slovenska_posta_sk_kurier_doruk_status'] = $this->fill('slovenska_posta_sk_kurier_doruk_status');
        $data['slovenska_posta_sk_kurier_doruk_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_doruk_cena', '0.24');
        $data['slovenska_posta_sk_kurier_delivery_time_id'] = $this->fill('slovenska_posta_sk_kurier_delivery_time_id');
        $data['slovenska_posta_sk_kurier_denpodania_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_denpodania_cena', '17.40');
        $data['slovenska_posta_sk_kurier_do10_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_do10_cena', '3.00');
        $data['slovenska_posta_sk_kurier_do14_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_do14_cena', '1.20');
        $data['slovenska_posta_sk_kurier_sobota_cena'] = $this->fillAndSet('slovenska_posta_sk_kurier_sobota_cena', '3.00');
        $data['slovenska_posta_sk_kurier_box_vaha'] = $this->fillAndSet('slovenska_posta_sk_kurier_box_vaha', '0.00');
        $data['slovenska_posta_sk_kurier_status'] = $this->fill('slovenska_posta_sk_kurier_status');


        //---------
        $data['slovenska_posta_cz_epg_sadzba'] = $this->fillAndSet('slovenska_posta_cz_epg_sadzba',
            '1:12.00,2:13.00,3:14.00,4:15.00,5:16.00,6:17.00,7:18.00,8:19.00,9:21.00,10:22.00,11:22.50,12:23.50,13:24.50,14:25.50,15:26.50,16:27.50,17:28.50,18:30.50,19:31.50,20:32.50,21:33.50,22:34.50,23:35.50,24:36.50,25:38.50,26:39.50,27:40.50,28:41.50,29:42.50,30:43.50');
        $data['slovenska_posta_cz_epg_box_vaha'] = $this->fillAndSet('slovenska_posta_cz_epg_box_vaha', '0.00');
        $data['slovenska_posta_cz_epg_status'] = $this->fill('slovenska_posta_cz_epg_status');

        $data['slovenska_posta_cz_ems_sadzba'] = $this->fillAndSet('slovenska_posta_cz_ems_sadzba',
            '1:15.00,2:16.00,3:17.00,4:18.00,5:19.00,6:20.00,7:21.00,8:22.00,9:23.00,10:24.00,11:25.00,12:26.00,13:27.00,14:28.00,15:30.00,16:31.00,17:32.00,18:33.00,19:34.00,20:35.00,21:37.00,22:38.00,23:39.00,24:40.00,25:41.00,26:42.00,27:43.00,28:45.00,29:46.00,30:47.00');
        $data['slovenska_posta_cz_ems_box_vaha'] = $this->fillAndSet('slovenska_posta_cz_ems_box_vaha', '0.00');
        $data['slovenska_posta_cz_ems_status'] = $this->fill('slovenska_posta_cz_ems_status');


        //-------
        $data['slovenska_posta_eu_epg_1_eu_sadzba'] = $this->fillAndSet('slovenska_posta_eu_epg_1_eu_sadzba', '1:21.00,2:24.00,3:26.00,4:28.00,5:31.00,6:33.00,7:35.00,8:38.00,9:40.00,10:42.00,11:45.00,12:47.00,13:49.00,14:51.00,15:53.00,16:56.00,17:58.00,18:60.00,19:62.00,20:64.00,21:66.00,22:68.00,23:70.00,24:73.00,25:76.00,26:78.00,27:80.00,28:83.00,29:86.00,30:88.00');
        $data['slovenska_posta_eu_epg_2_eu_sadzba'] = $this->fillAndSet('slovenska_posta_eu_epg_2_eu_sadzba', '1:22.00,2:25.00,3:27.00,4:29.00,5:32.00,6:34.00,7:36.00,8:39.00,9:41.00,10:43.00,11:46.00,12:48.00,13:50.00,14:52.00,15:54.00,16:57.00,17:59.00,18:61.00,19:63.00,20:65.00,21:66.50,22:68.50,23:70.50,24:73.50,25:76.50,26:78.50,27:80.50,28:83.50,29:86.50,30:88.50');
        $data['slovenska_posta_eu_epg_2_sadzba'] = $this->fillAndSet('slovenska_posta_eu_epg_2_sadzba', '1:22.00,2:25.00,3:27.00,4:29.00,5:32.00,6:34.00,7:36.00,8:39.00,9:41.00,10:43.00,11:45.50,12:47.50,13:49.50,14:51.50,15:53.50,16:56.50,17:58.50,18:60.50,19:62.50,20:64.50,21:66.50,22:68.50,23:70.50,24:73.50,25:76.50,26:78.50,27:80.50,28:83.50,29:86.50,30:88.50');
        $data['slovenska_posta_eu_epg_box_vaha'] = $this->fillAndSet('slovenska_posta_eu_epg_box_vaha', '0.00');
        $data['slovenska_posta_eu_epg_status'] = $this->fill('slovenska_posta_eu_epg_status');


        $data['slovenska_posta_eu_ems_0_eu_sadzba'] = $this->fillAndSet('slovenska_posta_eu_ems_0_eu_sadzba', '1:15.00,2:16.00,3:17.00,4:18.00,5:19.00,6:20.00,7:21.00,8:22.00,9:23.00,10:24.00,11:25.00,12:26.00,13:27.00,14:28.00,15:30.00,16:31.00,17:32.00,18:33.00,19:34.00,20:35.00,21:37.00,22:38.00,23:39.00,24:40.00,25:41.00,26:42.00,27:43.00,28:45.00,29:46.00,30:47.00');
        $data['slovenska_posta_eu_ems_1_eu_sadzba'] = $this->fillAndSet('slovenska_posta_eu_ems_1_eu_sadzba', '1:24.00,2:27.00,3:30.00,4:32.00,5:35.00,6:37.00,7:40.00,8:42.00,9:45.00,10:48.00,11:51.00,12:53.00,13:55.00,14:58.00,15:60.00,16:62.00,17:65.00,18:67.00,19:69.00,20:71.00,21:73.00,22:76.00,23:79.00,24:82.00,25:84.00,26:87.00,27:90.00,28:92.00,29:95.00,30:98.00');
        $data['slovenska_posta_eu_ems_2_eu_sadzba'] = $this->fillAndSet('slovenska_posta_eu_ems_2_eu_sadzba', '1:34.00,2:36.50,3:38.50,4:41.50,5:45.50,6:48.50,7:50.50,8:53.50,9:56.50,10:59.50,11:62.50,12:65.50,13:68.50,14:71.50,15:74.50,16:77.50,17:80.50,18:82.50,19:85.50,20:88.50,21:91.50,22:94.50,23:97.50,24:100.50,25:103.50,26:106.50,27:109.50,28:111.50,29:114.50,30:117.50');
        $data['slovenska_posta_eu_ems_1_sadzba'] = $this->fillAndSet('slovenska_posta_eu_ems_1_sadzba', '1:21.00,2:24.00,3:26.00,4:28.00,5:31.00,6:33.00,7:35.00,8:38.00,9:40.00,10:42.00,11:45.00,12:47.00,13:49.00,14:51.00,15:53.00,16:56.00,17:58.00,18:60.00,19:62.00,20:64.00,21:66.00,22:68.00,23:70.00,24:73.00,25:76.00,26:78.00,27:80.00,28:83.00,29:86.00,30:88.00');
        $data['slovenska_posta_eu_ems_2_sadzba'] = $this->fillAndSet('slovenska_posta_eu_ems_2_sadzba', '1:30.00,2:33.00,3:36.00,4:39.00,5:43.00,6:46.00,7:49.00,8:52.00,9:55.00,10:58.00,11:61.00,12:63.00,13:66.00,14:69.00,15:72.00,16:75.00,17:78.00,18:81.00,19:84.00,20:87.00,21:89.00,22:92.00,23:95.00,24:98.00,25:101.00,26:104.00,27:107.00,28:110.00,29:113.00,30:115.00');
        $data['slovenska_posta_eu_ems_3_sadzba'] = $this->fillAndSet('slovenska_posta_eu_ems_3_sadzba', '1:36.00,2:43.00,3:51.00,4:59.00,5:67.00,6:84.00,7:97.00,8:110.00,9:122.00,10:135.00,11:148.00,12:165.00,13:173.00,14:184.00,15:197.00,16:208.00,17:218.00,18:229.00,19:238.00,20:247.00,21:256.00,22:264.00,23:272.00,24:280.00,25:287.00,26:295.00,27:302.00,28:309.00,29:316.00,30:324.00');
        $data['slovenska_posta_eu_ems_box_vaha'] = $this->fillAndSet('slovenska_posta_eu_ems_box_vaha', '0.00');
        $data['slovenska_posta_eu_ems_status'] = $this->fill('slovenska_posta_eu_ems_status');


        //-------
        $data['slovenska_posta_world_ems_3_sadzba'] = $this->fillAndSet('slovenska_posta_world_ems_3_sadzba', '1:36.00,2:43.00,3:51.00,4:59.00,5:67.00,6:84.00,7:97.00,8:110.00,9:122.00,10:135.00,11:148.00,12:165.00,13:173.00,14:184.00,15:197.00,16:208.00,17:218.00,18:229.00,19:238.00,20:247.00,21:256.00,22:264.00,23:272.00,24:280.00,25:287.00,26:295.00,27:302.00,28:309.00,29:316.00,30:324.00');
        $data['slovenska_posta_world_ems_4_sadzba'] = $this->fillAndSet('slovenska_posta_world_ems_4_sadzba', '1:42,2:57,3:72,4:86,5:98,6:114,7:126,8:137,9:149,10:161,11:173,12:184,13:196,14:207,15:219,16:231,17:243,18:254,19:266,20:278,21:290,22:301,23:313,24:325,25:336,26:348,27:360,28:371,29:383,30:395');
        $data['slovenska_posta_world_ems_5_sadzba'] = $this->fillAndSet('slovenska_posta_world_ems_5_sadzba', '1:48,2:75,3:96,4:116,5:136,6:168,7:189,8:208,9:229,10:251,11:281,12:302,13:322,14:343,15:363,16:383,17:404,18:424,19:444,20:465,21:485,22:504,23:523,24:543,25:562,26:582,27:601,28:620,29:640,30:659');
        $data['slovenska_posta_world_ems_box_vaha'] = $this->fillAndSet('slovenska_posta_world_ems_box_vaha', '0.00');
        $data['slovenska_posta_world_ems_status'] = $this->fill('slovenska_posta_world_ems_status');
        //--

        $data['slovenska_posta_sk_free_vaha'] = $this->fillAndSet('slovenska_posta_sk_free_vaha', '0.00');
        $data['slovenska_posta_cz_free_vaha'] = $this->fillAndSet('slovenska_posta_cz_free_vaha', '0.00');
        $data['slovenska_posta_eu_free_vaha'] = $this->fillAndSet('slovenska_posta_eu_free_vaha', '0.00');
        $data['slovenska_posta_world_free_vaha'] = $this->fillAndSet('slovenska_posta_world_free_vaha', '0.00');


        $data['createTextField'] = function ($entry, $tultip, $reference, $value, $id_form, $error = false, $required = '') {
            $form = "<div class='form-group";
            $form .= strlen($required) ? (' '.$required) : '';
            $form .= "'><label class='col-sm-2 control-label' for='$id_form'>";
            if ($tultip) {
                $form .= "<span data-toggle='tooltip' title='$tultip'>";
            }
            $form .= $entry;
            if ($tultip) {
                $form .= "</span>";
            }
            $form .= "</label>";

            $form .= "<div class='col-sm-10'><input type='text' name='$reference' value='$value' id='$id_form' class='form-control'/>";

            if ($error) {
                if (isset($this->error[$error])) {
                    $form .= "<div class='text-danger'>" . $this->error[$error] . "</div>";
                }
            }
            $form .= "</div></div>";
            echo $form;
        };



        $data['createTextareaField'] = function ($entry, $tultip, $reference, $value, $id_form, $error = false, $required = '') {
            $form = "<div class='form-group";
            $form .= strlen($required) ? (' '.$required) : '';
            $form .= "'><label class='col-sm-2 control-label' for='$id_form'>";
            if ($tultip) {
                $form .= "<span data-toggle='tooltip' title='$tultip'>";
            }
            $form .= $entry;
            if ($tultip) {
                $form .= "</span>";
            }
            $form .= "</label>";

            $form .= "<div class='col-sm-10'>" .
                "<textarea name='$reference' rows='2' id='$id_form' class='form-control'/>" . $value . "</textarea>";
            if ($error) {
                if (isset($this->error[$error])) {
                    $form .= "<div class='text-danger'>" . $this->error[$error] . "</div>";
                }
            }
            $form .= "</div></div>";
            echo $form;
        };



        $data['createRadioButton'] = function ($entry, $tultip, $reference, $value, $text_yes, $text_no) {
            $form = " <div class='form-group'><label class='col-sm-2 control-label'>";
            if ($tultip) {
                $form .= "<span data-toggle='tooltip' title='$tultip'>";
            }
            $form .= $entry;
            if ($tultip) {
                $form .= "</span>";
            }
            $form .= "</label>";

            $form .= "<div class='col-sm-10'><label class='radio-inline'>";
            if ($value) {
                $form .= "<input type='radio' name='$reference' value='1' checked='checked'/>" . $text_yes;
            } else {
                $form .= "<input type='radio' name='$reference' value='1'/>" . $text_yes;
            }
            $form .= "</label><label class='radio-inline'>";
            if (!$value) {
                $form .= "<input type='radio' name='$reference' value='0' checked='checked'/>" . $text_no;
            } else {
                $form .= "<input type='radio' name='$reference' value='0'/>" . $text_no;
            }
            $form .= "</label></div></div>";
            echo $form;
        };


        $data['createStatus'] = function ($entry, $reference, $name, $id_form, $text_enabled, $text_disabled) {
            $form = "<div class='form-group'><label class='col-sm-2 control-label' for='$id_form'>";
            $form .= $entry . "</label>";

            $form .= "<div class='col-sm-10'><select name='$name' id='$id_form' class='form-control'>";
            if ($reference) {
                $form .= "
            <option value='1' selected='selected'>" . $text_enabled . "</option>
            ";
                $form .= "
            <option value='0'>" . $text_disabled . "</option>
            ";
            } else {
                $form .= "
            <option value='1'>" . $text_enabled . "</option>
            ";
                $form .= "
            <option value='0' selected='selected'>" . $text_disabled . "</option>
            ";
            }
            $form .= "</select></div></div>";
            echo $form;
        };

        $data['delivery_classes'] = array(
            array('delivery_class_id'=>0, 'title'=>'Vypnuté' ),
            array('delivery_class_id'=>1, 'title'=>'Doručiť v deň podania' ),
            array('delivery_class_id'=>2, 'title'=>'Doručiť do 10:00' ),
            array('delivery_class_id'=>3, 'title'=>'Doručiť do 14:00' ),
            array('delivery_class_id'=>4, 'title'=>'Doručiť aj v sobotu' )
        );
        $data['delivery_classes_easy'] = array(
            array('delivery_class_id'=>0, 'title'=>'Vypnuté' ),
            array('delivery_class_id'=>1, 'title'=>'Doručiť do 10:00' ),
            array('delivery_class_id'=>2, 'title'=>'Doručiť aj v sobotu' )
        );



        if (isset($this->error['warning'])) {
            $data['error_warning'][] = $this->error['warning'];
        } else {
            $data['error_warning'] = array();
        }


        $custom_errors = array(
            'sk_free', 'sk_free_vaha', 'sk_free_dobierka', 'sk_list_1', 'sk_list_2', 'sk_list_vaha',
            'sk_dop_list_1', 'sk_dop_list_2', 'sk_dop_list_dobierka', 'sk_dop_list_dorucenka', 'sk_dop_list_do_ruk', 'sk_dop_list_vaha',
            'sk_ist_list_1', 'sk_ist_list_2', 'sk_ist_list_sadzba', 'sk_ist_list_dobierka', 'sk_ist_list_dorucenka', 'sk_ist_list_do_ruk', 'sk_ist_list_vaha',
            'sk_balik_posta', 'sk_balik_adresa', 'sk_balik_ist', 'sk_balik_dobierka', 'sk_balik_krehke', 'sk_balik_neskladne', 'sk_balik_vaha',
            'sk_kurier_posta', 'sk_kurier_adresa', 'sk_kurier_podaj', 'sk_kurier_ist', 'sk_kurier_dobierka', 'sk_kurier_krehke', 'sk_kurier_neskladne',
            'sk_kurier_info', 'sk_kurier_do_ruk', 'sk_kurier_den_podania', 'sk_kurier_10', 'sk_kurier_14', 'sk_kurier_sobota', 'sk_kurier_vaha',
            'cz_free', 'cz_free_vaha', 'cz_free_dobierka', 'cz_list_1', 'cz_list_vaha',
            'cz_dop_list', 'cz_dop_list_dobierka', 'cz_dop_list_dorucenka', 'cz_dop_list_do_ruk', 'cz_dop_list_vaha',
            'cz_ist_list_1_100', 'cz_ist_list_1_500', 'cz_ist_list_1_1000', 'cz_ist_list_dobierka', 'cz_ist_list_dorucenka', 'cz_ist_list_do_ruk', 'cz_ist_list_vaha',
            'cz_balik_1', 'cz_balik_ist', 'cz_balik_dobierka', 'cz_balik_krehke', 'cz_balik_neskladne', 'cz_balik_vaha',
            'cz_epg', 'cz_epg_vaha', 'cz_ems', 'cz_ems_vaha',
            'eu_free', 'eu_free_vaha', 'eu_free_dobierka', 'eu_list_1', 'eu_list_vaha',
            'eu_dop_list', 'eu_dop_list_dobierka', 'eu_dop_list_dorucenka', 'eu_dop_list_do_ruk', 'eu_dop_list_vaha',
            'eu_ist_list_1_100','eu_ist_list_1_500', 'eu_ist_list_1_1000', 'eu_ist_list_dobierka', 'eu_ist_list_dorucenka', 'eu_ist_list_do_ruk', 'eu_ist_list_vaha',
            'eu_balik_1', 'eu_balik_ist', 'eu_balik_dobierka', 'eu_balik_krehke', 'eu_balik_neskladne', 'eu_balik_vaha',
            'eu_epg_1_eu', 'eu_epg_2_eu', 'eu_epg_2',
            'eu_ems_0_eu', 'eu_ems_1_eu', 'eu_ems_2_eu', 'eu_ems_1', 'eu_ems_2', 'eu_ems_3',
            'world_free', 'world_free_vaha', 'world_free_dobierka', 'world_list_1', 'world_list_vaha',
            'world_dop_list', 'world_dop_list_dobierka', 'world_dop_list_dorucenka', 'world_dop_list_do_ruk', 'world_dop_list_vaha',
            'world_ist_list_1_100', 'world_ist_list_1_500', 'world_ist_list_1_1000', 'world_ist_list_dobierka', 'world_ist_list_dorucenka', 'world_ist_list_do_ruk', 'world_ist_list_vaha',
            'world_balik_1', 'world_balik_ist', 'world_balik_dobierka', 'world_balik_krehke', 'world_balik_neskladne', 'world_balik_vaha',
            'world_ems_3', 'world_ems_4', 'world_ems_5', 'world_ems_vaha'
        );

        foreach ( $custom_errors as $custom_error) {
            if (isset($this->error[$custom_error])) {
                $data['error_'.$custom_error] = $this->error[$custom_error];
                $data['error_warning'][] = $this->error[$custom_error];
            } else {
                $data['error_'.$custom_error] = '';
            }

        }


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/slovenska_posta.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/slovenska_posta')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        $pattern = '/^(\d+([.]\d+)?)(:)(\d+([.]\d+)?)(([,])(\d+([.]\d+)?)(:)(\d+([.]\d+)?))*$/';
        if($this->request->post['slovenska_posta_sk_status']){
            if ( $this->request->post['slovenska_posta_sk_free_status'] ) {
                if (!is_numeric($this->request->post['slovenska_posta_sk_free_cena'])){
                    $this->error['sk_free'] = $this->language->get('error_intOrDouble');
                }
                if (!is_numeric($this->request->post['slovenska_posta_sk_free_vaha'])){
                    $this->error['sk_free_vaha'] = $this->language->get('error_intOrDouble');
                }
                if (!is_numeric($this->request->post['slovenska_posta_sk_free_dobierka_cena']) ) {
                    $this->error['sk_free_dobierka'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_sk_list_status']){
                if ( ($this->request->post['slovenska_posta_sk_list_1_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_list_1_sadzba'])) ) {
                    $this->error['sk_list_1'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_list_2_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_list_2_sadzba'])) ) {
                    $this->error['sk_list_2'] = $this->language->get('error_rate_format');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_sk_list_box_vaha']) ) {
                    $this->error['sk_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_sk_dop_list_status']){
                if ( ($this->request->post['slovenska_posta_sk_dop_list_1_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_dop_list_1_sadzba'])) ) {
                    $this->error['sk_dop_list_1'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_dop_list_2_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_dop_list_2_sadzba'])) ) {
                    $this->error['sk_dop_list_2'] = $this->language->get('error_rate_format');
                }

                if ( $this->request->post['slovenska_posta_sk_dop_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_dop_list_dorucenka_cena'])) ) {
                    $this->error['sk_dop_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_dop_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_dop_list_dobierka_cena'])) ) {
                    $this->error['sk_dop_list_dobierka'] = $this->language->get('error_intOrDouble');
                }

                if ( $this->request->post['slovenska_posta_sk_dop_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_dop_list_do_ruk_cena'])) ) {
                    $this->error['sk_dop_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_sk_dop_list_box_vaha']) ) {
                    $this->error['sk_dop_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_sk_poistenie_list_status']){
                if ( ($this->request->post['slovenska_posta_sk_poistenie_list_1_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_poistenie_list_1_sadzba'])) ) {
                    $this->error['sk_ist_list_1'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_poistenie_list_2_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_poistenie_list_2_sadzba'])) ) {
                    $this->error['sk_ist_list_2'] = $this->language->get('error_rate_format');
                }
                if ( utf8_strlen($this->request->post['slovenska_posta_sk_poistenie_list_poistenie_sadzba']) > 0 && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_poistenie_list_poistenie_sadzba'])) ) {
                    $this->error['sk_ist_list_sadzba'] = $this->language->get('error_insurance_format');
                }

                if ( $this->request->post['slovenska_posta_sk_poistenie_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_poistenie_list_dobierka_cena'])) ) {
                    $this->error['sk_ist_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_poistenie_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_poistenie_list_dorucenka_cena'])) ) {
                    $this->error['sk_ist_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_poistenie_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_poistenie_list_do_ruk_cena'])) ) {
                    $this->error['sk_ist_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_sk_poistenie_list_box_vaha']) ) {
                    $this->error['sk_ist_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_sk_balik_status']){
                if ( ($this->request->post['slovenska_posta_sk_balik_posta_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_balik_posta_sadzba'])) ) {
                    $this->error['sk_balik_posta'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_balik_adresa_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_balik_adresa_sadzba'])) ) {
                    $this->error['sk_balik_adresa'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_balik_poistenie_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_balik_poistenie_sadzba'])) ) {
                    $this->error['sk_balik_ist'] = $this->language->get('error_insurance_format');
                }

                if ( $this->request->post['slovenska_posta_sk_balik_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_balik_dobierka_cena'])) ) {
                    $this->error['sk_balik_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_balik_krehke_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_balik_krehke_cena'])) ) {
                    $this->error['sk_balik_krehke'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_balik_neskladne_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_balik_neskladne_cena'])) ) {
                    $this->error['sk_balik_neskladne'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_sk_balik_box_vaha']) ) {
                    $this->error['sk_balik_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_sk_easy_status']){
                if ( ($this->request->post['slovenska_posta_sk_easy_plast_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_easy_plast_sadzba'])) ) {
                    $this->error['sk_easy_plast'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_easy_karton_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_easy_karton_sadzba'])) ) {
                    $this->error['sk_easy_karton'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_easy_poistenie_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_easy_poistenie_sadzba'])) ) {
                    $this->error['sk_easy_ist'] = $this->language->get('error_insurance_format');
                }
                if ( $this->request->post['slovenska_posta_sk_easy_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_easy_dobierka_cena'])) ) {
                    $this->error['sk_easy_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_easy_doruk_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_easy_doruk_cena'])) ) {
                    $this->error['sk_easy_do_ruk'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_easy_dorucenie_info_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_easy_dorucenie_info_cena'])) ) {
                    $this->error['sk_easy_info'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_easy_delivery_time_id'] && (!is_numeric($this->request->post['slovenska_posta_sk_easy_dodesiatej_cena'])) ) {
                    $this->error['sk_easy_10'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_easy_delivery_time_id'] && (!is_numeric($this->request->post['slovenska_posta_sk_easy_sobota_cena'])) ) {
                    $this->error['sk_easy_sobota'] = $this->language->get('error_intOrDouble');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_sk_easy_box_vaha']) ) {
                    $this->error['sk_easy_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_sk_kurier_status']){
                if ( ($this->request->post['slovenska_posta_sk_kurier_posta_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_kurier_posta_sadzba'])) ) {
                    $this->error['sk_kurier_posta'] = $this->language->get('error_rate_format');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_adresa_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_adresa_cena'])) ) {
                    $this->error['sk_kurier_adresa'] = $this->language->get('error_intOrDouble');
                }
                if ( ($this->request->post['slovenska_posta_sk_kurier_podaj_kurier_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_kurier_podaj_kurier_sadzba'])) ) {
                    $this->error['sk_kurier_podaj'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_sk_kurier_poistenie_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_sk_kurier_poistenie_sadzba'])) ) {
                    $this->error['sk_kurier_ist'] = $this->language->get('error_insurance_format');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_dobierka_cena'])) ) {
                    $this->error['sk_kurier_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_krehke_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_krehke_cena'])) ) {
                    $this->error['sk_kurier_krehke'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_neskladne_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_neskladne_cena'])) ) {
                    $this->error['sk_kurier_neskladne'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_dorucenie_info_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_dorucenie_info_cena'])) ) {
                    $this->error['sk_kurier_info'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_doruk_status'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_doruk_cena'])) ) {
                    $this->error['sk_kurier_do_ruk'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_delivery_time_id'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_denpodania_cena'])) ) {
                    $this->error['sk_kurier_den_podania'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_delivery_time_id'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_do10_cena'])) ) {
                    $this->error['sk_kurier_10'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_delivery_time_id'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_do14_cena'])) ) {
                    $this->error['sk_kurier_14'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_sk_kurier_delivery_time_id'] && (!is_numeric($this->request->post['slovenska_posta_sk_kurier_sobota_cena'])) ) {
                    $this->error['sk_kurier_sobota'] = $this->language->get('error_intOrDouble');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_sk_kurier_box_vaha']) ) {
                    $this->error['sk_kurier_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
        }

        if($this->request->post['slovenska_posta_cz_status']){
            if ( $this->request->post['slovenska_posta_cz_free_status'] ) {
                if(!is_numeric($this->request->post['slovenska_posta_cz_free_cena'])){
                    $this->error['cz_free'] = $this->language->get('error_intOrDouble');
                }
                if (!is_numeric($this->request->post['slovenska_posta_cz_free_vaha'])){
                    $this->error['cz_free_vaha'] = $this->language->get('error_intOrDouble');
                }
                if(!is_numeric($this->request->post['slovenska_posta_cz_free_dobierka_cena'])) {
                    $this->error['cz_free_dobierka'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_cz_list_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_list_1_sadzba'])) ) {
                    $this->error['cz_list_1'] = $this->language->get('error_rate_format');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_cz_list_box_vaha']) ) {
                    $this->error['cz_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_cz_dop_list_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_dop_list_sadzba'])) ) {
                    $this->error['cz_dop_list'] = $this->language->get('error_rate_format');
                }
                if ( $this->request->post['slovenska_posta_cz_dop_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_dop_list_dobierka_cena'])) ) {
                    $this->error['cz_dop_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_cz_dop_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_dop_list_dorucenka_cena'])) ) {
                    $this->error['cz_dop_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }

                if ( $this->request->post['slovenska_posta_cz_dop_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_dop_list_do_ruk_cena'])) ) {
                    $this->error['cz_dop_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_cz_dop_list_box_vaha']) ) {
                    $this->error['cz_dop_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_cz_poistenie_list_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_poistenie_list_1_100_sadzba'])) ) {
                    $this->error['cz_ist_list_1_100'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_poistenie_list_1_500_sadzba'])) ) {
                    $this->error['cz_ist_list_1_500'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_poistenie_list_1_1000_sadzba'])) ) {
                    $this->error['cz_ist_list_1_1000'] = $this->language->get('error_rate_format');
                }
                if ( $this->request->post['slovenska_posta_cz_poistenie_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_poistenie_list_dobierka_cena'])) ) {
                    $this->error['cz_ist_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_cz_poistenie_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_poistenie_list_dorucenka_cena'])) ) {
                    $this->error['cz_ist_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_cz_poistenie_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_poistenie_list_do_ruk_cena'])) ) {
                    $this->error['cz_ist_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_cz_poistenie_list_box_vaha']) ) {
                    $this->error['cz_ist_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_cz_balik_status']){
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_cz_balik_1_sadzba'])) ) {
                    $this->error['cz_balik_1'] = $this->language->get('error_rate_format');
                }

                if ( ($this->request->post['slovenska_posta_cz_balik_poistenie_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_cz_balik_poistenie_sadzba'])) ) {
                    $this->error['cz_balik_ist'] = $this->language->get('error_insurance_format');
                }

                if ( $this->request->post['slovenska_posta_cz_balik_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_balik_dobierka_cena'])) ) {
                    $this->error['cz_balik_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_cz_balik_krehke_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_balik_krehke_cena'])) ) {
                    $this->error['cz_balik_krehke'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_cz_balik_neskladne_status'] && (!is_numeric($this->request->post['slovenska_posta_cz_balik_neskladne_cena'])) ) {
                    $this->error['cz_balik_neskladne'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_cz_balik_box_vaha']) ) {
                    $this->error['cz_balik_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_cz_epg_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_epg_sadzba'])) ) {
                    $this->error['cz_epg'] = $this->language->get('error_rate_format');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_cz_epg_box_vaha']) ) {
                    $this->error['cz_epg_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_cz_ems_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_cz_ems_sadzba'])) ) {
                    $this->error['cz_ems'] = $this->language->get('error_rate_format');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_cz_ems_box_vaha']) ) {
                    $this->error['cz_ems_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
        }

        if($this->request->post['slovenska_posta_eu_status']){
            if ( $this->request->post['slovenska_posta_eu_free_status']) {
                if(!is_numeric($this->request->post['slovenska_posta_eu_free_cena'])){
                    $this->error['eu_free'] = $this->language->get('error_intOrDouble');
                }
                if (!is_numeric($this->request->post['slovenska_posta_eu_free_vaha'])){
                    $this->error['eu_free_vaha'] = $this->language->get('error_intOrDouble');
                }
                if(!is_numeric($this->request->post['slovenska_posta_eu_free_dobierka_cena'])) {
                    $this->error['eu_free_dobierka'] = $this->language->get('error_intOrDouble');
                }
            }

            if($this->request->post['slovenska_posta_eu_list_status']){
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_eu_list_1_sadzba'])) ) {
                    $this->error['eu_list_1'] = $this->language->get('error_rate_format');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_eu_list_box_vaha']) ) {
                    $this->error['eu_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_eu_dop_list_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_dop_list_sadzba'])) ) {
                    $this->error['eu_dop_list'] = $this->language->get('error_rate_format');
                }
                if ( $this->request->post['slovenska_posta_eu_dop_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_dop_list_dobierka_cena'])) ) {
                    $this->error['eu_dop_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_eu_dop_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_dop_list_dorucenka_cena'])) ) {
                    $this->error['eu_dop_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }

                if ( $this->request->post['slovenska_posta_eu_dop_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_dop_list_do_ruk_cena'])) ) {
                    $this->error['eu_dop_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_eu_dop_list_box_vaha']) ) {
                    $this->error['eu_dop_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_eu_poistenie_list_status']){
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_eu_poistenie_list_1_100_sadzba'])) ) {
                    $this->error['eu_ist_list_1_100'] = $this->language->get('error_rate_format');
                }
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_eu_poistenie_list_1_500_sadzba'])) ) {
                    $this->error['eu_ist_list_1_500'] = $this->language->get('error_rate_format');
                }
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_eu_poistenie_list_1_1000_sadzba'])) ) {
                    $this->error['eu_ist_list_1_1000'] = $this->language->get('error_rate_format');
                }

                if ( $this->request->post['slovenska_posta_eu_poistenie_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_poistenie_list_dobierka_cena'])) ) {
                    $this->error['eu_ist_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_eu_poistenie_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_poistenie_list_dorucenka_cena'])) ) {
                    $this->error['eu_ist_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_eu_poistenie_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_poistenie_list_do_ruk_cena'])) ) {
                    $this->error['eu_ist_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_eu_poistenie_list_box_vaha']) ) {
                    $this->error['eu_ist_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_eu_balik_status']){
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_eu_balik_1_sadzba'])) ) {
                    $this->error['eu_balik_1'] = $this->language->get('error_rate_format');
                }

                if ( ($this->request->post['slovenska_posta_eu_balik_poistenie_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_eu_balik_poistenie_sadzba'])) ) {
                    $this->error['eu_balik_ist'] = $this->language->get('error_insurance_format');
                }

                if ( $this->request->post['slovenska_posta_eu_balik_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_balik_dobierka_cena'])) ) {
                    $this->error['eu_balik_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_eu_balik_krehke_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_balik_krehke_cena'])) ) {
                    $this->error['eu_balik_krehke'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_eu_balik_neskladne_status'] && (!is_numeric($this->request->post['slovenska_posta_eu_balik_neskladne_cena'])) ) {
                    $this->error['eu_balik_neskladne'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_eu_balik_box_vaha']) ) {
                    $this->error['eu_balik_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_eu_epg_status']){

                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_epg_1_eu_sadzba'])) ) {
                    $this->error['eu_epg_1_eu'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_epg_2_eu_sadzba'])) ) {
                    $this->error['eu_epg_2_eu'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_epg_2_sadzba'])) ) {
                    $this->error['eu_epg_2'] = $this->language->get('error_rate_format');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_eu_epg_box_vaha']) ) {
                    $this->error['eu_epg_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_eu_ems_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_ems_0_eu_sadzba'])) ) {
                    $this->error['eu_ems_0_eu'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_ems_1_eu_sadzba'])) ) {
                    $this->error['eu_ems_1_eu'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_ems_2_eu_sadzba'])) ) {
                    $this->error['eu_ems_2_eu'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_ems_1_sadzba'])) ) {
                    $this->error['eu_ems_1'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_ems_2_sadzba'])) ) {
                    $this->error['eu_ems_2'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_eu_ems_3_sadzba'])) ) {
                    $this->error['eu_ems_3'] = $this->language->get('error_rate_format');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_eu_ems_box_vaha']) ) {
                    $this->error['eu_ems_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
        }

        if($this->request->post['slovenska_posta_world_status']){
            if ( $this->request->post['slovenska_posta_world_free_status'] ) {
                if(!is_numeric($this->request->post['slovenska_posta_world_free_cena'])){
                    $this->error['world_free'] = $this->language->get('error_intOrDouble');
                }
                if (!is_numeric($this->request->post['slovenska_posta_world_free_vaha'])){
                    $this->error['world_free_vaha'] = $this->language->get('error_intOrDouble');
                }
                if (!is_numeric($this->request->post['slovenska_posta_world_free_dobierka_cena'])) {
                    $this->error['world_free_dobierka'] = $this->language->get('error_intOrDouble');
                }
            }

            if($this->request->post['slovenska_posta_world_list_status']){
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_world_list_1_sadzba'])) ) {
                    $this->error['world_list_1'] = $this->language->get('error_rate_format');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_world_list_box_vaha']) ) {
                    $this->error['world_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_world_dop_list_status']){
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_world_dop_list_sadzba'])) ) {
                    $this->error['world_dop_list'] = $this->language->get('error_rate_format');
                }
                if ( $this->request->post['slovenska_posta_world_dop_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_world_dop_list_dobierka_cena'])) ) {
                    $this->error['world_dop_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_world_dop_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_world_dop_list_dorucenka_cena'])) ) {
                    $this->error['world_dop_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }

                if ( $this->request->post['slovenska_posta_world_dop_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_world_dop_list_do_ruk_cena'])) ) {
                    $this->error['world_dop_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_world_dop_list_box_vaha']) ) {
                    $this->error['world_dop_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_world_poistenie_list_status']){
                if (  (!preg_match( $pattern , $this->request->post['slovenska_posta_world_poistenie_list_1_100_sadzba'])) ) {
                    $this->error['world_ist_list_1_100'] = $this->language->get('error_rate_format');
                }
                if (  (!preg_match( $pattern , $this->request->post['slovenska_posta_world_poistenie_list_1_500_sadzba'])) ) {
                    $this->error['world_ist_list_1_500'] = $this->language->get('error_rate_format');
                }
                if (  (!preg_match( $pattern , $this->request->post['slovenska_posta_world_poistenie_list_1_1000_sadzba'])) ) {
                    $this->error['world_ist_list_1_1000'] = $this->language->get('error_rate_format');
                }
                if ( $this->request->post['slovenska_posta_world_poistenie_list_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_world_poistenie_list_dobierka_cena'])) ) {
                    $this->error['world_ist_list_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_world_poistenie_list_dorucenka_status'] && (!is_numeric($this->request->post['slovenska_posta_world_poistenie_list_dorucenka_cena'])) ) {
                    $this->error['world_ist_list_dorucenka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_world_poistenie_list_do_ruk_status'] && (!is_numeric($this->request->post['slovenska_posta_world_poistenie_list_do_ruk_cena'])) ) {
                    $this->error['world_ist_list_do_ruk'] = $this->language->get('error_intOrDouble');
                }
                if ( !is_numeric($this->request->post['slovenska_posta_world_poistenie_list_box_vaha']) ) {
                    $this->error['world_ist_list_vaha'] = $this->language->get('error_intOrDouble');
                }
            }
            if($this->request->post['slovenska_posta_world_balik_status']){
                if ( (!preg_match( $pattern , $this->request->post['slovenska_posta_world_balik_1_sadzba'])) ) {
                    $this->error['world_balik_1'] = $this->language->get('error_rate_format');
                }
                if ( ($this->request->post['slovenska_posta_world_balik_poistenie_status']) && (!preg_match( $pattern , $this->request->post['slovenska_posta_world_balik_poistenie_sadzba'])) ) {
                    $this->error['world_balik_ist'] = $this->language->get('error_insurance_format');
                }

                if ( $this->request->post['slovenska_posta_world_balik_dobierka_status'] && (!is_numeric($this->request->post['slovenska_posta_world_balik_dobierka_cena'])) ) {
                    $this->error['world_balik_dobierka'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_world_balik_krehke_status'] && (!is_numeric($this->request->post['slovenska_posta_world_balik_krehke_cena'])) ) {
                    $this->error['world_balik_krehke'] = $this->language->get('error_intOrDouble');
                }
                if ( $this->request->post['slovenska_posta_world_balik_neskladne_status'] && (!is_numeric($this->request->post['slovenska_posta_world_balik_neskladne_cena'])) ) {
                    $this->error['world_balik_neskladne'] = $this->language->get('error_intOrDouble');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_world_balik_box_vaha']) ) {
                    $this->error['world_balik_vaha'] = $this->language->get('error_intOrDouble');
                }
            }

            if($this->request->post['slovenska_posta_world_ems_status']){

                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_world_ems_3_sadzba'])) ) {
                    $this->error['world_ems_3'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_world_ems_4_sadzba'])) ) {
                    $this->error['world_ems_4'] = $this->language->get('error_rate_format');
                }
                if ((!preg_match( $pattern , $this->request->post['slovenska_posta_world_ems_5_sadzba'])) ) {
                    $this->error['world_ems_5'] = $this->language->get('error_rate_format');
                }

                if ( !is_numeric($this->request->post['slovenska_posta_world_ems_box_vaha']) ) {
                    $this->error['world_ems_vaha'] = $this->language->get('error_intOrDouble');
                }
            }

        }

        return !$this->error;
    }
    //------------------------------------------------------------------------------------------------------------------


    /*******************************************************************************************************************
     * Notifikácia
     ******************************************************************************************************************/
    public function getNotifications() {
        sleep(1);
        $this->load->language('extension/shipping/slovenska_posta');
        $response = $this->loadNotifications();
        $json = array();
        if ($response===false) {
            $json['message'] = '';
            $json['error'] = $this->language->get( 'error_notifications' );
        } else {
            $json['message'] = $response;
            $json['error'] = '';
        }

        $this->response->setOutput(json_encode($json));
    }
    //------------------------------------------------------------------------------------------------------------------


    /*******************************************************************************************************************
     * @return string
     * Notifikácia nová verzia - dôležité bezpečnostné upozornenia
     ******************************************************************************************************************/
    public function loadNotifications() {
        $result = $this->curl_get_contents("http://www.openquiz.eu/index.php?route=message/information&tool=slovenska_posta&version=4&server=".HTTP_SERVER."&ocver=".VERSION);

        if (stripos($result,'<html') !== false) {
            return '';
        }
        return $result;
    }
    //------------------------------------------------------------------------------------------------------------------

    /*******************************************************************************************************************
     * @return string
     * Notifikácia nová verzia - dôležité bezpečnostné upozornenia
     ******************************************************************************************************************/
    protected function curl_get_contents($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
    //------------------------------------------------------------------------------------------------------------------
}