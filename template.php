<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the Themerocket theme.
 */

function themerocket_css_alter(&$css) {

  /* Remove some default Drupal css */
  $exclude = array(
    'modules/aggregator/aggregator.css' => FALSE,
    'modules/block/block.css' => FALSE,
    'modules/book/book.css' => FALSE,
    'modules/comment/comment.css' => FALSE,
    'modules/dblog/dblog.css' => FALSE,
    'modules/field/theme/field.css' => FALSE,
    'modules/file/file.css' => FALSE,
    'modules/filter/filter.css' => FALSE,
    'modules/forum/forum.css' => FALSE,
    'modules/help/help.css' => FALSE,
    'modules/menu/menu.css' => FALSE,
    'modules/node/node.css' => FALSE,
    'modules/openid/openid.css' => FALSE,
    'modules/poll/poll.css' => FALSE,
    'modules/profile/profile.css' => FALSE,
    'modules/search/search.css' => FALSE,
    'modules/statistics/statistics.css' => FALSE,
    'modules/syslog/syslog.css' => FALSE,
    'modules/system/admin.css' => FALSE,
    'modules/system/maintenance.css' => FALSE,
    'modules/system/system.css' => FALSE,
    'modules/system/system.admin.css' => FALSE,
    //'modules/system/system.base.css' => FALSE,
    'modules/system/system.maintenance.css' => FALSE,
    'modules/system/system.messages.css' => FALSE,
    'modules/system/system.theme.css' => FALSE,
    'modules/system/system.menus.css' => FALSE,
    'modules/taxonomy/taxonomy.css' => FALSE,
    'modules/tracker/tracker.css' => FALSE,
    'modules/update/update.css' => FALSE,
    'modules/user/user.css' => FALSE,
    'sites/all/modules/contrib/panels/css/panels.css' => FALSE,
  );

  $css = array_diff_key($css, $exclude);

  /* Get rid of some default panel css */
  foreach ($css as $path => $meta) {
    if (strpos($path, 'threecol_33_34_33_stacked') !== FALSE || strpos($path, 'threecol_25_50_25_stacked') !== FALSE) {
      unset($css[$path]);
    }
  }
}

/**
 * implements theme_links__locale_block
 *
 * language shortcuts in locale menu
 */
function themerocket_links__locale_block($variables) {
  foreach($variables['links'] as $language => $langInfo) {
    // link empty translation to homepage
    if (empty($langInfo['href'])) {
      $langInfo['href'] = "";
    }

    $abbr = $langInfo['language']->language;
    $name = $langInfo['language']->name;
    $variables['links'][$language]['title'] = '<abbr title="' . $name . '">' . $abbr . '</abbr>';
    $variables['links'][$language]['html'] = TRUE;
  }
  $content = theme_links($variables);
  return $content;
}

/**
 * Changes the default meta content-type tag to the shorter HTML5 version
 */
function themerocket_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8'
  );
}

/**
 * Changes the search form to use the HTML5 "search" input attribute
 */
function themerocket_preprocess_search_block_form(&$vars) {
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}

/**
 * implements template_preprocess_block
 */
function themerocket_preprocess_block(&$variables) {
  if ($variables['block']->module === 'menu_block') {
    $variables['title_attributes_array']['class'] = 'menu-title';
  }
}

/**
 * Uses RDFa attributes if the RDF module is enabled
 * Lifted from Adaptivetheme for D7, full credit to Jeff Burnz
 * ref: http://drupal.org/node/887600
 */
function themerocket_preprocess_html(&$vars) {

  // fonts
  drupal_add_css('http://fonts.googleapis.com/css?family=Alegreya+Sans:400,700,900,400italic,700italic');


  // Ensure that the $vars['rdf'] variable is an object.
  if (!isset($vars['rdf']) || !is_object($vars['rdf'])) {
    $vars['rdf'] = new StdClass();
  }

  if (module_exists('rdf')) {
    $vars['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">' . "\n";
    $vars['rdf']->version = 'version="HTML+RDFa 1.1"';
    $vars['rdf']->namespaces = $vars['rdf_namespaces'];
    $vars['rdf']->profile = ' profile="' . $vars['grddl_profile'] . '"';
  } else {
    $vars['doctype'] = '<!DOCTYPE html>' . "\n";
    $vars['rdf']->version = '';
    $vars['rdf']->namespaces = '';
    $vars['rdf']->profile = '';
  }


 // use the $html5shiv variable in their html.tpl.php
  $element = array(
    'element' => array(
    '#tag' => 'script',
    '#value' => '',
    '#attributes' => array(
      'src' => '//html5shiv.googlecode.com/svn/trunk/html5.js',
     ),
   ),
 );

 $shimset = theme_get_setting('themerocket_shim');
 $script = theme('html_tag', $element);
 //If the theme setting for adding the html5shim is checked, set the variable.
 if ($shimset == 1) { $vars['html5shim'] = "\n<!--[if lt IE 9]>\n" . $script . "<![endif]-->\n"; }

}

function themerocket_preprocess_page(&$variables){

  //custom 404
  $headers = drupal_get_http_header();

  if (isset($headers['status'])) {
    if($headers['status'] == '404 Not Found'){
      $variables['theme_hook_suggestions'][] = 'page__404';
    }
  }
}

/**
 * implements hook_preprocess_panels_pane
 *
 * add view mode to fieldable panels pane
 */
function themerocket_preprocess_panels_pane(&$variables) {
  // if fieldable panel panes is used, add extra class to panel, related to the view mode
  if ($variables['pane']->type === "fieldable_panels_pane" && isset($variables['content']['#view_mode'])) {
    $variables['classes_array'][] = 'fieldable--view-mode-' . $variables['content']['#view_mode'];
  }
}

/**
 * implements hook_form_alter
 */
function themerocket_form_alter(&$form, &$form_state, $form_id) {
// Add some cool text to the search block form
  if ($form_id == 'search_block_form') {
    // HTML5 placeholder attribute
    $form['search_block_form']['#attributes']['placeholder'] = t('enter search terms');
  }
}

/**
 * implements template_preprocess_views_view_table
 *
 * add vertical striping to views tables
 */
function themerocket_preprocess_views_view_table(&$vars) {
  $view = $vars['view'];

  // We need the raw data for this grouping, which is passed in as $vars['rows'].
  // However, the template also needs to use for the rendered fields.  We
  // therefore swap the raw data out to a new variable and reset $vars['rows']
  // so that it can get rebuilt.
  // Store rows so that they may be used by further preprocess functions.
  $result = $vars['result'] = $vars['rows'];
  $vars['rows'] = array();
  $vars['field_classes'] = array();
  $vars['header'] = array();

  $options = $view->style_plugin->options;
  $handler = $view->style_plugin;

  $default_row_class = isset($options['default_row_class']) ? $options['default_row_class'] : TRUE;
  $row_class_special = isset($options['row_class_special']) ? $options['row_class_special'] : TRUE;

  $fields = &$view->field;
  $columns = $handler->sanitize_columns($options['columns'], $fields);

  $active = !empty($handler->active) ? $handler->active : '';
  $order = !empty($handler->order) ? $handler->order : 'asc';

  $query = tablesort_get_query_parameters();
  if (isset($view->exposed_raw_input)) {
    $query += $view->exposed_raw_input;
  }

  // Fields must be rendered in order as of Views 2.3, so we will pre-render
  // everything.
  $renders = $handler->render_fields($result);

  $odd = true;

  foreach ($columns as $field => $column) {
    // Create a second variable so we can easily find what fields we have and what the
    // CSS classes should be.
    $vars['fields'][$field] = drupal_clean_css_identifier($field);
    if ($active == $field) {
      $vars['fields'][$field] .= ' active';
    }

    // render the header labels
    if ($field == $column && empty($fields[$field]->options['exclude'])) {

      $label = check_plain(!empty($fields[$field]) ? $fields[$field]->label() : '');
      if (empty($options['info'][$field]['sortable']) || !$fields[$field]->click_sortable()) {
        $vars['header'][$field] = $label;
      }
      else {
        $initial = !empty($options['info'][$field]['default_sort_order']) ? $options['info'][$field]['default_sort_order'] : 'asc';

        if ($active == $field) {
          $initial = ($order == 'asc') ? 'desc' : 'asc';
        }

        $title = t('sort by @s', array('@s' => $label));
        if ($active == $field) {
          $label .= theme('tablesort_indicator', array('style' => $initial));
        }

        $query['order'] = $field;
        $query['sort'] = $initial;
        $link_options = array(
          'html' => TRUE,
          'attributes' => array('title' => $title),
          'query' => $query,
        );
        $vars['header'][$field] = l($label, $_GET['q'], $link_options);
      }

      $vars['header_classes'][$field] = $odd ? 'col-odd' : 'col-even';

      // Set up the header label class.
      if ($fields[$field]->options['element_default_classes']) {
        $vars['header_classes'][$field] .= " views-field views-field-" . $vars['fields'][$field];
      }
      $class = $fields[$field]->element_label_classes(0);
      if ($class) {
        if ($vars['header_classes'][$field]) {
          $vars['header_classes'][$field] .= ' ';
        }
        $vars['header_classes'][$field] .= $class;
      }
      // Add a CSS align class to each field if one was set
      if (!empty($options['info'][$field]['align'])) {
        $vars['header_classes'][$field] .= ' ' . drupal_clean_css_identifier($options['info'][$field]['align']);
      }

      // Add a header label wrapper if one was selected.
      if ($vars['header'][$field]) {
        $element_label_type = $fields[$field]->element_label_type(TRUE, TRUE);
        if ($element_label_type) {
          $vars['header'][$field] = '<' . $element_label_type . '>' . $vars['header'][$field] . '</' . $element_label_type . '>';
        }
      }

    }

    // Add a CSS align class to each field if one was set
    if (!empty($options['info'][$field]['align'])) {
      $vars['fields'][$field] .= ' ' . drupal_clean_css_identifier($options['info'][$field]['align']);
    }

    // Render each field into its appropriate column.
    foreach ($result as $num => $row) {
      $vars['field_classes'][$field][$num] = $odd ? 'col-odd' : 'col-even';

      // Add field classes
      if ($fields[$field]->options['element_default_classes']) {
        $vars['field_classes'][$field][$num] .= " views-field views-field-" . $vars['fields'][$field];
      }
      if ($classes = $fields[$field]->element_classes($num)) {
        if ($vars['field_classes'][$field][$num]) {
          $vars['field_classes'][$field][$num] .= ' ';
        }

        $vars['field_classes'][$field][$num] .= $classes;
      }
      $vars['field_attributes'][$field][$num] = array();

      if (!empty($fields[$field]) && empty($fields[$field]->options['exclude'])) {
        $field_output = $renders[$num][$field];
        $element_type = $fields[$field]->element_type(TRUE, TRUE);
        if ($element_type) {
          $field_output = '<' . $element_type . '>' . $field_output . '</' . $element_type . '>';
        }

        // Don't bother with separators and stuff if the field does not show up.
        if (empty($field_output) && !empty($vars['rows'][$num][$column])) {
          continue;
        }

        // Place the field into the column, along with an optional separator.
        if (!empty($vars['rows'][$num][$column])) {
          if (!empty($options['info'][$column]['separator'])) {
            $vars['rows'][$num][$column] .= filter_xss_admin($options['info'][$column]['separator']);
          }
        }
        else {
          $vars['rows'][$num][$column] = '';
        }

        $vars['rows'][$num][$column] .= $field_output;
      }
    }

    // Remove columns if the option is hide empty column is checked and the field is not empty.
    if (!empty($options['info'][$field]['empty_column'])) {
      $empty = TRUE;
      foreach ($vars['rows'] as $num => $columns) {
        $empty &= empty($columns[$column]);
      }
      if ($empty) {
        foreach ($vars['rows'] as $num => &$column_items) {
          unset($column_items[$column]);
          unset($vars['header'][$column]);
        }
      }
    }
    if(!$fields[$field]->options['exclude']) {
      $odd = !$odd;
    }
  }

  // Hide table header if all labels are empty.
  if (!array_filter($vars['header'])) {
    $vars['header'] = array();
  }

  $count = 0;
  foreach ($vars['rows'] as $num => $row) {
    $vars['row_classes'][$num] = array();
    if ($row_class_special) {
      $vars['row_classes'][$num][] = ($count++ % 2 == 0) ? 'odd' : 'even';
    }
    if ($row_class = $handler->get_row_class($num)) {
      $vars['row_classes'][$num][] = $row_class;
    }
  }

  if ($row_class_special) {
    $vars['row_classes'][0][] = 'views-row-first';
    $vars['row_classes'][count($vars['row_classes']) - 1][] = 'views-row-last';
  }

  $vars['classes_array'] = array('views-table');
  if (empty($vars['rows']) && !empty($options['empty_table'])) {
    $vars['rows'][0][0] = $view->display_handler->render_area('empty');
    // Calculate the amounts of rows with output.
    $vars['field_attributes'][0][0]['colspan'] = count($vars['header']);
    $vars['field_classes'][0][0] = 'views-empty';
  }


  if (!empty($options['sticky'])) {
    drupal_add_js('misc/tableheader.js');
    $vars['classes_array'][] = "sticky-enabled";
  }
  $vars['classes_array'][] = 'cols-' . count($vars['header']);

  // Add the summary to the list if set.
  if (!empty($handler->options['summary'])) {
    $vars['attributes_array'] = array('summary' => filter_xss_admin($handler->options['summary']));
  }

  // Add the caption to the list if set.
  if (!empty($handler->options['caption'])) {
    $vars['caption'] = filter_xss_admin($handler->options['caption']);
  }
  else {
    $vars['caption'] = '';
  }
}

/**
  * Implements theme_panels_default_style_render_region()
  *
  * Remove panels separator
  */
function themerocket_panels_default_style_render_region($vars) {
  $output = '';
  $output .= implode('', $vars['panes']);
  return $output;
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Adds a placeholder to the search block.
 */
function themerocket_form_search_block_form_alter(&$form, &$form_state, $form_id) {
  $form['search_block_form']['#attributes']['placeholder'] = t('Search…');
}


/**
 * Implements theme_menu_tree__menu_block().
 *
 * add level classes to menus in menu_block
 */
function themerocket_menu_tree__menu_block(&$variables) {
  return '<ul class="menu lvl-1">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_menu_link().
 *
 * add level classes to menus
 * adding a 'trigger' to links with submenu, for use in mobile menus
 */
function themerocket_menu_link($variables) {

  $element = $variables['element'];
  if (!isset($element['lvl'])) {
    $element['lvl'] = 2;
  }
  $lvl = $element['lvl'];
  $sub_menu = '';
  $sub_menu_trigger = '';

  if ($element['#below']) {
    // Wrap in dropdown-menu.
    unset($element['#below']['#theme_wrappers']);
    foreach($element['#below'] as $key => $value) {
      if (isset($value['#href'])) {
        $element['#below'][$key]['lvl'] = $lvl + 1;
      }
    }
    $sub_menu = '<ul class="menu lvl-' . $lvl . '">' . drupal_render($element['#below']) . '</ul>';
    $sub_menu_trigger = '<a href="#" class="expand-trigger">' . t('expand submenu') . '</a>';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu_trigger . $sub_menu . "</li>\n";
}

/**
 * Implements theme_status_messages.
 *
 * add class to ul
 */
function themerocket_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages $type\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul class='message-list'>\n";
      foreach ($messages as $message) {
        $output .= '  <li class="message wysiwyg">' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= '<div class="message wysiwyg">' . $messages[0] . '</div>';
    }
    $output .= "</div>\n";
  }
  return $output;
}