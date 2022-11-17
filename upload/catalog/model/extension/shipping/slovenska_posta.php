<?php

class ModelExtensionShippingSlovenskaPosta extends Model
{
    private function getRate($rates, $weight, $cost=0){
        $rates = explode(',', $this->config->get($rates));
        foreach ($rates as $rate) {
            $data = explode(':', $rate);
            if ($data[0] >= $weight) {
                if (isset($data[1])) {
                    $cost += $data[1];
                }
                break;
            }
        }
        return $cost;
    }


    private function getAdditionalWeight($base_weight, $additional_weight_parameter){
        if ($this->config->get($additional_weight_parameter)) {
            return $base_weight + $this->config->get($additional_weight_parameter);
        }
        return $base_weight;
    }


    private function addServices($base_cost, array $insurances){
        $cost = $base_cost;
        foreach($insurances as $insurance){
            if ($this->config->get( 'slovenska_posta_' . $insurance.'_status' )) {
                $cost += $this->config->get( 'slovenska_posta_' . $insurance . '_cena' );
            }
        }
        return $cost;
    }


    private function addServicesIfPosible($base_cost, $country_parameters, array $insurances){
        $ins = array();
        if( array_key_exists( 'D', $insurances ) ){

            if( in_array('D', $country_parameters) ) {
                if ($this->config->get( 'slovenska_posta_' . $insurances['D'].'_status' )){
                    $ins[] = $insurances['D'];
                }
            }
            if( in_array('VR', $country_parameters) ) {
                if ($this->config->get( 'slovenska_posta_' . $insurances['VR'].'_status' )){
                    $ins[] = $insurances['VR'];
                }
            }
            if( in_array('VR-D', $country_parameters) ) {
                if( array_key_exists('D', $insurances) ) {
                    if ($this->config->get( 'slovenska_posta_' . $insurances['D'].'_status' )){
                        $ins[] = $insurances['VR'];
                    }
                }
            }



            //ak je nainstalovany modul total SP, ale je vypnuty, moze ratat dobierku do ceny prepravy
            if( !$this->config->get('slovenska_posta_cod_fee_status') ){
                if( in_array('DOB', $country_parameters)  ) {
                    if ($this->config->get( 'slovenska_posta_' . $insurances['DOB'].'_status' )){
                        $ins[] = $insurances['DOB'];
                    }
                }
            }

        }

        if ( array_key_exists( 'F' , $insurances)){
            if ( $this->config->get( 'slovenska_posta_' . $insurances['F'].'_status')  &&
                $this->config->get( 'slovenska_posta_' . $insurances['ENC'].'_status' ) ){

                if( in_array('F', $country_parameters)  ) {
                    $ins[] = $insurances['F'];
                }elseif( in_array('ENC', $country_parameters) ) {
                    $ins[] = $insurances['ENC'];
                }
            }
            elseif ( $this->config->get( 'slovenska_posta_' . $insurances['ENC'].'_status' ) ){
                if( in_array('ENC', $country_parameters)  ) {
                    $ins[] = $insurances['ENC'];
                }
            }
            elseif ( $this->config->get( 'slovenska_posta_' . $insurances['F'].'_status' ) ){
                if( in_array('F', $country_parameters)  ) {
                    $ins[] = $insurances['F'];
                }
            }

            //ak je nainstalovany modul total SP, ale je vypnuty, moze ratat dobierku do ceny prepravy
            if(!$this->config->get('slovenska_posta_cod_fee_status') ){
                if( in_array('DOB', $country_parameters)  ) {
                    if ($this->config->get( 'slovenska_posta_' . $insurances['DOB'].'_status' )){
                        $ins[] = $insurances['DOB'];
                    }
                }
            }
        }

        $cost = $this->addServices( $base_cost, array_unique($ins) );

        return $cost;
    }


    private function createLabel($code, $title, $cost, $sp_cod_status = false, $sp_cod_fee = 0 ){
        return array(
            'code'          => 'slovenska_posta.' . $code,
            'title'         => $title,
            'cost'          => $cost,
            'tax_class_id'  => $this->config->get('slovenska_posta_tax_class_id'),
            'text'          => $this->currency->format($this->tax->calculate($cost, $this->config->get('slovenska_posta_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
            'sp_cod_status' => $sp_cod_status,
            'sp_cod_fee'    => $sp_cod_fee

        );
    }


    private function getInsurancePrice($duty, $limit = false){
        $cost = 0;
        $tarifa = 0;
        $data = array();

        if ( is_int( $limit ) && $limit == 0 ){
            return array( -1, $cost );
        }

        $rates = explode(',', $this->config->get('slovenska_posta_' . $duty . '_poistenie_sadzba'));

        foreach ($rates as $rate) {
            $data = explode(':', $rate);
            $max = false;

            if( $limit && ( $data[0] >= $limit ) ){
                $data[0] = $limit;
                $max = true;

            }
            if ( $data[0] >= $this->cart->getTotal() ) {
                if (isset($data[1])) {
                    $cost = $data[1];
                    $tarifa = $data[0];
                    if($limit && ($tarifa > $limit) ) $tarifa = $limit;
                }

                break;
            }
            if($max) break;
        }
        if($tarifa>0){
            return array ( $tarifa, $cost );
        }
        elseif($this->config->get('slovenska_posta_allow_max_insurance')){
            return $data;
        }
        elseif($this->config->get('slovenska_posta_disable_insurance')){
            return array( -1, $cost );
        }
        else{
            return array( $tarifa, $cost );
        }

    }


    private function getTitle($text, $weight, array $insurance = null){

        $title = '';
        $sequel = false;
        if ($this->config->get('slovenska_posta_display_weight')) {
            $title .=  $this->language->get('text_weight') . ' ' .
                $this->weight->format($weight, $this->config->get('slovenska_posta_weight_class_id') ).
                ', ';
            $sequel = true;
        }
        if ($this->config->get('slovenska_posta_display_insurance') && $insurance) {
            if($insurance[0]!=0){
                $title .=  $this->language->get('text_insurance') .
                    $this->currency->format($insurance[0], $this->session->data['currency']) . '/' .
                    $this->currency->format($insurance[1], $this->session->data['currency']) .
                    ', ';
            }else{
                $title .=   $this->language->get('text_out') .
                    ', ';
            }
            $sequel = true;
        }

        $title = ( rtrim($title, ', ') );
        if ( $sequel ) $title = '(' . $title . ')';

        return $text .' '. $title;
    }


    function getQuote($address)
    {

        $eu_country = array(
//Albánsko
            'AL' => array( 'list_service' => array('D', 'VR-D'),
                           'box_service' => array('F', 'ENC', 'PR') ),


//Andora
            'AD' => array( 'list_service' => array(),
                           'box_service' => array('P'=>array(813, 100)) ),


//Arménsko
            'AM' => array( 'list_service' => array('VR'),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('3', 30) ),
//Azerbajdžan
            'AZ' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('3', 20)  ),
//Belgicko
            'BE' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Bielorusko
            'BY' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('2', 20) ),
//Bosna a Hercegovina
            'BA' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 0)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)) ),
//Bulharsko
            'BG' => array( 'list_service' => array(),
                           'box_service' => array('F', 'ENC', 'P'=>array(952, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Cyprus
            'CY' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Čierna Hora
            'ME' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(24, 0)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)) ),
//Dánsko
            'DK' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('PR'),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Estónsko
            'EE' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),

// Faerské ostrovy presunute do svet

//Fínsko
            'FI' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Francúzsko
            'FR' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Gibraltár
            'GI' => array( 'list_service' => array('D', 'VR-D'),
                           'box_service' => array('F', 'ENC', 'PR') ),
//Grécko
            'GR' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Gruzínsko
            'GE' => array( 'list_service' => array('D', 'VR-D'),
                           'box_service' => array('F', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('3', 30) ),
//Holandsko
            'NL' => array( 'list_service' => array('P'=>array(547, 100)),
                           'box_service' => array('F', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Chorvátsko
            'HR' => array( 'list_service' => array('D', 'VR', 'DOB', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'DOB', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Írsko
            'IE' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('ENC', 'PR', 'P'=>array(103, 94)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Island
            'IS' => array( 'list_service' => array('D'),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2', 30),
                           'ems' =>array('2', 20) ),
//Jersey žeby ísrko?
            'JE' => array( 'list_service' => array('D'),
                           'box_service' => array() ),
//Lichtenštajnsko
            'LI' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('ENC', 'P'=>array(1000, 100)) ),
//Litva
            'LT' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Lotyšsko
            'LV' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Luxembursko
            'LU' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR'),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Macedónsko
            'MK' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'P'=>array(1000, 100)),
                           'ems' =>array('2', 30) ),
//Maďarsko
            'HU' => array( 'list_service' => array('D', 'VR-D', 'DOB', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'DOB', 'P'=>array(1000, 100)),
                           'epg' =>array('1_eu', 30),
                           'ems' =>array('1_eu', 30) ),
//Malta
            'MT' => array( 'list_service' => array('D'),
                           'box_service' => array('F', 'PR'),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Moldavsko
            'MD' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 0)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('2', 30) ),
//Monako
            'MC' => array( 'list_service' => array(),
                           'box_service' => array('P'=>array(1000, 100)) ),
//Nemecko
            'DE' => array( 'list_service' => array('D'),
                           'box_service' => array('ENC', 'P'=>array(498, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Nórsko
            'NO' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array(),
                           'epg' =>array('2', 30),
                           'ems' =>array('2', 30) ),
//Poľsko
            'PL' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('1_eu', 30),
                           'ems' =>array('0_eu', 20) ),
//Portugalsko
            'PT' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Rakúsko
            'AT' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('1_eu', 30),
                           'ems' =>array('1_eu', 30) ),
//Rumunsko
            'RO' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Ruská federácia
            'RU' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('2', 30) ),
//San Marino
            'SM' => array( 'list_service' => array(),
                           'box_service' => array('F', 'ENC', 'P'=>array(1000, 100)) ),
//Slovinsko
            'SI' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Srbsko
            'RS' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 0)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('2', 15) ),
//Španielsko
            'ES' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
            //Chafarinas I. ??
//Švajčiarsko
            'CH' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 100)),
                           'box_service' => array('ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2', 30),
                           'ems' =>array('2', 30) ),
//Švédsko
            'SE' => array( 'list_service' => array('D', 'P'=>array(1000, 100)),
                           'box_service' => array('P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Taliansko
            'IT' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 0)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),
//Turecko
            'TR' => array( 'list_service' => array('D', 'VR', 'P'=>array(813, 100)),
                           'box_service' => array('PR', 'P'=>array(813, 100)),
                           'ems' =>array('2', 30) ),
//Ukrajina
            'UA' => array( 'list_service' => array('D', 'VR-D', 'P'=>array(1000, 100)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)),
                           'ems' =>array('1', 20) ),
//Vatikán
            'VA' => array( 'list_service' => array('D', 'VR', 'P'=>array(1000, 0)),
                           'box_service' => array('F', 'ENC', 'PR', 'P'=>array(1000, 100)) ),
//Veľká Británia
            'GB' => array( 'list_service' => array('D'),
                           'box_service' => array(),
                           'epg' =>array('2_eu', 30),
                           'ems' =>array('2_eu', 30) ),

        );

        $world_country = array(
//Afganistan
            'AF' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Alžírsko
            'DZ' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 100 ) ) ),
//Americká Samoa
            'AS' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 990, 100 ) ) ),
//Americké Panenské ostrovy
            'VI' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 990, 100 ) ) ),
//Angola
            'AO' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR' ) ),
//Anguilla
            'AI' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Antigua a Barbuda
            'AG' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Argentína
            'AR' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 0 ) ) ),
//Aruba
            'AW' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 765, 0 ) ) ),
//Austrália
            'AU' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('5', 20) ),
//Bahamy
            'BS' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'PR' ) ),
//Bahrajn
            'BH' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Bangladéš
            'BD' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR', 'P'=>array( 194, 0 ) ) ),
//Barbados
            'BB' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 194, 0 ) ) ),
//Belize
            'BZ' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Benin
            'BJ' => array( 'list_service' => array( 'D', 'VR', 'P'=>array( 547, 100 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 405, 0 ) ) ),
//Bermudy
            'BM' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Bhután
            'BT' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'P'=>array( 17, 100 ) ) ),
//Bolívia
            'BO' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Botswana
            'BW' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Brazília
            'BR' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR', 'P'=>array( 622, 100 ) ),
                           'ems' =>array('4', 30) ),
//Britské indickooceánske územie
            'IO' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Britské Panenské ostrovy
            'VG' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Brunej
            'BN' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 0 ) ) ),
//Burkina
            'BF' => array( 'list_service' => array( 'D', 'VR ', 'P'=>array( 830, 0 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 677, 100 ) ) ),
//Burundi
            'BI' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 0 ) ) ),
//Čad
            'TD' => array( 'list_service' => array( 'D', 'VR', 'P'=>array( 547, 100 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 423, 0 ) ) ),
//Čile
            'CL' => array( 'list_service' => array( 'D'),
                           'box_service' => array( ) ),
//Čína
            'CN' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'ENC', 'PR', 'P'=>array( 953, 100 ) ),
                           'ems' =>array('4', 30) ),
//Curaçao
            'CW' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'P'=>array( 765, 0 ) ) ),
//Dominika
            'DM' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR' ) ),
//Dominikánska republika
            'DO' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR' ) ),
//Džibutsko
            'DJ' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 813, 0 ) ) ),
//Egypt
            'EG' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'ENC,F', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('3', 20) ),
//Ekvádor
            'EC' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Eritrea
            'ER' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR' ) ),
//Etiópia
            'ET' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Faerské ostrovy
            'FO' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'F', 'ENC', 'P'=>array( 1000, 100 ) ) ),
//Falklandy
            'FK' => array( 'list_service' => array(  ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 423, 0 ) ) ),
//Fidži
            'FJ' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 0 ) ) ),
//Filipíny
            'PH' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR' ),
                           'ems' =>array('5', 20) ),
//Francúzska Guyana
            'GF' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 547, 100 ) ),
                           'box_service' => array( ) ),
//Francúzska Polynézia
            'PF' => array( 'list_service' => array(  ),
                           'box_service' => array( 'PR', 'P'=>array( 937, 0 ) ) ),
//Gabon
            'GA' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'PR', 'P'=>array( 447, 0 ) ) ),
//Gambia
            'GM' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 405, 0 ) ) ),
//Ghana
            'GH' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 405, 100 ) ) ),
//Grenada
            'GD' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Grónsko
            'GL' => array( 'list_service' => array( 'D', 'VR', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Guadeloupe
            'GP' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Guam
            'GU' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 990, 100 ) ) ),
//Guatemala
            'GT' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Guinea
            'GN' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 501, 0 ) ) ),
//Guinea-Bissau
            'GW' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Guyana
            'GY' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( ) ),
//Haiti
            'HT' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Honduras
            'HN' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Hongkong
            'HK' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('4', 30) ),
//India
            'IN' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR' ),
                           'ems' =>array('4', 30) ),
//Indonézia
            'ID' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F,ENC' ) ),
//Irak
            'IQ' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Irán
            'IR' => array( 'list_service' => array( 'D', 'VR', 'P'=>array( 498, 100 ) ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 10, 100 ) ) ),
//Izrael
            'IL' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ),
                           'ems' =>array('3', 20) ),
//Jamajka
            'JM' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'P'=>array( 405, 0 ) ) ),
//Japonsko
            'JP' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('4', 30) ),
//Jordánsko
            'JO' => array( 'list_service' => array( ),
                           'box_service' => array( ),
                           'ems' =>array('3', 30) ),
//Južná Afrika //Juhoafrická republika
            'ZA' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( ),
                           'ems' =>array('4', 20) ),
//Južný Sudán
            'SS' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Kajmanie ostrovy
            'KY' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F,ENC' ) ),
//Kambodža
            'KH' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Kamerun
            'CM' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 677, 100 ) ) ),
//Kanada
            'CA' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ),
                           'ems' =>array('3', 30) ),
//Kapverdy
            'CV' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 373, 100 ) ),
                           'box_service' => array( ) ),
//Katar
            'QA' => array( 'list_service' => array( ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Kazachstan
            'KZ' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('3', 30) ),
//Keňa
            'KE' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'P'=>array( 452, 100 ) ) ),
//Kirgizsko
            'KG' => array( 'list_service' => array( 'D', 'P'=>array( 186, 100 ) ),
                           'box_service' => array( 'F', 'ENC', 'P'=>array( 1000, 100 ) ) ),
//Kiribati
            'KI' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( ) ),
//Kolumbia
            'CO' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Komory
            'KM' => array( 'list_service' => array( 'VR' ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 0 ) ) ),
//Kongo
            'CG' => array( 'list_service' => array( ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Kongo (býv. Zair)
            'CD' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ) ),
//Kórejská ľudovodemokratická republika
            'KP' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Kórejská republika
            'KR' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('4', 30) ),
//Kostarika
            'CR' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Kuba
            'CU' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Kuvajt
            'KW' => array( 'list_service' => array( ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Laos
            'LA' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'PR' ) ),
//Lesotho
            'LS' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( ) ),
//Libanon
            'LB' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR', 'P'=>array( 405, 0 ) ) ),
//Libéria
            'LR' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 405, 0 ) ) ),
//Macao
            'MO' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 0 ) ) ),
//Madagaskar
            'MG' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ) ),
//Malajzia
            'MY' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 684, 0 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 100 ) ) ),
//Malawi
            'MW' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 159, 41 ) ) ),
//Maldivy
            'MV' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'PR', 'P'=>array( 92, 0 ) ) ),
//Mali
            'ML' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 553, 100 ) ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 553, 0 ) ) ),
//Maroko
            'MA' => array( 'list_service' => array( 'D', 'VR ', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 100 ) ) ),
//Marshallove ostrovy
            'MH' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 493, 100 ) ) ),
//Martinik
            'MQ' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Maurícius
            'MU' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Mauritánia
            'MR' => array( 'list_service' => array( 'VR', 'P'=>array( 553, 0 ) ),
                           'box_service' => array( 'P'=>array( 585, 0 ) ) ),
//Mexiko
            'MX' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR' ),
                           'ems' =>array('4', 20) ),
//Mikronézia
            'FM' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( ) ),
//Mjanmarsko
            'MM' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Mongolsko
            'MN' => array( 'list_service' => array( 'D', 'VR', 'P'=>array( 918, 100 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 405, 0 ) ) ),
//Montserrat
            'MS' => array( 'list_service' => array(  ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Mozambik
            'MZ' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Namíbia
            'NA' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR' ) ),
//Nauru
            'NR' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC', 'P'=>array( 202, 100 ) ) ),
//Nepál
            'NP' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Niger
            'NE' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 553, 0 ) ),
                           'box_service' => array( 'ENC', 'PR', 'P'=>array( 405, 0 ) ) ),
//Nigéria
            'NG' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ),
                           'ems' =>array('4', 30) ),
//Nikaragua
            'NI' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR', 'P'=>array( 405, 0 ) ) ),
//Nová Kaledónia
            'NC' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'ENC', 'PR' ) ),
//Nový Zéland
            'NZ' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR', 'P'=>array( 990, 100 ) ) ),
//Omán
            'OM' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Pakistan
            'PK' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'PR', 'P'=>array( 498, 100 ) ) ),
//Panama
            'PA' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ) ),
//Papua-Nová Guinea
            'PG' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ) ),
//Paraguaj
            'PY' => array( 'list_service' => array( ),
                           'box_service' => array( 'F' ) ),
//Peru
            'PE' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ) ),
//Pitcairnove ostrovy
            'PN' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Pobrežie Slonoviny
            'CI' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 677, 100 ) ) ),
//Portoriko
            'PR' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( ) ),
//Réunion
            'RE' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Rovníková Guinea
            'GQ' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Rwanda
            'RW' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Šalamúnove ostrovy
            'SB' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F' ) ),
//Salvádor
            'SV' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ) ),
//Samoa
            'WS' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F, ENC', 'PR', 'P'=>array( 410, 100 ) ) ),
//Saudská Arábia
            'SA' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC' ),
                           'ems' =>array('3', 30) ),
//Senegal
            'SN' => array( 'list_service' => array( 'D', 'P'=>array( 747, 0 ) ),
                           'box_service' => array( 'F', 'ENC', 'P'=>array( 656, 100 ) ) ),
//Seychely
            'SC' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ) ),
//Sierra Leone
            'SL' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Singapur
            'SG' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('4', 30) ),
//Somálsko
            'SO' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Spojené arabské emiráty
            'AE' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('3', 30) ),
//Spojené štáty americké
            'US' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'P'=>array( 990, 100 ) ),
                           'ems' =>array('3', 30) ),
//Srí Lanka
            'LK' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR', 'P'=>array( 92, 83 ) ) ),
//Stredoafrická republika
            'CF' => array( 'list_service' => array( 'D', 'VR', 'P'=>array( 422, 100 ) ),
                           'box_service' => array( 'P'=>array( 1000, 100 ) ) ),
//Sudán
            'SD' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'PR' ) ),
//Surinam
            'SR' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC ' ) ),
//Svätá Helena
            'SH' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'PR' ) ),
//Svätá Lucia
            'LC' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Svätý Krištof a Nevis
            'KN' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'ENC', 'PR' ) ),
//Svätý Peter a Michal
            'PM' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( ) ),
//Svätý Tomáš a Princov ostrov
            'ST' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR' ) ),
//Svätý Vincent a Grenadíny
            'VC' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Svazijsko
            'SZ' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 100 ) ) ),
//Tadžikistan
            'TJ' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 0, 100 ) ) ),
//Taiwan
            'TW' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( 'P'=>array( 811, 100 ) ),
                           'ems' =>array('4', 20) ),
//Tanzánia
            'TZ' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 211, 100 ) ) ),
//Thajsko
            'TH' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 1000, 100 ) ),
                           'ems' =>array('4', 30) ),
//Togo
            'TG' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 405, 100 ) ) ),
//Tonga
            'TO' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'ENC', 'PR', 'P'=>array( 476, 100 ) ) ),
//Trinidad a Tobago
            'TT' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 858, 0 ) ) ),
//Tristan da Cunha
            'TA' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Tunisko
            'TN' => array( 'list_service' => array( 'D', 'P'=>array( 1000, 0 ) ),
                           'box_service' => array( 'F', 'ENC', 'PR', 'P'=>array( 1000, 100 ) ) ),
//Turkménsko
            'TM' => array( 'list_service' => array( ),
                           'box_service' => array( 'PR', 'P'=>array( 622, 100 ) ) ),
//Turks a Caicos
            'TC' => array( 'list_service' => array( ),
                           'box_service' => array( 'F' ) ),
//Tuvalu
            'TV' => array( 'list_service' => array( ),
                           'box_service' => array( 'F', 'ENC' ) ),
//Uganda
            'UG' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR' ) ),
//Uruguaj
            'UY' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Uzbekistan
            'UZ' => array( 'list_service' => array( 'D', 'VR-D', 'P'=>array( 1000, 100 ) ),
                           'box_service' => array( 'PR', 'P'=>array( 1000, 100 ) ) ),
//Vanuatu
            'VU' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'ENC' ) ),
//Venezuela
            'VE' => array( 'list_service' => array( 'D' ),
                           'box_service' => array( ) ),
//Vietnam
            'VN' => array( 'list_service' => array( 'D', 'VR' ),
                           'box_service' => array( 'ENC' ),
                           'ems' =>array('4', 30) ),
//Východný Timor
            'TL' => array( 'list_service' => array( ),
                           'box_service' => array( ) ),
//Wallis a Futuna
            'WF' => array( 'list_service' => array( ),
                           'box_service' => array( 'P'=>array( 1000, 0 ) ) ),
//Zambia
            'ZM' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'PR', 'P'=>array( 498, 100 ) ) ),
//Zimbabwe
            'ZW' => array( 'list_service' => array( 'D', 'VR-D' ),
                           'box_service' => array( 'F', 'PR', 'P'=>array( 557, 100 ) ) )
        );

        $this->load->language('extension/shipping/slovenska_posta');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('slovenska_posta_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if (!$this->config->get('slovenska_posta_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();
        $quote_data = array();

        $weight_convert = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('slovenska_posta_weight_class_id'));

        if ($status) {

            //COD
            $cod_fee_status = $this->config->get('slovenska_posta_cod_fee_status');


            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = 'SK'");
            if ($query->num_rows && (int)$address['country_id'] == $query->row['country_id'] && $this->config->get('slovenska_posta_sk_status') ) {

                $free_weight = (float)$this->config->get('slovenska_posta_sk_free_vaha');
                $weight = $weight_convert;

                if ($this->config->get('slovenska_posta_sk_free_status') &&
                    ($this->config->get('slovenska_posta_sk_free_cena') < $this->cart->getTotal() )  &&
                    ( ($free_weight && ($free_weight > $weight)) || !$free_weight )//ak je zadaná hodnota a je väčšia než košík, alebo ak je parameter vaha 0 (na vahu sa neprihliada)
                ) {

                    $cost = 0;

                    if( ($free_weight && ($free_weight > $weight)) || !$free_weight ){ //ak je zadaná hodnota a je väčšia než košík, alebo ak je parameter vaha 0 (na vahu sa neprihliada)
                        $title = $this->getTitle(
                            $this->language->get('text_free'),
                            $weight
                        );

                        $code = 'sk_free';

                        //ak je modul total SP vypnutý, zarátať do delivery method
                        if( !$cod_fee_status ) {
                            $cost = $this->addServices($cost, array('sk_free_dobierka'));
                        }
                        //COD
                        $cod_status = false;
                        $cod_price = 0;
                        if($this->config->get('slovenska_posta_sk_free_dobierka_status')){
                            $cod_status = true;
                            $cod_price = $this->config->get('slovenska_posta_sk_free_dobierka_cena');
                        }

                        $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                    }

                }else{

                    if ($this->config->get('slovenska_posta_sk_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_sk_list_box_vaha');

                        for ($x = 1; $x < 3; $x++) {

                            if( !$this->config->get('slovenska_posta_sk_list_' . $x . '_status') ) continue;

                            $cost = $this->getRate('slovenska_posta_sk_list_' . $x . '_sadzba', $weight);

                            if ((float)$cost) {

                                $title = $this->getTitle(
                                    $this->language->get('text_list'). ' ' . $x . '. ' . $this->language->get('text_trieda'),
                                    $weight
                                );

                                $code = 'list_' . $x . '_trieda';

                                $quote_data[$code] = $this -> createLabel($code, $title, $cost );
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_sk_dop_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_sk_dop_list_box_vaha');

                        for ($x = 1; $x < 3; $x++) {

                            if( !$this->config->get('slovenska_posta_sk_dop_list_' . $x . '_status') ) continue;

                            $cost = $this->getRate('slovenska_posta_sk_dop_list_' . $x . '_sadzba', $weight);

                            if ((float)$cost) {

                                $title = $this->getTitle(
                                    $this->language->get('text_dopopuceny_list'). ' ' . $x . '. ' . $this->language->get('text_trieda'),
                                    $weight
                                );

                                $cost = $this -> addServices($cost, array('sk_dop_list_dorucenka', 'sk_dop_list_do_ruk' ) );

                                //ak je modul total SP vypnutý, zarátať do delivery method
                                if( !$cod_fee_status ) {
                                    $cost = $this->addServices($cost, array('sk_dop_list_dobierka'));
                                }

                                $code = 'doporuceny_list_' . $x . '_trieda';

                                $quote_data[$code] = $this -> createLabel($code, $title, $cost, $this->config->get('slovenska_posta_sk_dop_list_dobierka_status'), $this->config->get('slovenska_posta_sk_dop_list_dobierka_cena') );
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_sk_poistenie_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_sk_poistenie_list_box_vaha');

                        for ($x = 1; $x < 3; $x++) {

                            if( !$this->config->get('slovenska_posta_sk_poistenie_list_' . $x . '_status') ) continue;

                            $cost = $this->getRate('slovenska_posta_sk_poistenie_list_' . $x . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_poistena_zasielka'). ' ' . $x . '. ' . $this->language->get('text_trieda');

                                $cost_insurance = null;
                                $cost_insurance = $this->getInsurancePrice('sk_poistenie_list');
                                if ( $cost_insurance[0] == -1) continue;
                                $cost += $cost_insurance[1];

                                $title = $this->getTitle(
                                    $title,
                                    $weight,
                                    $cost_insurance
                                );

                                $cost = $this -> addServices($cost, array( 'sk_poistenie_list_dorucenka', 'sk_poistenie_list_do_ruk' ) );

                                //ak je modul total SP vypnutý, zarátať do delivery method
                                if( !$cod_fee_status ) {
                                    $cost = $this->addServices($cost, array('sk_poistenie_list_dobierka'));
                                }

                                $code = 'sk_poisteny_list_' . $x . '_trieda';

                                $quote_data[$code] = $this -> createLabel($code, $title, $cost, $this->config->get('slovenska_posta_sk_poistenie_list_dobierka_status'), $this->config->get('slovenska_posta_sk_poistenie_list_dobierka_cena') );
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_sk_balik_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_sk_balik_box_vaha');

                        foreach (array('adresa', 'posta') as $destination ) {

                            if( !$this->config->get('slovenska_posta_sk_balik_' . $destination . '_status') ) continue;

                            $cost = $this->getRate('slovenska_posta_sk_balik_' . $destination . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_balik_' . ($destination=='adresa' ? 'adresa' : 'posta'));

                                $cost_insurance = null;
                                if ($this->config->get('slovenska_posta_sk_balik_poistenie_status')) {
                                    $cost_insurance = $this->getInsurancePrice('sk_balik');
                                    if ( $cost_insurance[0] == -1) continue;
                                    $cost += $cost_insurance[1];
                                }

                                $title = $this->getTitle(
                                    $title,
                                    $weight,
                                    $cost_insurance
                                );

                                $cost = $this -> addServices($cost, array('sk_balik_krehke', 'sk_balik_neskladne' ) );

                                //ak je modul total SP vypnutý, zarátať do delivery method
                                if( !$cod_fee_status ) {
                                    $cost = $this->addServices($cost, array('sk_balik_dobierka'));
                                }

                                $code = 'balik_' . $destination;

                                $quote_data[$code] = $this -> createLabel($code, $title, $cost, $this->config->get('slovenska_posta_sk_balik_dobierka_status'), $this->config->get('slovenska_posta_sk_balik_dobierka_cena') );
                            }
                        }
                    }


                    if ($this->config->get('slovenska_posta_sk_easy_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_sk_easy_box_vaha');

                        foreach (array('plast', 'karton') as $destination ) {

                            if( !$this->config->get('slovenska_posta_sk_easy_' . $destination . '_status') ) continue;

                            $cost = $this->getRate('slovenska_posta_sk_easy_' . $destination . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_easy') . " " . ($destination=='plast' ? 'plastový obal' : 'kartónový obal');

                                $cost_insurance = null;
                                if ($this->config->get('slovenska_posta_sk_easy_poistenie_status')) {
                                    $cost_insurance = $this->getInsurancePrice('sk_easy');
                                    if ( $cost_insurance[0] == -1) continue;
                                    $cost += $cost_insurance[1];
                                }

                                $title = $this->getTitle(
                                    $title,
                                    $weight,
                                    $cost_insurance
                                );

                                $cost = $this -> addServices($cost, array('sk_easy_dorucenie_info', 'sk_easy_doruk' ) );

                                if ($this->config->get('slovenska_posta_sk_easy_delivery_time_id')) {
                                    $delivery_classes = array(
                                        $this->config->get('slovenska_posta_sk_kurier_do10_cena'),
                                        $this->config->get('slovenska_posta_sk_kurier_sobota_cena')
                                    );
                                    $cost += $delivery_classes[$this->config->get('slovenska_posta_sk_easy_delivery_time_id') - 1];
                                }

                                //ak je modul total SP vypnutý, zarátať do delivery method
                                if( !$cod_fee_status ) {
                                    $cost = $this->addServices($cost, array('sk_easy_dobierka'));
                                }

                                $code = 'easy_' . $destination;

                                $quote_data[$code] = $this -> createLabel($code, $title, $cost, $this->config->get('slovenska_posta_sk_easy_dobierka_status'), $this->config->get('slovenska_posta_sk_easy_dobierka_cena') );
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_sk_kurier_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_sk_kurier_box_vaha');

                        for ($x=1; $x<3; $x++) {
                            $destination = ($x==1 ? 'adresa' : 'posta');

                            if( !$this->config->get('slovenska_posta_sk_kurier_' . $destination . '_status') ) continue;

                            $cost = $this->getRate('slovenska_posta_sk_kurier_posta_sadzba', $weight);

                            if ((float)$cost) {

                                $title = $this->language->get('text_kurier') . " " .
                                    ($destination=='adresa' ?
                                        $this->language->get('text_na_adresu') :
                                        $this->language->get('text_na_postu')
                                    );

                                $cost_insurance = null;
                                if ($this->config->get('slovenska_posta_sk_kurier_poistenie_status')) {
                                    $cost_insurance = $this->getInsurancePrice('sk_kurier');
                                    if ( $cost_insurance[0] == -1) continue;
                                    $cost += $cost_insurance[1];

                                }

                                $title = $this->getTitle(
                                    $title,
                                    $weight,
                                    $cost_insurance
                                );

                                if ( $destination == 'adresa') {
                                    if ($this->config->get('slovenska_posta_sk_kurier_delivery_time_id') == 1) {
                                        if ($this->config->get('slovenska_posta_sk_kurier_krehke_status')) {
                                            $cost -= $this->config->get('slovenska_posta_sk_kurier_krehke_cena');
                                        }
                                        if ($this->config->get('slovenska_posta_sk_kurier_dorucenie_info_status')) {
                                            $cost -= $this->config->get('slovenska_posta_sk_kurier_dorucenie_info_cena');
                                        }
                                        if ($this->config->get('slovenska_posta_sk_kurier_doruk_status')) {
                                            $cost -= $this->config->get('slovenska_posta_sk_kurier_doruk_cena');
                                        }

                                    }
                                }

                                $cost = $this -> addServices($cost, array('sk_kurier_krehke', 'sk_kurier_dorucenie_info', 'sk_kurier_doruk', 'sk_kurier_neskladne' ) );

                                if ( $destination == 'adresa') {
                                    if ($this->config->get('slovenska_posta_sk_kurier_delivery_time_id')) {
                                        $delivery_classes = array(
                                            $this->config->get('slovenska_posta_sk_kurier_denpodania_cena'),
                                            $this->config->get('slovenska_posta_sk_kurier_do10_cena'),
                                            $this->config->get('slovenska_posta_sk_kurier_do14_cena'),
                                            $this->config->get('slovenska_posta_sk_kurier_sobota_cena')
                                        );
                                        $cost += $delivery_classes[ $this->config->get('slovenska_posta_sk_kurier_delivery_time_id') - 1 ];
                                    }
                                }

                                if ($destination == 'adresa') {
                                    $cost += $this->config->get('slovenska_posta_sk_kurier_adresa_cena');
                                }

                                if ($this->config->get('slovenska_posta_sk_kurier_podaj_kurier_status')) {
                                    $cost = $this->getRate('slovenska_posta_sk_kurier_podaj_kurier_sadzba', $weight, $cost);
                                }

                                //ak je modul total SP vypnutý, zarátať do delivery method
                                if( !$cod_fee_status ) {
                                    $cost = $this->addServices($cost, array('sk_kurier_dobierka'));
                                }

                                $code = 'kurier_' . $destination;

                                $quote_data[$code] = $this -> createLabel($code, $title, $cost, $this->config->get('slovenska_posta_sk_kurier_dobierka_status'), $this->config->get('slovenska_posta_sk_kurier_dobierka_cena') );
                            }
                        }
                    }
                }
            }

            elseif( $this->config->get('slovenska_posta_eu_status') && array_key_exists($address['iso_code_2'], $eu_country  ) ) {

                $free_weight = (float)$this->config->get('slovenska_posta_eu_free_vaha');
                $weight = $weight_convert;

                if ($this->config->get('slovenska_posta_eu_free_status') &&
                    ($this->config->get('slovenska_posta_eu_free_cena') < $this->cart->getTotal() &&
                        ( ($free_weight && ($free_weight > $weight)) || !$free_weight )//ak je zadaná hodnota a je väčšia než košík, alebo ak je parameter vaha 0 (na vahu sa neprihliada)
                    )
                ) {
                    $cost = 0;

                    $title = $this->getTitle(
                        $this->language->get('text_free'),
                        $weight
                    );

                    $code = 'eu_free';

                    //ak je modul total SP vypnutý, zarátať do delivery method
                    if( !$cod_fee_status ) {
                        $cost = $this->addServices($cost, array('eu_free_dobierka'));
                    }
                    //COD
                    $cod_status = false;
                    $cod_price = 0;
                    if($this->config->get('slovenska_posta_eu_free_dobierka_status')){
                        $cod_status = true;
                        $cod_price = $this->config->get('slovenska_posta_eu_free_dobierka_cena');
                    }

                    $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);


                }else {

                    if ($this->config->get('slovenska_posta_eu_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_eu_list_box_vaha');

                        $cost = $this->getRate('slovenska_posta_eu_list_1_sadzba', $weight);

                        if ((float)$cost) {
                            $title = $this->getTitle(
                                $this->language->get('text_list'),
                                $weight
                            );

                            $code = 'eu_list';
                            $quote_data[$code] = $this->createLabel($code, $title, $cost);
                        }

                    }

                    if ($this->config->get('slovenska_posta_eu_dop_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_eu_dop_list_box_vaha');

                        $cost = $this->getRate('slovenska_posta_eu_dop_list_sadzba', $weight);

                        if ((float)$cost) {

                            $cost = $this->addServicesIfPosible(
                                $cost,
                                $eu_country[$address['iso_code_2']]['list_service'],
                                array('VR'=>'eu_dop_list_do_ruk', 'D'=>'eu_dop_list_dorucenka', 'DOB'=>'eu_dop_list_dobierka')
                            );

                            $title = $this->getTitle(
                                $this->language->get('text_dopopuceny_list'),
                                $weight
                            );
                            $code = 'eu_doporuceny_list';

                            //COD
                            $cod_status = false;
                            $cod_price = 0;
                            if(in_array('DOB', $eu_country[$address['iso_code_2']]['list_service'])){
                                if($this->config->get('slovenska_posta_eu_dop_list_dobierka_status')){
                                    $cod_status = true;
                                    $cod_price = $this->config->get('slovenska_posta_eu_dop_list_dobierka_cena');
                                }
                            }

                            $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                        }
                    }




                    if ($this->config->get('slovenska_posta_eu_poistenie_list_status') && array_key_exists('P', $eu_country[$address['iso_code_2']]['list_service'] )  ) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_eu_poistenie_list_box_vaha');

                        $insurance_limits = $eu_country[$address['iso_code_2']]['list_service']['P'];

                        for ($x = 1; $x < 2; $x++) {

                            if($this->cart->getTotal() > $insurance_limits[$x-1]) continue; // služba dostupná len pre sumu nižžšie alebo rovnú ako nastavenie danej krajiny

                            $limit = false;
                            if($this->cart->getTotal() <= 100){
                                $limit = 100;
                            }elseif($this->cart->getTotal() <=500){
                                $limit = 500;
                            }elseif($this->cart->getTotal() <= 100){
                                $limit = 1000;
                            }

                            if(!$limit) continue; //je mozne poistit len do vysky 1000€, povolit najvyššie poistenie z principu nie je mozne

                            $cost = $this->getRate('slovenska_posta_eu_poistenie_list_' . $x . '_' . $limit . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_poistena_zasielka');

                                $title = $this->getTitle(
                                    $title,
                                    $weight
                                );

                                $cost = $this->addServicesIfPosible(
                                    $cost,
                                    $eu_country[$address['iso_code_2']]['list_service'],
                                    array('VR'=>'eu_poistenie_list_do_ruk', 'D'=>'eu_poistenie_list_dorucenka', 'DOB'=>'eu_poistenie_list_dobierka')
                                );

                                $code = 'eu_poisteny_list';

                                //COD
                                $cod_status = false;
                                $cod_price = 0;
                                if(in_array('DOB', $eu_country[$address['iso_code_2']]['list_service'])){
                                    if($this->config->get('slovenska_posta_eu_poistenie_list_dobierka_status')){
                                        $cod_status = true;
                                        $cod_price = $this->config->get('slovenska_posta_eu_poistenie_list_dobierka_cena');
                                    }
                                }

                                $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_eu_balik_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_eu_balik_box_vaha');

                        for ($x = 1; $x < 2; $x++) {

                            $cost = $this->getRate('slovenska_posta_eu_balik_' . $x . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_balik_cz');

                                $cost_insurance = null;
                                if ($this->config->get('slovenska_posta_eu_balik_poistenie_status')) {

                                    if ( array_key_exists( 'P', $eu_country[$address['iso_code_2']]['box_service'] ) ){
                                        $insurance_limits = $eu_country[$address['iso_code_2']]['box_service']['P'];

                                        $cost_insurance = $this->getInsurancePrice('eu_balik', $insurance_limits[$x-1]);
                                        if ( $cost_insurance[0] == -1) continue;
                                        $cost += $cost_insurance[1];

                                        $title = $this->getTitle(
                                            $title,
                                            $weight,
                                            $cost_insurance
                                        );
                                    }
                                    else{
                                        $title = $this->getTitle(
                                            $title,
                                            $weight
                                        );
                                    }

                                }else{
                                    $title = $this->getTitle(
                                        $title,
                                        $weight
                                    );
                                }

                                $services = array();
                                if ($this->config->get('slovenska_posta_eu_balik_neskladne_status') &&
                                    $this->config->get('slovenska_posta_eu_balik_krehke_status')
                                ) {
                                    $services['F'] = 'eu_balik_krehke';
                                } elseif ($this->config->get('slovenska_posta_eu_balik_neskladne_status')) {
                                    $services['ENC'] = 'eu_balik_neskladne';
                                } else {
                                    $services['F'] = 'eu_balik_krehke';
                                }

                                $cost = $this->addServicesIfPosible(
                                    $cost,
                                    $eu_country[$address['iso_code_2']]['box_service'],
                                    array('ENC' => 'eu_balik_neskladne', 'F' => 'eu_balik_krehke', 'DOB'=>'eu_balik_dobierka')
                                );

                                $code = 'eu_balik';

                                //COD
                                $cod_status = false;
                                $cod_price = 0;
                                if(in_array('DOB', $eu_country[$address['iso_code_2']]['box_service'])){
                                    if($this->config->get('slovenska_posta_eu_balik_dobierka_status')){
                                        $cod_status = true;
                                        $cod_price = $this->config->get('slovenska_posta_eu_balik_dobierka_cena');
                                    }
                                }

                                $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                            }
                        }
                    }

                    //epg
                    if ($this->config->get('slovenska_posta_eu_epg_status')) {


                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_eu_epg_box_vaha');

                        if (array_key_exists('epg', $eu_country[$address['iso_code_2']]) &&
                            $this->config->has('slovenska_posta_eu_epg_' . $eu_country[$address['iso_code_2']]['epg'][0] . '_sadzba') &&
                            ($weight <= $eu_country[$address['iso_code_2']]['epg'][1])
                        ) {

                            $cost = $this->getRate('slovenska_posta_eu_epg_' . $eu_country[$address['iso_code_2']]['epg'][0] . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->getTitle($this->language->get('text_epg'), $weight);
                                $code = 'eu_epg';
                                $quote_data[$code] = $this->createLabel($code, $title, $cost);
                            }
                        }
                    }
                    //ems
                    if ($this->config->get('slovenska_posta_eu_ems_status')) {

                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_eu_ems_box_vaha');

                        if (array_key_exists('ems', $eu_country[$address['iso_code_2']]) &&
                            $this->config->has('slovenska_posta_eu_ems_' . $eu_country[$address['iso_code_2']]['ems'][0] . '_sadzba')  &&
                            ($weight <= $eu_country[$address['iso_code_2']]['ems'][1])
                        ) {

                            $cost = $this->getRate('slovenska_posta_eu_ems_' . $eu_country[$address['iso_code_2']]['ems'][0] . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->getTitle($this->language->get('text_ems'), $weight);
                                $code = 'eu_ems';
                                $quote_data[$code] = $this->createLabel($code, $title, $cost);
                            }
                        }
                    }



                }
            }



            elseif( $this->config->get('slovenska_posta_world_status') && array_key_exists($address['iso_code_2'], $world_country  ) ) {

                $weight = $weight_convert;
                $free_weight = (float)$this->config->get('slovenska_posta_world_free_vaha');

                if ($this->config->get('slovenska_posta_world_free_status') &&
                    ($this->config->get('slovenska_posta_world_free_cena') < $this->cart->getTotal() )&&
                        ( ($free_weight && ($free_weight > $weight)) || !$free_weight )//ak je zadaná hodnota a je väčšia než košík, alebo ak je parameter vaha 0 (na vahu sa neprihliada)
                ) {
                    $cost = 0;

                    $title = $this->getTitle(
                        $this->language->get('text_free'),
                        $weight
                    );

                    $code = 'world_free';

                    //ak je modul total SP vypnutý, zarátať do delivery method
                    if( !$cod_fee_status ) {
                        $cost = $this->addServices($cost, array('world_free_dobierka'));
                    }

                    //COD
                    $cod_status = false;
                    $cod_price = 0;
                    if($this->config->get('slovenska_posta_world_free_dobierka_status')){
                        $cod_status = true;
                        $cod_price = $this->config->get('slovenska_posta_world_free_dobierka_cena');
                    }

                    $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);


                }else {

                    if ($this->config->get('slovenska_posta_world_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_world_list_box_vaha');


                        for ($x = 1; $x < 2; $x++) {

                            $cost = $this->getRate('slovenska_posta_world_list_' . $x . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->getTitle(
                                    $this->language->get('text_list'),
                                    $weight
                                );

                                $code = 'world_list';
                                $quote_data[$code] = $this->createLabel($code, $title, $cost);
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_world_dop_list_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_world_dop_list_box_vaha');

                        $cost = $this->getRate('slovenska_posta_world_dop_list_sadzba', $weight);

                        if ((float)$cost) {

                            $cost = $this->addServicesIfPosible(
                                $cost,
                                $world_country[$address['iso_code_2']]['list_service'],
                                array('VR'=>'world_dop_list_do_ruk', 'D'=>'world_dop_list_dorucenka', 'DOB'=>'world_dop_list_dobierka')
                            );

                            $title = $this->getTitle(
                                $this->language->get('text_dopopuceny_list'),
                                $weight
                            );
                            $code = 'world_doporuceny_list';

                            //COD
                            $cod_status = false;
                            $cod_price = 0;
                            if(in_array('DOB', $world_country[$address['iso_code_2']]['list_service'])){
                                if($this->config->get('slovenska_posta_world_dop_list_dobierka_status')){
                                    $cod_status = true;
                                    $cod_price = $this->config->get('slovenska_posta_world_dop_list_dobierka_cena');
                                }
                            }

                            $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                        }
                    }



                    if ($this->config->get('slovenska_posta_world_poistenie_list_status') && array_key_exists('P', $world_country[$address['iso_code_2']]['list_service'] )  ) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_world_poistenie_list_box_vaha');

                        $insurance_limits = $world_country[$address['iso_code_2']]['list_service']['P'];

                        for ($x = 1; $x < 2; $x++) {

                            if($this->cart->getTotal() > $insurance_limits[$x-1]) continue; // služba dostupná len pre sumu nižžšie alebo rovnú ako nastavenie danej krajiny

                            $limit = false;
                            if($this->cart->getTotal() <= 100){
                                $limit = 100;
                            }elseif($this->cart->getTotal() <=500){
                                $limit = 500;
                            }elseif($this->cart->getTotal() <= 100){
                                $limit = 1000;
                            }

                            if(!$limit ) continue; //je mozne poistit len do vysky max 1000€, povolit najvyššie poistenie z principu nie je mozne

                            $cost = $this->getRate('slovenska_posta_world_poistenie_list_' . $x . '_' . $limit . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_poistena_zasielka');


                                $title = $this->getTitle(
                                    $title,
                                    $weight
                                );

                                $cost = $this->addServicesIfPosible(
                                    $cost,
                                    $world_country[$address['iso_code_2']]['list_service'],
                                    array('VR'=>'world_poistenie_list_do_ruk', 'D'=>'world_poistenie_list_dorucenka', 'DOB'=>'world_poistenie_list_dobierka')
                                );

                                $code = 'world_poisteny_list';

                                //COD
                                $cod_status = false;
                                $cod_price = 0;
                                if(in_array('DOB', $world_country[$address['iso_code_2']]['list_service'])){
                                    if($this->config->get('slovenska_posta_world_poistenie_list_dobierka_status')){
                                        $cod_status = true;
                                        $cod_price = $this->config->get('slovenska_posta_world_poistenie_list_dobierka_cena');
                                    }
                                }

                                $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                            }
                        }
                    }

                    if ($this->config->get('slovenska_posta_world_balik_status')) {
                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_world_balik_box_vaha');

                        for ($x = 1; $x < 2; $x++) {


                            $cost = $this->getRate('slovenska_posta_world_balik_' . $x . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->language->get('text_balik_cz');

                                $cost_insurance = null;
                                if ($this->config->get('slovenska_posta_world_balik_poistenie_status')) {

                                    if ( array_key_exists( 'P', $world_country[$address['iso_code_2']]['box_service'] ) ){
                                        $insurance_limits = $world_country[$address['iso_code_2']]['box_service']['P'];

                                        $cost_insurance = $this->getInsurancePrice('world_balik', $insurance_limits[$x-1]);
                                        if ( $cost_insurance[0] == -1) continue;
                                        $cost += $cost_insurance[1];

                                        $title = $this->getTitle(
                                            $title,
                                            $weight,
                                            $cost_insurance
                                        );
                                    }
                                    else{
                                        $title = $this->getTitle(
                                            $title,
                                            $weight
                                        );
                                    }

                                }else{
                                    $title = $this->getTitle(
                                        $title,
                                        $weight
                                    );
                                }



                                $services = array();
                                if ($this->config->get('slovenska_posta_world_balik_neskladne_status') &&
                                    $this->config->get('slovenska_posta_world_balik_krehke_status')
                                ) {
                                    $services['F'] = 'world_balik_krehke';
                                } elseif ($this->config->get('slovenska_posta_world_balik_neskladne_status')) {
                                    $services['ENC'] = 'world_balik_neskladne';
                                } else {
                                    $services['F'] = 'world_balik_krehke';
                                }

                                $cost = $this->addServicesIfPosible(
                                    $cost,
                                    $world_country[$address['iso_code_2']]['box_service'],
                                    array('ENC' => 'world_balik_neskladne', 'F' => 'world_balik_krehke', 'DOB'=>'world_balik_dobierka')
                                );


                                $code = 'world_balik';

                                //COD
                                $cod_status = false;
                                $cod_price = 0;
                                if(in_array('DOB', $world_country[$address['iso_code_2']]['box_service'])){
                                    if($this->config->get('slovenska_posta_world_balik_dobierka_status')){
                                        $cod_status = true;
                                        $cod_price = $this->config->get('slovenska_posta_world_balik_dobierka_cena');
                                    }
                                }

                                $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);
                            }
                        }
                    }

                    //ems
                    if ($this->config->get('slovenska_posta_world_ems_status')) {

                        $weight = $weight_convert;
                        $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_world_ems_box_vaha');

                        if (array_key_exists('ems', $world_country[$address['iso_code_2']]) &&
                            $this->config->has('slovenska_posta_world_ems_' . $world_country[$address['iso_code_2']]['ems'][0] . '_sadzba')   &&
                            ($weight <= $world_country[$address['iso_code_2']]['ems'][1])
                        ) {

                            $cost = $this->getRate('slovenska_posta_world_ems_' . $world_country[$address['iso_code_2']]['ems'][0] . '_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->getTitle($this->language->get('text_ems'), $weight);
                                $code = 'world_ems';
                                $quote_data[$code] = $this->createLabel($code, $title, $cost);
                            }
                        }
                    }
                }
            }


            else {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = 'CZ'");
                if ($query->num_rows && (int)$address['country_id'] == $query->row['country_id'] && $this->config->get('slovenska_posta_cz_status')) {

                    $weight = $weight_convert;
                    $free_weight = (float)$this->config->get('slovenska_posta_cz_free_vaha');

                    if ($this->config->get('slovenska_posta_cz_free_status') &&
                        ($this->config->get('slovenska_posta_cz_free_cena') < $this->cart->getTotal() ) &&
                        ( ($free_weight && ($free_weight > $weight)) || !$free_weight )//ak je zadaná hodnota a je väčšia než košík, alebo ak je parameter vaha 0 (na vahu sa neprihliada)
                    ) {
                        $cost = 0;

                        $title = $this->getTitle(
                            $this->language->get('text_free'),
                            $weight
                        );

                        $code = 'cz_free';

                        //ak je modul total SP vypnutý, zarátať do delivery method
                        if( !$cod_fee_status ) {
                            $cost = $this->addServices($cost, array('cz_free_dobierka'));
                        }
                        //COD
                        $cod_status = false;
                        $cod_price = 0;
                        if($this->config->get('slovenska_posta_cz_free_dobierka_status')){
                            $cod_status = true;
                            $cod_price = $this->config->get('slovenska_posta_cz_free_dobierka_cena');
                        }

                        $quote_data[$code] = $this->createLabel($code, $title, $cost, $cod_status, $cod_price);

                    }else {

                        if ($this->config->get('slovenska_posta_cz_list_status')) {
                            $weight = $weight_convert;
                            $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_cz_list_box_vaha');

                            $cost = $this->getRate('slovenska_posta_cz_list_1_sadzba', $weight);

                            if ((float)$cost) {
                                $title = $this->getTitle( $this->language->get('text_list'), $weight );

                                $code = 'cz_list';
                                $quote_data[$code] = $this->createLabel($code, $title, $cost);
                            }
                        }

                        if ($this->config->get('slovenska_posta_cz_dop_list_status')) {
                            $weight = $weight_convert;
                            $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_cz_dop_list_box_vaha');

                            $cost = $this->getRate('slovenska_posta_cz_dop_list_sadzba', $weight);

                            if ((float)$cost) {

                                $cost = $this->addServices($cost,
                                    array('cz_dop_list_do_ruk', 'cz_dop_list_dorucenka' ));

                                $title = $this->getTitle(
                                    $this->language->get('text_dopopuceny_list'),
                                    $weight
                                );

                                //ak je modul total SP vypnutý, zarátať do delivery method
                                if( !$cod_fee_status ) {
                                    $cost = $this->addServices($cost, array('cz_dop_list_dobierka'));
                                }

                                $code = 'cz_doporuceny_list';

                                $quote_data[$code] = $this->createLabel($code, $title, $cost, $this->config->get('slovenska_posta_cz_dop_list_dobierka_status'), $this->config->get('slovenska_posta_cz_dop_list_dobierka_cena') );
                            }
                        }


                        if ($this->config->get('slovenska_posta_cz_poistenie_list_status')) {
                            $weight = $weight_convert;
                            $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_cz_poistenie_list_box_vaha');

                            for ($x = 1; $x < 2; $x++) { //nechavam cyklus aby som z neho mohol vyskočiť

                                $limit = false;
                                if($this->cart->getTotal() <= 100){
                                    $limit = 100;
                                }elseif($this->cart->getTotal() <=500){
                                    $limit = 500;
                                }elseif($this->cart->getTotal() <= 100){
                                    $limit = 1000;
                                }

                                if(!$limit) continue; //je mozne poistit len do vysky 1000€, povolit najvyššie poistenie z principu nie je mozne

                                $cost = $this->getRate('slovenska_posta_cz_poistenie_list_' . $x . '_' . $limit . '_sadzba', $weight);

                                if ((float)$cost) {
                                    $title = $this->language->get('text_poistena_zasielka');

                                    $title = $this->getTitle(
                                        $title,
                                        $weight
                                    );

                                    $cost = $this->addServices($cost, array(
                                        'cz_poistenie_list_dorucenka',
                                        'cz_poistenie_list_do_ruk'
                                    ));

                                    //ak je modul total SP vypnutý, zarátať do delivery method
                                    if( !$cod_fee_status ) {
                                        $cost = $this->addServices($cost, array('cz_poistenie_list_dobierka'));
                                    }

                                    $code = 'cz_poisteny_list';

                                    $quote_data[$code] = $this->createLabel($code, $title, $cost, $this->config->get('slovenska_posta_cz_poistenie_list_dobierka_status'), $this->config->get('slovenska_posta_cz_poistenie_list_dobierka_cena') );
                                }
                            }
                        }

                        if ($this->config->get('slovenska_posta_cz_balik_status')) {
                            $weight = $weight_convert;
                            $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_cz_balik_box_vaha');

                            for ($x = 1; $x < 2; $x++) { //nechávam cyklus, aby som z neho mohol vyskočiť

                                $cost = $this->getRate('slovenska_posta_cz_balik_' . $x . '_sadzba', $weight);

                                if ((float)$cost) {
                                    $title = $this->language->get('text_balik_cz');

                                    $cost_insurance = null;
                                    if ($this->config->get('slovenska_posta_cz_balik_poistenie_status')) {
                                        $cost_insurance = $this->getInsurancePrice('cz_balik');
                                        if ( $cost_insurance[0] == -1) continue;
                                        $cost += $cost_insurance[1];
                                    }

                                    $title = $this->getTitle(
                                        $title,
                                        $weight,
                                        $cost_insurance
                                    );


                                    if ($this->config->get('slovenska_posta_cz_balik_neskladne_status') &&
                                        $this->config->get('slovenska_posta_cz_balik_krehke_status')
                                    ) {
                                        $cost = $this->addServices($cost, array('cz_balik_krehke'));
                                    } elseif ($this->config->get('slovenska_posta_cz_balik_neskladne_status')) {
                                        $cost = $this->addServices($cost, array('cz_balik_neskladne'));
                                    } else {
                                        $cost = $this->addServices($cost, array('cz_balik_krehke'));
                                    }


                                    //ak je modul total SP vypnutý, zarátať do delivery method
                                    if( !$cod_fee_status ) {
                                        $cost = $this->addServices($cost, array('cz_balik_dobierka'));
                                    }


                                    $code = 'cz_balik';

                                    $quote_data[$code] = $this->createLabel($code, $title, $cost, $this->config->get('slovenska_posta_cz_balik_dobierka_status'), $this->config->get('slovenska_posta_cz_balik_dobierka_cena') );
                                }
                            }
                        }

                        //epg
                        if ($this->config->get('slovenska_posta_cz_epg_status')) {
                            $weight = $weight_convert;
                            $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_cz_epg_box_vaha');

                            $cost = $this->getRate('slovenska_posta_cz_epg_sadzba', $weight);

                            if ((float)$cost) {

                                $title = $this->getTitle(
                                    $this->language->get('text_epg'),
                                    $weight
                                );

                                $code = 'cz_epg';

                                $quote_data[$code] = $this->createLabel($code, $title, $cost );
                            }
                        }
                        //ems
                        if ($this->config->get('slovenska_posta_cz_ems_status')) {
                            $weight = $weight_convert;
                            $weight = $this->getAdditionalWeight($weight, 'slovenska_posta_cz_ems_box_vaha');

                            $cost = $this->getRate('slovenska_posta_cz_ems_sadzba', $weight);

                            if ((float)$cost) {

                                $title = $this->getTitle(
                                    $this->language->get('text_ems'),
                                    $weight
                                );

                                $code = 'cz_ems';

                                $quote_data[$code] = $this->createLabel($code, $title, $cost );
                            }
                        }

                    }
                }
            }

            if ($quote_data) {
                $method_data = array(
                    'code'       => 'slovenska_posta',
                    'title'      => $this->language->get('text_title'),
                    'quote'      => $quote_data,
                    'sort_order' => $this->config->get('slovenska_posta_sort_order'),
                    'error'      => false
                );
            }
        }
        return $method_data;
    }

}