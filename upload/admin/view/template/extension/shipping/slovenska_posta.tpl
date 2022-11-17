<?php echo $header; ?><?php echo $column_left; ?>


<div id="content">
<div class="page-header">
    <div class="container-fluid">
        <div class="pull-right">
            <button type="submit" form="form-posta" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                    class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
               class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <h1><?php echo $heading_title; ?></h1>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="container-fluid">
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php foreach($error_warning as $warning){echo "<br />" . $warning;}  ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>

<?php if ( (!$error_warning) ) { ?>
<div id="ikros_notification" class="alert alert-info"><i class="fa fa-info-circle"></i>
    <div id="ikros_loading"><?php echo $text_loading_notifications; ?></div>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>

<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
</div>
<div class="panel-body">


<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-posta"
      class="form-horizontal">


<div class="row">
    <ul class="nav nav-tabs">

        <li class="nav-item active">
            <a class="nav-link" data-toggle="tab" href="#hlava"><?php echo $text_tab_main; ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#slovensko"><?php echo $text_tab_sk; ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#cesko"><?php echo $text_tab_cz; ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#eu"><?php echo $text_tab_eu; ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#world"><?php echo $text_tab_world; ?></a>
        </li>
    </ul>
</div>


<div class="row">

<div class="tab-content">

<div class="tab-pane active" id="hlava">

    <div class="tab-content">

        <div class="tab-pane active" id="tab-vseobecne">

            <?php $createRadioButton($entry_display_weight, $help_display_weight, "slovenska_posta_display_weight", $slovenska_posta_display_weight, $text_yes, $text_no) ?>
            <?php $createRadioButton($entry_display_insurance, $help_display_insurance, "slovenska_posta_display_insurance", $slovenska_posta_display_insurance, $text_yes, $text_no) ?>
            <?php $createRadioButton($entry_max_insurance, $help_max_insurance, "slovenska_posta_allow_max_insurance", $slovenska_posta_allow_max_insurance, $text_yes, $text_no) ?>
            <?php $createRadioButton($entry_disable_insurance, $help_disable_insurance, "slovenska_posta_disable_insurance", $slovenska_posta_disable_insurance, $text_yes, $text_no) ?>

            <div class="form-group">
                <label class="col-sm-2 control-label"
                       for="input-weight-class"><?php echo $entry_weight_class; ?></label>

                <div class="col-sm-10">
                    <select name="slovenska_posta_weight_class_id" id="input-weight-class" class="form-control">
                        <?php foreach ($weight_classes as $weight_class) { ?>
                        <?php if ($weight_class['weight_class_id'] == $slovenska_posta_weight_class_id) { ?>
                        <option value="<?php echo $weight_class['weight_class_id']; ?>"
                                selected="selected"><?php echo $weight_class['title']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"
                       for="input-tax-class"><?php echo $entry_tax_class; ?></label>

                <div class="col-sm-10">
                    <select name="slovenska_posta_tax_class_id" id="input-tax-class" class="form-control">
                        <option value="0"><?php echo $text_none; ?></option>
                        <?php foreach ($tax_classes as $tax_class) { ?>
                        <?php if ($tax_class['tax_class_id'] == $slovenska_posta_tax_class_id) { ?>
                        <option value="<?php echo $tax_class['tax_class_id']; ?>"
                                selected="selected"><?php echo $tax_class['title']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label"
                       for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>

                <div class="col-sm-10">
                    <select name="slovenska_posta_geo_zone_id" id="input-geo-zone" class="form-control">
                        <option value="0"><?php echo $text_all_zones; ?></option>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                        <?php if ($geo_zone['geo_zone_id'] == $slovenska_posta_geo_zone_id) { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                selected="selected"><?php echo $geo_zone['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php $createStatus($entry_status, $slovenska_posta_status, 'slovenska_posta_status', 'input-status', $text_enabled_sp, $text_disabled_sp) ?>

            <div class="form-group">
                <label class="col-sm-2 control-label"
                       for="input-sort-order"><?php echo $entry_sort_order; ?></label>

                <div class="col-sm-10">
                    <input type="text" name="slovenska_posta_sort_order"
                           value="<?php echo $slovenska_posta_sort_order; ?>"
                           placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order"
                           class="form-control"/>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="tab-pane" id="slovensko">

    <div class="col-sm-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#tab-sk-general" data-toggle="tab"><?php echo $text_tab_general; ?></a></li>
            <li><a href="#tab-sk-free" data-toggle="tab"><?php echo $text_tab_free; ?></a></li>
            <li><a href="#tab-list" data-toggle="tab"><?php echo $text_tab_list; ?></a></li>
            <li><a href="#tab-dop-list" data-toggle="tab"><?php echo $text_tab_dop_list; ?></a></li>
            <li><a href="#tab-poistenie-list" data-toggle="tab"><?php echo $text_tab_poistenie_list; ?></a></li>
            <li><a href="#tab-balik-posta" data-toggle="tab"><?php echo $text_tab_balik_posta; ?></a></li>
            <li><a href="#tab-easy" data-toggle="tab"><?php echo $text_tab_easy; ?></a></li>
            <li><a href="#tab-kurier" data-toggle="tab"><?php echo $text_tab_kurier; ?></a></li>
        </ul>
    </div>

    <div class="col-sm-10">

        <div class="tab-content">
            <div class="tab-pane active" id="tab-sk-general">
                <?php $createStatus($entry_status, $slovenska_posta_sk_status, 'slovenska_posta_sk_status', 'input-sk-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-sk-free">
                <?php $createTextField($entry_free_cena, $help_free_delivery, 'slovenska_posta_sk_free_cena', $slovenska_posta_sk_free_cena, 'input-sk-free', 'sk_free') ?>
                <?php $createTextField($entry_free_vaha, $help_free_delivery_vaha, 'slovenska_posta_sk_free_vaha', $slovenska_posta_sk_free_vaha, 'input-sk-free-vaha', 'sk_free_vaha') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_sk_free_dobierka_status", $slovenska_posta_sk_free_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_sk_free_dobierka_cena', $slovenska_posta_sk_free_dobierka_cena, 'input-sk-free-dobierka', 'sk_free_dobierka') ?>
                <?php $createStatus($entry_free_status, $slovenska_posta_sk_free_status, 'slovenska_posta_sk_free_status', 'input-sk-free-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-list">
                <?php $createRadioButton($entry_sk_list_1_status, false, "slovenska_posta_sk_list_1_status", $slovenska_posta_sk_list_1_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_list_1_trieda_sadzba, $help_rate, 'slovenska_posta_sk_list_1_sadzba', $slovenska_posta_sk_list_1_sadzba, 'input-sk-list-1-trieda', 'sk_list_1') ?>
                <?php $createRadioButton($entry_sk_list_2_status, false, "slovenska_posta_sk_list_2_status", $slovenska_posta_sk_list_2_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_list_2_trieda_sadzba, $help_rate, 'slovenska_posta_sk_list_2_sadzba', $slovenska_posta_sk_list_2_sadzba, 'input-sk-list-2-trieda', 'sk_list_2') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_sk_list_box_vaha', $slovenska_posta_sk_list_box_vaha, 'input-sk-list-box-vaha', 'sk_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_sk_list_status, 'slovenska_posta_sk_list_status', 'input-list', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-dop-list">
                <?php $createRadioButton($entry_sk_list_1_status, false, "slovenska_posta_sk_dop_list_1_status", $slovenska_posta_sk_dop_list_1_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_list_1_trieda_sadzba, $help_rate, 'slovenska_posta_sk_dop_list_1_sadzba', $slovenska_posta_sk_dop_list_1_sadzba, 'input-sk-dop-list-1-trieda', 'sk_dop_list_1') ?>
                <?php $createRadioButton($entry_sk_list_2_status, false, "slovenska_posta_sk_dop_list_2_status", $slovenska_posta_sk_dop_list_2_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_list_2_trieda_sadzba, $help_rate, 'slovenska_posta_sk_dop_list_2_sadzba', $slovenska_posta_sk_dop_list_2_sadzba, 'input-sk-dop-list-2-trieda', 'sk_dop_list_2') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_sk_dop_list_dobierka_status", $slovenska_posta_sk_dop_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_sk_dop_list_dobierka_cena', $slovenska_posta_sk_dop_list_dobierka_cena, 'input-sk-dop-list-dobierka', 'sk_dop_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_sk_dop_list_dorucenka_status", $slovenska_posta_sk_dop_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_sk_dop_list_dorucenka_cena', $slovenska_posta_sk_dop_list_dorucenka_cena, 'input-sk-dop-list-dorucenka', 'sk_dop_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_sk_dop_list_do_ruk_status", $slovenska_posta_sk_dop_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_sk_dop_list_do_ruk_cena', $slovenska_posta_sk_dop_list_do_ruk_cena, 'input-sk-dop-list-do-ruk', 'sk_dop_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_sk_dop_list_box_vaha', $slovenska_posta_sk_dop_list_box_vaha, 'input-sk-dop-list-box-vaha', 'sk_dop_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_sk_dop_list_status, 'slovenska_posta_sk_dop_list_status', 'input-list-doporuceny', $text_enabled_sp, $text_disabled_sp) ?>
            </div>


            <div class="tab-pane" id="tab-poistenie-list">
                <?php $createRadioButton($entry_sk_list_1_status, false, "slovenska_posta_sk_poistenie_list_1_status", $slovenska_posta_sk_poistenie_list_1_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_poistenie_list_1_30_sadzba, $help_rate, 'slovenska_posta_sk_poistenie_list_1_sadzba', $slovenska_posta_sk_poistenie_list_1_sadzba, 'input-sk-poistenie-list-1-trieda', 'sk_ist_list_1') ?>
                <?php $createRadioButton($entry_sk_list_2_status, false, "slovenska_posta_sk_poistenie_list_2_status", $slovenska_posta_sk_poistenie_list_2_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_poistenie_list_2_30_sadzba, $help_rate, 'slovenska_posta_sk_poistenie_list_2_sadzba', $slovenska_posta_sk_poistenie_list_2_sadzba, 'input-sk-poistenie-list-2-trieda', 'sk_ist_list_2') ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_sk_poistenie_list_poistenie_sadzba', $slovenska_posta_sk_poistenie_list_poistenie_sadzba, 'input-sk-poistenie-list-1-poistenie-sadzba', 'sk_ist_list_sadzba') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_sk_poistenie_list_dobierka_status", $slovenska_posta_sk_poistenie_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_sk_poistenie_list_dobierka_cena', $slovenska_posta_sk_poistenie_list_dobierka_cena, 'input-sk-poistenie-list-dobierka', 'sk_ist_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_sk_poistenie_list_dorucenka_status", $slovenska_posta_sk_poistenie_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_sk_poistenie_list_dorucenka_cena', $slovenska_posta_sk_poistenie_list_dorucenka_cena, 'input-sk-poistenie-list-dorucenka', 'sk_ist_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_sk_poistenie_list_do_ruk_status", $slovenska_posta_sk_poistenie_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_sk_poistenie_list_do_ruk_cena', $slovenska_posta_sk_poistenie_list_do_ruk_cena, 'input-sk-poistenie-list-do-ruk', 'sk_ist_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_sk_poistenie_list_box_vaha', $slovenska_posta_sk_poistenie_list_box_vaha, 'input-sk-poistenie-list-box-vaha', 'sk_ist_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_sk_poistenie_list_status, 'slovenska_posta_sk_poistenie_list_status', 'input-list-poistenie', $text_enabled_sp, $text_disabled_sp) ?>
            </div>


            <div class="tab-pane" id="tab-balik-posta">
                <?php $createRadioButton($entry_sk_balik_posta_status, false, "slovenska_posta_sk_balik_posta_status", $slovenska_posta_sk_balik_posta_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_balik_posta_cena, $help_rate, 'slovenska_posta_sk_balik_posta_sadzba', $slovenska_posta_sk_balik_posta_sadzba, 'input-sk-balik-posta-sadzba', 'sk_balik_posta') ?>
                <?php $createRadioButton($entry_sk_balik_adresa_status, false, "slovenska_posta_sk_balik_adresa_status", $slovenska_posta_sk_balik_adresa_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_balik_adresa_cena, $help_rate, 'slovenska_posta_sk_balik_adresa_sadzba', $slovenska_posta_sk_balik_adresa_sadzba, 'input-sk-balik-adresa-sadzba', 'sk_balik_adresa') ?>
                <?php $createRadioButton($entry_sk_balik_poistenie_status, false, "slovenska_posta_sk_balik_poistenie_status", $slovenska_posta_sk_balik_poistenie_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_sk_balik_poistenie_sadzba', $slovenska_posta_sk_balik_poistenie_sadzba, 'input-sk-balik-poistenie-sadzba', 'sk_balik_ist') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_sk_balik_dobierka_status", $slovenska_posta_sk_balik_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_sk_balik_dobierka_cena', $slovenska_posta_sk_balik_dobierka_cena, 'input-sk-balik-dobierka', 'sk_balik_dobierka') ?>
                <?php $createRadioButton($entry_krehke, false, "slovenska_posta_sk_balik_krehke_status", $slovenska_posta_sk_balik_krehke_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_krehke_cena, false, 'slovenska_posta_sk_balik_krehke_cena', $slovenska_posta_sk_balik_krehke_cena, 'input-sk-balik-krehke', 'sk_balik_krehke') ?>
                <?php $createRadioButton($entry_neskladne, false, "slovenska_posta_sk_balik_neskladne_status", $slovenska_posta_sk_balik_neskladne_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_neskladne_cena, false, 'slovenska_posta_sk_balik_neskladne_cena', $slovenska_posta_sk_balik_neskladne_cena, 'input-sk-balik-neskladne', 'sk_balik_neskladne') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_sk_balik_box_vaha', $slovenska_posta_sk_balik_box_vaha, 'input-sk-balik-box-vaha', 'sk_balik_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_sk_balik_status, 'slovenska_posta_sk_balik_status', 'input-sk-balik-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>


            <div class="tab-pane" id="tab-easy">
                <?php $createRadioButton($entry_sk_easy_plast_status, false, "slovenska_posta_sk_easy_plast_status", $slovenska_posta_sk_easy_plast_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_easy_plast_sadzba, $help_rate, 'slovenska_posta_sk_easy_plast_sadzba', $slovenska_posta_sk_easy_plast_sadzba, 'input-sk-easy-plast', 'sk_easy_plast') ?>
                <?php $createRadioButton($entry_sk_easy_karton_status, false, "slovenska_posta_sk_easy_karton_status", $slovenska_posta_sk_easy_karton_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_easy_karton_sadzba, $help_rate, 'slovenska_posta_sk_easy_karton_sadzba', $slovenska_posta_sk_easy_karton_sadzba, 'input-sk-easy-karton', 'sk_easy_karton') ?>
                <?php $createRadioButton($entry_sk_balik_poistenie_status, false, "slovenska_posta_sk_easy_poistenie_status", $slovenska_posta_sk_easy_poistenie_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_sk_easy_poistenie_sadzba', $slovenska_posta_sk_easy_poistenie_sadzba, 'input-sk-easy-poistenie-sadzba', 'sk_easy_ist') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_sk_easy_dobierka_status", $slovenska_posta_sk_easy_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_sk_easy_dobierka_cena', $slovenska_posta_sk_easy_dobierka_cena, 'input-sk-easy-dobierka', 'sk_easy_dobierka') ?>


                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_sk_easy_doruk_status", $slovenska_posta_sk_easy_doruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_sk_easy_doruk_cena', $slovenska_posta_sk_easy_doruk_cena, 'input-sk-easy-do-ruk', 'sk_easy_do_ruk') ?>

                <?php $createRadioButton($entry_dorucenie_info, false, "slovenska_posta_sk_easy_dorucenie_info_status", $slovenska_posta_sk_easy_dorucenie_info_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenie_info_cena, false, 'slovenska_posta_sk_easy_dorucenie_info_cena', $slovenska_posta_sk_easy_dorucenie_info_cena, 'input-sk-easy-info-dorucenie', 'sk_easy_info') ?>


                <div class="form-group">
                    <label class="col-sm-2 control-label"
                           for="input-delivery-class-easy"><?php echo $entry_delivery_time; ?></label>

                    <div class="col-sm-10">
                        <select name="slovenska_posta_sk_easy_delivery_time_id" id="input-delivery-class-easy"
                                class="form-control">
                            <?php foreach ($delivery_classes_easy as $delivery_class) { ?>
                            <?php if ($delivery_class['delivery_class_id'] == $slovenska_posta_sk_easy_delivery_time_id) { ?>
                            <option value="<?php echo $delivery_class['delivery_class_id']; ?>"
                                    selected="selected"><?php echo $delivery_class['title']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $delivery_class['delivery_class_id']; ?>"><?php echo $delivery_class['title']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php $createTextField($entry_do_desiatej_cena, false, 'slovenska_posta_sk_easy_dodesiatej_cena', $slovenska_posta_sk_easy_dodesiatej_cena, 'input-sk-easy-dodesiatej', 'sk_easy_10') ?>
                <?php $createTextField($entry_doruciť_sobota_cena, false, 'slovenska_posta_sk_easy_sobota_cena', $slovenska_posta_sk_easy_sobota_cena, 'input-sk-easy-sobota', 'sk_easy_sobota') ?>

                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_sk_easy_box_vaha', $slovenska_posta_sk_easy_box_vaha, 'input-sk-easy-box-vaha', 'sk_easy_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_sk_easy_status, 'slovenska_posta_sk_easy_status', 'input-easy', $text_enabled_sp, $text_disabled_sp) ?>

            </div>


            <div class="tab-pane" id="tab-kurier">
                <?php $createRadioButton($entry_sk_kurier_posta_status, false, "slovenska_posta_sk_kurier_posta_status", $slovenska_posta_sk_kurier_posta_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_kurier_posta_sadzba, false, 'slovenska_posta_sk_kurier_posta_sadzba', $slovenska_posta_sk_kurier_posta_sadzba, 'input-sk-kurier-posta-sadzba', 'sk_kurier_posta') ?>
                <?php $createRadioButton($entry_sk_kurier_adresa_status, false, "slovenska_posta_sk_kurier_adresa_status", $slovenska_posta_sk_kurier_adresa_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_sk_kurier_adresa_cena, false, 'slovenska_posta_sk_kurier_adresa_cena', $slovenska_posta_sk_kurier_adresa_cena, 'input-sk-kurier-adresa-cena', 'sk_kurier_adresa') ?>

                <?php $createRadioButton($entry_sk_kurier_podaj_kurier_status, false, "slovenska_posta_sk_kurier_podaj_kurier_status", $slovenska_posta_sk_kurier_podaj_kurier_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_kurier_podaj_kurier_sadzba, false, 'slovenska_posta_sk_kurier_podaj_kurier_sadzba', $slovenska_posta_sk_kurier_podaj_kurier_sadzba, 'input-sk-kurier-podaj-kurier-sadzba', 'sk_kurier_podaj') ?>


                <?php $createRadioButton($entry_sk_balik_poistenie_status, false, "slovenska_posta_sk_kurier_poistenie_status", $slovenska_posta_sk_kurier_poistenie_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_sk_kurier_poistenie_sadzba', $slovenska_posta_sk_kurier_poistenie_sadzba, 'input-sk-kurier-poistenie', 'sk_kurier_ist') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_sk_kurier_dobierka_status", $slovenska_posta_sk_kurier_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_sk_kurier_dobierka_cena', $slovenska_posta_sk_kurier_dobierka_cena, 'input-sk-easy-dobierka', 'sk_kurier_dobierka') ?>
                <?php $createRadioButton($entry_krehke, false, "slovenska_posta_sk_kurier_krehke_status", $slovenska_posta_sk_kurier_krehke_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_krehke_cena, false, 'slovenska_posta_sk_kurier_krehke_cena', $slovenska_posta_sk_kurier_krehke_cena, 'input-sk-kurier-krehke', 'sk_kurier_krehke') ?>
                <?php $createRadioButton($entry_neskladne, false, "slovenska_posta_sk_kurier_neskladne_status", $slovenska_posta_sk_kurier_neskladne_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_neskladne_cena, false, 'slovenska_posta_sk_kurier_neskladne_cena', $slovenska_posta_sk_kurier_neskladne_cena, 'input-sk-kurier-neskladne', 'sk_kurier_neskladne') ?>
                <?php $createRadioButton($entry_dorucenie_info, false, "slovenska_posta_sk_kurier_dorucenie_info_status", $slovenska_posta_sk_kurier_dorucenie_info_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenie_info_cena, false, 'slovenska_posta_sk_kurier_dorucenie_info_cena', $slovenska_posta_sk_kurier_dorucenie_info_cena, 'input-sk-kurier-dorucenie-info', 'sk_kurier_info') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_sk_kurier_doruk_status", $slovenska_posta_sk_kurier_doruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_sk_kurier_doruk_cena', $slovenska_posta_sk_kurier_doruk_cena, 'input-sk-kurier-doruk', 'sk_kurier_do_ruk') ?>


                <div class="form-group">
                    <label class="col-sm-2 control-label"
                           for="input-delivery-class"><?php echo $entry_delivery_time; ?></label>

                    <div class="col-sm-10">
                        <select name="slovenska_posta_sk_kurier_delivery_time_id" id="input-delivery-class"
                                class="form-control">
                            <?php foreach ($delivery_classes as $delivery_class) { ?>
                            <?php if ($delivery_class['delivery_class_id'] == $slovenska_posta_sk_kurier_delivery_time_id) { ?>
                            <option value="<?php echo $delivery_class['delivery_class_id']; ?>"
                                    selected="selected"><?php echo $delivery_class['title']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $delivery_class['delivery_class_id']; ?>"><?php echo $delivery_class['title']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php $createTextField($entry_sk_kurier_vdenpodania_cena, false, 'slovenska_posta_sk_kurier_denpodania_cena', $slovenska_posta_sk_kurier_denpodania_cena, 'input-sk-kurier-denpodania', 'sk_kurier_den_podania') ?>
                <?php $createTextField($entry_do_desiatej_cena, false, 'slovenska_posta_sk_kurier_do10_cena', $slovenska_posta_sk_kurier_do10_cena, 'input-sk-kurier-do10', 'sk_kurier_10') ?>
                <?php $createTextField($entry_do_strnastej_cena, false, 'slovenska_posta_sk_kurier_do14_cena', $slovenska_posta_sk_kurier_do14_cena, 'input-sk-kurier-do14', 'sk_kurier_14') ?>
                <?php $createTextField($entry_doruciť_sobota_cena, false, 'slovenska_posta_sk_kurier_sobota_cena', $slovenska_posta_sk_kurier_sobota_cena, 'input-sk-kurier-sobota', 'sk_kurier_sobota') ?>

                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_sk_kurier_box_vaha', $slovenska_posta_sk_kurier_box_vaha, 'input-sk-kurier-box-vaha', 'sk_kurier_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_sk_kurier_status, 'slovenska_posta_sk_kurier_status', 'input-kurier', $text_enabled_sp, $text_disabled_sp) ?>

            </div>
        </div>
    </div>

</div>

<div class="tab-pane" id="cesko">
    <div class="col-sm-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#tab-cz-general" data-toggle="tab"><?php echo $text_tab_general; ?></a></li>
            <li><a href="#tab-cz-free" data-toggle="tab"><?php echo $text_tab_free; ?></a></li>
            <li><a href="#tab-cz-list" data-toggle="tab"><?php echo $text_tab_list; ?></a></li>
            <li><a href="#tab-cz-dop-list" data-toggle="tab"><?php echo $text_tab_dop_list; ?></a></li>
            <li><a href="#tab-cz-poistenie-list" data-toggle="tab"><?php echo $text_tab_poistenie_list; ?></a></li>
            <li><a href="#tab-cz-balik-posta" data-toggle="tab"><?php echo $text_tab_balik_posta; ?></a></li>
            <li><a href="#tab-cz-epg" data-toggle="tab"><?php echo $text_tab_epg; ?></a></li>
            <li><a href="#tab-cz-ems" data-toggle="tab"><?php echo $text_tab_ems; ?></a></li>

        </ul>
    </div>

    <div class="col-sm-10">

        <div class="tab-content">

            <div class="tab-pane active" id="tab-cz-general">
                <?php $createStatus($entry_status, $slovenska_posta_cz_status, 'slovenska_posta_cz_status', 'input-cz-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-cz-free">
                <?php $createTextField($entry_free_cena, $help_free_delivery, 'slovenska_posta_cz_free_cena', $slovenska_posta_cz_free_cena, 'input-cz-free', 'cz_free') ?>
                <?php $createTextField($entry_free_vaha, $help_free_delivery_vaha, 'slovenska_posta_cz_free_vaha', $slovenska_posta_cz_free_vaha, 'input-cz-free-vaha', 'cz_free_vaha') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_cz_free_dobierka_status", $slovenska_posta_cz_free_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_cz_free_dobierka_cena', $slovenska_posta_cz_free_dobierka_cena, 'input-cz-free-dobierka', 'cz_free_dobierka') ?>
                <?php $createStatus($entry_free_status, $slovenska_posta_cz_free_status, 'slovenska_posta_cz_free_status', 'input-cz-free-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-cz-list">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_cz_list_1_sadzba', $slovenska_posta_cz_list_1_sadzba, 'input-cz-list-1-trieda', 'cz_list_1') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_cz_list_box_vaha', $slovenska_posta_cz_list_box_vaha, 'input-cz-list-box-vaha', 'cz_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_cz_list_status, 'slovenska_posta_cz_list_status', 'input-cz-list', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-cz-dop-list">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_cz_dop_list_sadzba', $slovenska_posta_cz_dop_list_sadzba, 'input-cz-dop-list-1-trieda', 'cz_dop_list') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_cz_dop_list_dobierka_status", $slovenska_posta_cz_dop_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_cz_dop_list_dobierka_cena', $slovenska_posta_cz_dop_list_dobierka_cena, 'input-cz-dop-list-dobierka', 'cz_dop_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_cz_dop_list_dorucenka_status", $slovenska_posta_cz_dop_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_cz_dop_list_dorucenka_cena', $slovenska_posta_cz_dop_list_dorucenka_cena, 'input-cz-dop-list-dorucenka', 'cz_dop_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_cz_dop_list_do_ruk_status", $slovenska_posta_cz_dop_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_cz_dop_list_do_ruk_cena', $slovenska_posta_cz_dop_list_do_ruk_cena, 'input-cz-dop-list-do-ruk', 'cz_dop_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_cz_dop_list_box_vaha', $slovenska_posta_cz_dop_list_box_vaha, 'input-cz-dop-list-box-vaha', 'cz_dop_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_cz_dop_list_status, 'slovenska_posta_cz_dop_list_status', 'input-cz-list-doporuceny', $text_enabled_sp, $text_disabled_sp) ?>
            </div>


            <div class="tab-pane" id="tab-cz-poistenie-list">
                <?php $createTextareaField($entry_cz_poistenie_list_1_100_sadzba, $help_rate, 'slovenska_posta_cz_poistenie_list_1_100_sadzba', $slovenska_posta_cz_poistenie_list_1_100_sadzba, 'input-cz-poistenie-list-1-100-trieda', 'cz_ist_list_1_100') ?>
                <?php $createTextareaField($entry_cz_poistenie_list_1_500_sadzba, $help_rate, 'slovenska_posta_cz_poistenie_list_1_500_sadzba', $slovenska_posta_cz_poistenie_list_1_500_sadzba, 'input-cz-poistenie-list-1-500-trieda', 'cz_ist_list_1_500') ?>
                <?php $createTextareaField($entry_cz_poistenie_list_1_1000_sadzba, $help_rate, 'slovenska_posta_cz_poistenie_list_1_1000_sadzba', $slovenska_posta_cz_poistenie_list_1_1000_sadzba, 'input-cz-poistenie-list-1-1000-trieda', 'cz_ist_list_1_1000') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_cz_poistenie_list_dobierka_status", $slovenska_posta_cz_poistenie_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_cz_poistenie_list_dobierka_cena', $slovenska_posta_cz_poistenie_list_dobierka_cena, 'input-cz-poistenie-list-dobierka', 'cz_ist_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_cz_poistenie_list_dorucenka_status", $slovenska_posta_cz_poistenie_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_cz_poistenie_list_dorucenka_cena', $slovenska_posta_cz_poistenie_list_dorucenka_cena, 'input-cz-poistenie-list-dorucenka', 'cz_ist_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_cz_poistenie_list_do_ruk_status", $slovenska_posta_cz_poistenie_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_cz_poistenie_list_do_ruk_cena', $slovenska_posta_cz_poistenie_list_do_ruk_cena, 'input-cz-poistenie-list-do-ruk', 'cz_ist_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_cz_poistenie_list_box_vaha', $slovenska_posta_cz_poistenie_list_box_vaha, 'input-cz-poistenie-list-box-vaha', 'cz_ist_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_cz_poistenie_list_status, 'slovenska_posta_cz_poistenie_list_status', 'input-cz-list-poistenie', $text_enabled_sp, $text_disabled_sp) ?>
            </div>


            <div class="tab-pane" id="tab-cz-balik-posta">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_cz_balik_1_sadzba', $slovenska_posta_cz_balik_1_sadzba, 'input-cz-balik-1-sadzba', 'cz_balik_1') ?>
                <?php $createRadioButton($entry_sk_balik_poistenie_status, false, "slovenska_posta_cz_balik_poistenie_status", $slovenska_posta_cz_balik_poistenie_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_cz_balik_poistenie_sadzba', $slovenska_posta_cz_balik_poistenie_sadzba, 'input-cz-balik-poistenie-sadzba', 'cz_balik_ist') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_cz_balik_dobierka_status", $slovenska_posta_cz_balik_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_cz_balik_dobierka_cena', $slovenska_posta_cz_balik_dobierka_cena, 'input-cz-balik-dobierka', 'cz_balik_dobierka') ?>
                <?php $createRadioButton($entry_krehke, false, "slovenska_posta_cz_balik_krehke_status", $slovenska_posta_cz_balik_krehke_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_krehke_cena, false, 'slovenska_posta_cz_balik_krehke_cena', $slovenska_posta_cz_balik_krehke_cena, 'input-cz-balik-krehke', 'cz_balik_krehke') ?>
                <?php $createRadioButton($entry_neskladne, false, "slovenska_posta_cz_balik_neskladne_status", $slovenska_posta_cz_balik_neskladne_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_neskladne_cena, false, 'slovenska_posta_cz_balik_neskladne_cena', $slovenska_posta_cz_balik_neskladne_cena, 'input-cz-balik-neskladne', 'cz_balik_neskladne') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_cz_balik_box_vaha', $slovenska_posta_cz_balik_box_vaha, 'input-cz-balik-box-vaha', 'cz_balik_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_cz_balik_status, 'slovenska_posta_cz_balik_status', 'input-cz-balik-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-cz-epg">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_cz_epg_sadzba', $slovenska_posta_cz_epg_sadzba, 'input-cz-epg', 'cz_epg') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_cz_epg_box_vaha', $slovenska_posta_cz_epg_box_vaha, 'input-cz-epg-box-vaha', 'cz_epg_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_cz_epg_status, 'slovenska_posta_cz_epg_status', 'input-cz-epg-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-cz-ems">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_cz_ems_sadzba', $slovenska_posta_cz_ems_sadzba, 'input-cz-ems', 'cz_ems') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_cz_ems_box_vaha', $slovenska_posta_cz_ems_box_vaha, 'input-cz-ems-box-vaha', 'cz_ems_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_cz_ems_status, 'slovenska_posta_cz_ems_status', 'input-cz-ems-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane" id="eu">
    <div class="col-sm-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#tab-eu-general" data-toggle="tab"><?php echo $text_tab_general; ?></a></li>
            <li><a href="#tab-eu-free" data-toggle="tab"><?php echo $text_tab_free; ?></a></li>
            <li><a href="#tab-eu-list" data-toggle="tab"><?php echo $text_tab_list; ?></a></li>
            <li><a href="#tab-eu-dop-list" data-toggle="tab"><?php echo $text_tab_dop_list; ?></a></li>
            <li><a href="#tab-eu-poistenie-list" data-toggle="tab"><?php echo $text_tab_poistenie_list; ?></a></li>
            <li><a href="#tab-eu-balik-posta" data-toggle="tab"><?php echo $text_tab_balik_posta; ?></a></li>
            <li><a href="#tab-eu-epg" data-toggle="tab"><?php echo $text_tab_epg; ?></a></li>
            <li><a href="#tab-eu-ems" data-toggle="tab"><?php echo $text_tab_ems; ?></a></li>

        </ul>
    </div>

    <div class="col-sm-10">

        <div class="tab-content">

            <div class="tab-pane active" id="tab-eu-general">
                <?php $createStatus($entry_status, $slovenska_posta_eu_status, 'slovenska_posta_eu_status', 'input-eu-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-free">
                <?php $createTextField($entry_free_cena, $help_free_delivery, 'slovenska_posta_eu_free_cena', $slovenska_posta_eu_free_cena, 'input-eu-free', 'eu_free') ?>
                <?php $createTextField($entry_free_vaha, $help_free_delivery_vaha, 'slovenska_posta_eu_free_vaha', $slovenska_posta_eu_free_vaha, 'input-eu-free-vaha', 'eu_free_vaha') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_eu_free_dobierka_status", $slovenska_posta_eu_free_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_eu_free_dobierka_cena', $slovenska_posta_eu_free_dobierka_cena, 'input-eu-free-dobierka', 'eu_free_dobierka') ?>
                <?php $createStatus($entry_free_status, $slovenska_posta_eu_free_status, 'slovenska_posta_eu_free_status', 'input-eu-free-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-list">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_eu_list_1_sadzba', $slovenska_posta_eu_list_1_sadzba, 'input-eu-list-1-trieda', 'eu_list_1') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_eu_list_box_vaha', $slovenska_posta_eu_list_box_vaha, 'input-eu-list-box-vaha', 'eu_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_eu_list_status, 'slovenska_posta_eu_list_status', 'input-eu-list', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-dop-list">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_eu_dop_list_sadzba', $slovenska_posta_eu_dop_list_sadzba, 'input-eu-dop-list-1-trieda', 'eu_dop_list') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_eu_dop_list_dobierka_status", $slovenska_posta_eu_dop_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_eu_dop_list_dobierka_cena', $slovenska_posta_eu_dop_list_dobierka_cena, 'input-eu-dop-list-dobierka', 'eu_dop_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_eu_dop_list_dorucenka_status", $slovenska_posta_eu_dop_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_eu_dop_list_dorucenka_cena', $slovenska_posta_eu_dop_list_dorucenka_cena, 'input-eu-dop-list-dorucenka' , 'eu_dop_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_eu_dop_list_do_ruk_status", $slovenska_posta_eu_dop_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_eu_dop_list_do_ruk_cena', $slovenska_posta_eu_dop_list_do_ruk_cena, 'input-eu-dop-list-do-ruk', 'eu_dop_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_eu_dop_list_box_vaha', $slovenska_posta_eu_dop_list_box_vaha, 'input-eu-dop-list-box-vaha' , 'eu_dop_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_eu_dop_list_status, 'slovenska_posta_eu_dop_list_status', 'input-eu-list-doporuceny', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-poistenie-list">
                <?php $createTextareaField($entry_cz_poistenie_list_1_100_sadzba, $help_rate, 'slovenska_posta_eu_poistenie_list_1_100_sadzba', $slovenska_posta_eu_poistenie_list_1_100_sadzba, 'input-eu-poistenie-list-1-100-trieda', 'eu_ist_list_1_100') ?>
                <?php $createTextareaField($entry_cz_poistenie_list_1_500_sadzba, $help_rate, 'slovenska_posta_eu_poistenie_list_1_500_sadzba', $slovenska_posta_eu_poistenie_list_1_500_sadzba, 'input-eu-poistenie-list-1-500-trieda', 'eu_ist_list_1_500') ?>
                <?php $createTextareaField($entry_cz_poistenie_list_1_1000_sadzba, $help_rate, 'slovenska_posta_eu_poistenie_list_1_1000_sadzba', $slovenska_posta_eu_poistenie_list_1_1000_sadzba, 'input-eu-poistenie-list-1-1000-trieda', 'eu_ist_list_1_1000') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_eu_poistenie_list_dobierka_status", $slovenska_posta_eu_poistenie_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_eu_poistenie_list_dobierka_cena', $slovenska_posta_eu_poistenie_list_dobierka_cena, 'input-eu-poistenie-list-dobierka', 'eu_ist_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_eu_poistenie_list_dorucenka_status", $slovenska_posta_eu_poistenie_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_eu_poistenie_list_dorucenka_cena', $slovenska_posta_eu_poistenie_list_dorucenka_cena, 'input-eu-poistenie-list-dorucenka', 'eu_ist_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_eu_poistenie_list_do_ruk_status", $slovenska_posta_eu_poistenie_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_eu_poistenie_list_do_ruk_cena', $slovenska_posta_eu_poistenie_list_do_ruk_cena, 'input-eu-poistenie-list-do-ruk', 'eu_ist_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_eu_poistenie_list_box_vaha', $slovenska_posta_eu_poistenie_list_box_vaha, 'input-eu-poistenie-list-box-vaha', 'eu_ist_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_eu_poistenie_list_status, 'slovenska_posta_eu_poistenie_list_status', 'input-eu-list-poistenie', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-balik-posta">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_eu_balik_1_sadzba', $slovenska_posta_eu_balik_1_sadzba, 'input-eu-balik-1-sadzba' , 'eu_balik_1') ?>
                <?php $createRadioButton($entry_sk_balik_poistenie_status, false, "slovenska_posta_eu_balik_poistenie_status", $slovenska_posta_eu_balik_poistenie_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_eu_balik_poistenie_sadzba', $slovenska_posta_eu_balik_poistenie_sadzba, 'input-eu-balik-poistenie-sadzba', 'eu_balik_ist') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_eu_balik_dobierka_status", $slovenska_posta_eu_balik_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_eu_balik_dobierka_cena', $slovenska_posta_eu_balik_dobierka_cena, 'input-eu-balik-dobierka', 'eu_balik_dobierka') ?>
                <?php $createRadioButton($entry_krehke, false, "slovenska_posta_eu_balik_krehke_status", $slovenska_posta_eu_balik_krehke_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_krehke_cena, false, 'slovenska_posta_eu_balik_krehke_cena', $slovenska_posta_eu_balik_krehke_cena, 'input-eu-balik-krehke', 'eu_balik_krehke') ?>
                <?php $createRadioButton($entry_neskladne, false, "slovenska_posta_eu_balik_neskladne_status", $slovenska_posta_eu_balik_neskladne_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_neskladne_cena, false, 'slovenska_posta_eu_balik_neskladne_cena', $slovenska_posta_eu_balik_neskladne_cena, 'input-eu-balik-neskladne', 'eu_balik_neskladne') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_eu_balik_box_vaha', $slovenska_posta_eu_balik_box_vaha, 'input-eu-balik-box-vaha', 'eu_balik_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_eu_balik_status, 'slovenska_posta_eu_balik_status', 'input-eu-balik-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-epg">
                <?php $createTextareaField($entry_rate_tar_eu1, $help_rate, 'slovenska_posta_eu_epg_1_eu_sadzba', $slovenska_posta_eu_epg_1_eu_sadzba, 'input-eu-epg-1-eu', 'eu_epg_1_eu') ?>
                <?php $createTextareaField($entry_rate_tar_eu2, $help_rate, 'slovenska_posta_eu_epg_2_eu_sadzba', $slovenska_posta_eu_epg_2_eu_sadzba, 'input-eu-epg-2-eu', 'eu_epg_2_eu') ?>
                <?php $createTextareaField($entry_rate_tar_2, $help_rate, 'slovenska_posta_eu_epg_2_sadzba', $slovenska_posta_eu_epg_2_sadzba, 'input-eu-epg-2', 'eu_epg_2') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_eu_epg_box_vaha', $slovenska_posta_eu_epg_box_vaha, 'input-eu-epg-box-vaha', 'eu_epg_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_eu_epg_status, 'slovenska_posta_eu_epg_status', 'input-eu-epg-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-eu-ems">
                <?php $createTextareaField($entry_rate_tar_eu0, $help_rate, 'slovenska_posta_eu_ems_0_eu_sadzba', $slovenska_posta_eu_ems_0_eu_sadzba, 'input-eu-ems-0-eu', 'eu_ems_0_eu') ?>
                <?php $createTextareaField($entry_rate_tar_eu1, $help_rate, 'slovenska_posta_eu_ems_1_eu_sadzba', $slovenska_posta_eu_ems_1_eu_sadzba, 'input-eu-ems-1-eu', 'eu_ems_1_eu') ?>
                <?php $createTextareaField($entry_rate_tar_eu2, $help_rate, 'slovenska_posta_eu_ems_2_eu_sadzba', $slovenska_posta_eu_ems_2_eu_sadzba, 'input-eu-ems-2-eu', 'eu_ems_2_eu') ?>
                <?php $createTextareaField($entry_rate_tar_1, $help_rate, 'slovenska_posta_eu_ems_1_sadzba', $slovenska_posta_eu_ems_1_sadzba, 'input-eu-ems-1', 'eu_ems_1') ?>
                <?php $createTextareaField($entry_rate_tar_2, $help_rate, 'slovenska_posta_eu_ems_2_sadzba', $slovenska_posta_eu_ems_2_sadzba, 'input-eu-ems-2', 'eu_ems_2') ?>
                <?php $createTextareaField($entry_rate_tar_3, $help_rate, 'slovenska_posta_eu_ems_3_sadzba', $slovenska_posta_eu_ems_3_sadzba, 'input-eu-ems-3', 'eu_ems_3') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_eu_ems_box_vaha', $slovenska_posta_eu_ems_box_vaha, 'input-eu-ems-box-vaha', 'eu_ems_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_eu_ems_status, 'slovenska_posta_eu_ems_status', 'input-eu-ems-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>
        </div>
    </div>
</div>


<div class="tab-pane" id="world">
    <div class="col-sm-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#tab-world-general" data-toggle="tab"><?php echo $text_tab_general; ?></a></li>
            <li><a href="#tab-world-free" data-toggle="tab"><?php echo $text_tab_free; ?></a></li>
            <li><a href="#tab-world-list" data-toggle="tab"><?php echo $text_tab_list; ?></a></li>
            <li><a href="#tab-world-dop-list" data-toggle="tab"><?php echo $text_tab_dop_list; ?></a></li>
            <li><a href="#tab-world-poistenie-list" data-toggle="tab"><?php echo $text_tab_poistenie_list; ?></a></li>
            <li><a href="#tab-world-balik-posta" data-toggle="tab"><?php echo $text_tab_balik_posta; ?></a></li>
            <li><a href="#tab-world-ems" data-toggle="tab"><?php echo $text_tab_ems; ?></a></li>

        </ul>
    </div>

    <div class="col-sm-10">

        <div class="tab-content">

            <div class="tab-pane active" id="tab-world-general">
                <?php $createStatus($entry_status, $slovenska_posta_world_status, 'slovenska_posta_world_status', 'input-world-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-world-free">
                <?php $createTextField($entry_free_cena, $help_free_delivery, 'slovenska_posta_world_free_cena', $slovenska_posta_world_free_cena, 'input-world-free', 'world_free') ?>
                <?php $createTextField($entry_free_vaha, $help_free_delivery_vaha, 'slovenska_posta_world_free_vaha', $slovenska_posta_world_free_vaha, 'input-world-free-vaha', 'world_free_vaha') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_world_free_dobierka_status", $slovenska_posta_world_free_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_world_free_dobierka_cena', $slovenska_posta_world_free_dobierka_cena, 'input-world-free-dobierka', 'world_free_dobierka') ?>
                <?php $createStatus($entry_free_status, $slovenska_posta_world_free_status, 'slovenska_posta_world_free_status', 'input-world-free-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-world-list">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_world_list_1_sadzba', $slovenska_posta_world_list_1_sadzba, 'input-world-list-1-trieda', 'world_list_1') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_world_list_box_vaha', $slovenska_posta_world_list_box_vaha, 'input-world-list-box-vaha', 'world_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_world_list_status, 'slovenska_posta_world_list_status', 'input-world-list', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-world-dop-list">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_world_dop_list_sadzba', $slovenska_posta_world_dop_list_sadzba, 'input-world-dop-list-1-trieda', 'world_dop_list') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_world_dop_list_dobierka_status", $slovenska_posta_world_dop_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_world_dop_list_dobierka_cena', $slovenska_posta_world_dop_list_dobierka_cena, 'input-world-dop-list-dobierka', 'world_dop_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_world_dop_list_dorucenka_status", $slovenska_posta_world_dop_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_world_dop_list_dorucenka_cena', $slovenska_posta_world_dop_list_dorucenka_cena, 'input-world-dop-list-dorucenka', 'world_dop_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_world_dop_list_do_ruk_status", $slovenska_posta_world_dop_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_world_dop_list_do_ruk_cena', $slovenska_posta_world_dop_list_do_ruk_cena, 'input-world-dop-list-do-ruk', 'world_dop_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_world_dop_list_box_vaha', $slovenska_posta_world_dop_list_box_vaha, 'input-world-dop-list-box-vaha', 'world_dop_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_world_dop_list_status, 'slovenska_posta_world_dop_list_status', 'input-world-list-doporuceny', $text_enabled_sp, $text_disabled_sp) ?>
            </div>

            <div class="tab-pane" id="tab-world-poistenie-list">
                <?php $createTextareaField($entry_cz_poistenie_list_1_100_sadzba, $help_rate, 'slovenska_posta_world_poistenie_list_1_100_sadzba', $slovenska_posta_world_poistenie_list_1_100_sadzba, 'input-world-poistenie-list-1-100-trieda', 'world_ist_list_1_100') ?>
                <?php $createTextareaField($entry_cz_poistenie_list_1_500_sadzba, $help_rate, 'slovenska_posta_world_poistenie_list_1_500_sadzba', $slovenska_posta_world_poistenie_list_1_500_sadzba, 'input-world-poistenie-list-1-500-trieda', 'world_ist_list_1_500') ?>
                <?php $createTextareaField($entry_cz_poistenie_list_1_1000_sadzba, $help_rate, 'slovenska_posta_world_poistenie_list_1_1000_sadzba', $slovenska_posta_world_poistenie_list_1_1000_sadzba, 'input-world-poistenie-list-1-1000-trieda', 'world_ist_list_1_1000') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_world_poistenie_list_dobierka_status", $slovenska_posta_world_poistenie_list_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_world_poistenie_list_dobierka_cena', $slovenska_posta_world_poistenie_list_dobierka_cena, 'input-world-poistenie-list-dobierka', 'world_ist_list_dobierka') ?>
                <?php $createRadioButton($entry_dorucenka, false, "slovenska_posta_world_poistenie_list_dorucenka_status", $slovenska_posta_world_poistenie_list_dorucenka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dorucenka_cena, false, 'slovenska_posta_world_poistenie_list_dorucenka_cena', $slovenska_posta_world_poistenie_list_dorucenka_cena, 'input-world-poistenie-list-dorucenka', 'world_ist_list_dorucenka') ?>
                <?php $createRadioButton($entry_do_ruk, false, "slovenska_posta_world_poistenie_list_do_ruk_status", $slovenska_posta_world_poistenie_list_do_ruk_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_do_ruk_cena, false, 'slovenska_posta_world_poistenie_list_do_ruk_cena', $slovenska_posta_world_poistenie_list_do_ruk_cena, 'input-world-poistenie-list-do-ruk', 'world_ist_list_do_ruk') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_world_poistenie_list_box_vaha', $slovenska_posta_world_poistenie_list_box_vaha, 'input-world-poistenie-list-box-vaha', 'world_ist_list_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_world_poistenie_list_status, 'slovenska_posta_world_poistenie_list_status', 'input-world-list-poistenie', $text_enabled_sp, $text_disabled_sp) ?>

            </div>

            <div class="tab-pane" id="tab-world-balik-posta">
                <?php $createTextareaField($entry_rate, $help_rate, 'slovenska_posta_world_balik_1_sadzba', $slovenska_posta_world_balik_1_sadzba, 'input-world-balik-1-sadzba', 'world_balik_1') ?>
                <?php $createRadioButton($entry_sk_balik_poistenie_status, false, "slovenska_posta_world_balik_poistenie_status", $slovenska_posta_world_balik_poistenie_status, $text_yes, $text_no) ?>
                <?php $createTextareaField($entry_sk_balik_poistenie_sadzba, $help_balik_poistenie_sadzba, 'slovenska_posta_world_balik_poistenie_sadzba', $slovenska_posta_world_balik_poistenie_sadzba, 'input-world-balik-poistenie-sadzba', 'world_balik_ist') ?>
                <?php $createRadioButton($entry_dobierka, false, "slovenska_posta_world_balik_dobierka_status", $slovenska_posta_world_balik_dobierka_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_dobierka_cena, false, 'slovenska_posta_world_balik_dobierka_cena', $slovenska_posta_world_balik_dobierka_cena, 'input-world-balik-dobierka', 'world_balik_dobierka') ?>
                <?php $createRadioButton($entry_krehke, false, "slovenska_posta_world_balik_krehke_status", $slovenska_posta_world_balik_krehke_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_krehke_cena, false, 'slovenska_posta_world_balik_krehke_cena', $slovenska_posta_world_balik_krehke_cena, 'input-world-balik-krehke', 'world_balik_krehke') ?>
                <?php $createRadioButton($entry_neskladne, false, "slovenska_posta_world_balik_neskladne_status", $slovenska_posta_world_balik_neskladne_status, $text_yes, $text_no) ?>
                <?php $createTextField($entry_neskladne_cena, false, 'slovenska_posta_world_balik_neskladne_cena', $slovenska_posta_world_balik_neskladne_cena, 'input-world-balik-neskladne', 'world_balik_neskladne') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_world_balik_box_vaha', $slovenska_posta_world_balik_box_vaha, 'input-world-balik-box-vaha', 'world_balik_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_world_balik_status, 'slovenska_posta_world_balik_status', 'input-world-balik-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>


            <div class="tab-pane" id="tab-world-ems">
                <?php $createTextareaField($entry_rate_tar_3, $help_rate, 'slovenska_posta_world_ems_3_sadzba', $slovenska_posta_world_ems_3_sadzba, 'input-world-ems-3', 'world_ems_3') ?>
                <?php $createTextareaField($entry_rate_tar_4, $help_rate, 'slovenska_posta_world_ems_4_sadzba', $slovenska_posta_world_ems_4_sadzba, 'input-world-ems-4', 'world_ems_4') ?>
                <?php $createTextareaField($entry_rate_tar_5, $help_rate, 'slovenska_posta_world_ems_5_sadzba', $slovenska_posta_world_ems_5_sadzba, 'input-world-ems-5', 'world_ems_5') ?>
                <?php $createTextField($entry_box_vaha, $help_box_vaha, 'slovenska_posta_world_ems_box_vaha', $slovenska_posta_world_ems_box_vaha, 'input-world-ems-box-vaha', 'world_ems_vaha') ?>
                <?php $createStatus($entry_status, $slovenska_posta_world_ems_status, 'slovenska_posta_world_ems_status', 'input-world-ems-status', $text_enabled_sp, $text_disabled_sp) ?>
            </div>
        </div>
    </div>
</div>


</div>
</div>
</form>
</div>


</div>
</div>

<script type="text/javascript"><!--

    $(document).ready(function () {

        $('a[data-toggle="tab"]').click(function() {
            $('#ikros_notification').remove();
            $('.alert-success').remove();
        });

        getNotifications();

    });





    //--></script>


<script type="text/javascript"><!--

    function getNotifications() {
        $('#ikros_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> <div id="ikros_loading"><?php echo $text_loading_notifications; ?></div>');
        setTimeout(
                function(){
                    $.ajax({
                        type: 'GET',
                        url: 'index.php?route=extension/shipping/slovenska_posta/getNotifications&token=<?php echo $token; ?>',
                        dataType: 'json',
                        success: function(json) {

                            if (json['error']) {

                                $('#ikros_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['error']+' <span style="cursor:pointer;font-weight:bold;text-decoration:underline;float:right;" onclick="getNotifications();"><?php echo $text_retry; ?></span>');
                            } else if (json['message']) {
                                $('#ikros_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['message']);
                            } else {
                                $('#ikros_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+'<?php echo $error_no_news; ?>');
                            }
                        },
                        failure: function(){
                            $('#ikros_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+'<?php echo $error_notifications; ?> <span style="cursor:pointer;font-weight:bold;text-decoration:underline;float:right;" onclick="getNotifications();"><?php echo $text_retry; ?></span>');
                        },
                        error: function() {
                            $('#ikros_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+'<?php echo $error_notifications; ?> <span style="cursor:pointer;font-weight:bold;text-decoration:underline;float:right;" onclick="getNotifications();"><?php echo $text_retry; ?></span>');
                        }
                    });
                },
                1000
        );
    }
    //--></script>

</div>
<?php echo $footer; ?> 