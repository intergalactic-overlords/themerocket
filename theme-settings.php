<?php
function themerocket_form_system_theme_settings_alter(&$form, &$form_state) {

  //libaries stuff
  $form['libraries'] = array(
    '#type'          => 'fieldset',
    '#title'         => '&#10006; ' .t('Libraries'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#description'   => t('External libraries')
  );

  $form['libraries']['themrocket_shim'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('html5 Shim'),
    '#default_value' => theme_get_setting('themerocket_shim'),
    '#description'   => t('Add html5 shim to header')
  );
  $form['libraries']['themerocket_respondjs'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('respond.js'),
    '#default_value' => isset(theme_get_setting('themerocket_respondjs')) ? theme_get_setting('themerocket_respondjs') : true,
    '#description'   => t('IE6-IE8 support for mediaqueries with <a href="!link">Respond</a> - <strong>Only works with css aggregation turned on!</strong>
    ', array('!link' => 'https://github.com/scottjehl/Respond')),
  );

}