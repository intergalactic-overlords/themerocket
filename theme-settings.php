<?php
function themerocket_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['libraries']['boron_shim'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('html5 Shim'),
    '#default_value' => theme_get_setting('themerocket_shim'),
    '#description'   => t('Add html5 shim to header')
  );

}