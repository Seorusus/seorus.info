<?php
/**
 * @file
 * page.vars.php
 */

/**
 * Implements hook_preprocess_page().
 *
 * @see page.tpl.php
 */
function biotic_preprocess_page(&$variables) {
  // Add information about the number of sidebars.
  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $variables['content_column_class'] = ' class="col-sm-6"';
  }
  elseif (!empty($variables['page']['sidebar_first']) || !empty($variables['page']['sidebar_second'])) {
    $variables['content_column_class'] = ' class="col-sm-9"';
  }
  else {
    $variables['content_column_class'] = ' class="col-sm-12"';
  }

  // Primary nav.
  $variables['primary_nav'] = FALSE;
  if ($variables['main_menu']) {
    // Build links.
    $variables['primary_nav'] = menu_tree(variable_get('menu_main_links_source', 'main-menu'));
    // Provide default theme wrapper function.
    $variables['primary_nav']['#theme_wrappers'] = array('menu_tree__primary');
  }

  // Secondary nav.
  $variables['secondary_nav'] = FALSE;
  if ($variables['secondary_menu']) {
    // Build links.
    $variables['secondary_nav'] = menu_tree(variable_get('menu_secondary_links_source', 'user-menu'));
    // Provide default theme wrapper function.
    $variables['secondary_nav']['#theme_wrappers'] = array('menu_tree__secondary');
  }

  $variables['navbar_classes_array'] = array('navbar');

  if (theme_get_setting('bootstrap_navbar_position') !== '') {
    $variables['navbar_classes_array'][] = 'navbar-' . theme_get_setting('bootstrap_navbar_position');
  }
  else {
    // $variables['navbar_classes_array'][] = 'container';
  }
  if (theme_get_setting('bootstrap_navbar_inverse')) {
    $variables['navbar_classes_array'][] = 'navbar-inverse';
  }
  else {
    $variables['navbar_classes_array'][] = 'navbar-default';
  }


  //
  $theme = biotic_get_theme();
  $theme->page = &$variables;

  $webFontFamily = $theme->_webfont_family();

  // dpm($theme->get_web_fonts(), "Get web fonts");
  // dpm($theme->_webfont_family(), "WebFonts Load");
  //
  $css_uri    = theme_get_setting("css_uri");
  $isDelta    = theme_get_setting("isDelta");
  $saved_time = theme_get_setting("savetime");
  $lock       = theme_get_setting("lock");

  /* CHECK THE THEME SETTINGS */
  $default_theme   = variable_get("theme_default");
  $default_theme_settings = variable_get("theme_{$default_theme}_settings");

  if ( empty($default_theme_settings) ) {
  //  debug("SETTINGS DOES NOT EXIST");
   /* ADD CSS  FILE FOR TEMP. IF THEME SETTINGS NEVER SAVED! */
   $css_uri = "public://css-temp.css";
  }

  // Aggregate and compress CSS files setting
  $preprocess_css      = variable_get('preprocess_css', 0);
  // $saved_css_uri       = variable_get('biotic_saved_css_uri', array( 'uri' => $css_uri, 'preprocess' => $preprocess_css, 'time' => strtotime("now") ) );
  $defaut_css_uri      = array( 'uri' => null, 'preprocess' => null, 'time' => null, 'lock' => null );
  $saved_css_uri       = variable_get('biotic_saved_css_uri', $defaut_css_uri );
  // $saved_css_uri_delta = variable_get('biotic_saved_css_uri_deta', array( $isDelta => array($css_uri, $preprocess_css, $isDelta ) ) );
  $default_css_uri_delta = array( 'uri' => null, 'preprocess' => null, 'delta' => null, 'basetime' => null ,'time' => null, 'lock' => null );
  $saved_css_uri_delta = variable_get('biotic_saved_css_uri_deta', array ( $isDelta => $default_css_uri_delta ) );

  if (!isset($saved_css_uri_delta[$isDelta])) {
    $saved_css_uri_delta[$isDelta] = $default_css_uri_delta;
  }

  // DELTA CONTROL

  if (!$isDelta) {

    // debug("This is NOT  Delta");
    // debug(  $saved_css_uri_delta ,"Delta" );
    // debug(  $saved_css_uri ,"Saved" );

    if (!file_exists($css_uri) || $saved_css_uri['uri'] != $css_uri || $preprocess_css != $saved_css_uri['preprocess'] || $saved_time != $saved_css_uri['time'] ) {

      if (file_exists($saved_css_uri['uri'])) {
        file_unmanaged_delete( $saved_css_uri['uri'] );
      }

      $CSSDATA = $theme->typo_css() . $theme->get_section_css();

      $file_status = file_unmanaged_save_data( $CSSDATA, $css_uri , FILE_EXISTS_REPLACE );

      if ($file_status) {
        // debug("CSS re-Created","ThemeSettings");
        variable_set('biotic_saved_css_uri', array( 'uri' => $css_uri, 'preprocess' => $preprocess_css, 'time' => $saved_time, 'lock' => $lock ) );
        watchdog('theme', 'Theme CSS created : '. '<pre>' . print_r(variable_get('biotic_saved_css_uri'),TRUE ) . '</pre>'  );

      }

    }

  } else {

    // dpm("This is The Delta");

    // debug(  $saved_css_uri_delta ,"Delta" );
    // debug(  $saved_css_uri ,"Saved" );

    if (!file_exists($css_uri) || $saved_css_uri_delta[$isDelta]['uri'] != $css_uri || $preprocess_css != $saved_css_uri_delta[$isDelta]['preprocess'] ||  $saved_time != $saved_css_uri_delta[$isDelta]['time']  || $saved_css_uri_delta[$isDelta]['basetime'] !=  $saved_css_uri['time']  ) {

      if (file_exists($saved_css_uri_delta[$isDelta]['uri'])) {
        file_unmanaged_delete( $saved_css_uri_delta[$isDelta]['uri'] );
      }

      $CSSDATA = $theme->typo_css() . $theme->get_section_css();

      $file_status = file_unmanaged_save_data( $CSSDATA, $css_uri , FILE_EXISTS_REPLACE );

      if ($file_status) {

        $saved_css_uri_delta[$isDelta] = array(
          'uri' => $css_uri,
          'preprocess' => $preprocess_css,
          'delta' => $isDelta,
          'basetime' => $saved_css_uri['time'],
          'time' => $saved_time,
          'lock' => $lock
          );

        // debug("Delta re-Created");
        variable_set('biotic_saved_css_uri_deta', $saved_css_uri_delta );
        //  watchdog debug
        watchdog('theme', 'Theme Delta CSS created : '. '<pre>' . print_r(variable_get('biotic_saved_css_uri_deta'),TRUE ) . '</pre>'  );
      }

    }

  }


  /**
   * ---------------
   * Webfont Loaded
   * ---------------
   */

  if (count($webFontFamily) > 0) {

    $webfontjs  = drupal_get_path('theme', 'biotic')."/js/webfont.js";
    $cdnwebfont = "//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";

    drupal_add_js( $webfontjs ,
      array(
        'type' => 'file',
        'group' => JS_THEME,
        'every_page' => TRUE
      )
    );

    drupal_add_js(array('webfontloader' => array( 'fonts' => $webFontFamily ) ), 'setting');

  }

  // CSS FILES
  if ( file_exists($css_uri) ) {
      drupal_add_css( $css_uri ,array(
          'group' => CSS_THEME,
          'every_page' => TRUE,
          'type' => 'file',
          'media' => 'all',
          'preprocess' => FALSE,
          'weight' => '9999',
        ));
  }


}

/**
 * Implements hook_process_page().
 *
 * @see page.tpl.php
 */
function biotic_process_page(&$variables) {

  $variables['navbar_classes'] = implode(' ', $variables['navbar_classes_array']);
  // dpm($variables, "Proccess");
}
