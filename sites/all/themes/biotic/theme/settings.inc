<?php


/**
 * @file
 * settings.inc
 */

function _biotic_form_constructor(&$form,$form_state){
  // Delta module Support
  global $arguments;
  $arguments = arg();

  $form['args'] = array('#type' => 'value', '#value' => $arguments );
  $form['css_uri'] = array('#type' => 'hidden', '#default_value' => theme_get_setting('css_uri') );
  $form['isDelta'] = array('#type' => 'hidden', '#default_value' => theme_get_setting('isDelta') );
  $form['savetime'] = array('#type' => 'hidden', '#default_value' => theme_get_setting('savetime') );

  // biotic_form_system_theme_settings_alter
  $form['#submit'][] = 'biotic_form_constructor_submitter';

}

function biotic_form_constructor_submitter($form, &$form_state){

  // $Lock = TRUE;
  $Lock = theme_get_setting('lock');
  $Lock = $form_state['values']['lock'];
  $debug_mode = $form_state['values']['debug_mode'];


  $prefix = "";
  $file   = "";
  $args = $form_state['values']['args'];
  $isDelta = array_search('delta', $args);
  $hash = user_password(8);

  $exFile = $form_state['values']['css_uri'];

  if ($isDelta) {

    $prefix = $args[$isDelta+2]; // return Delta Name

    $form_state['values']['isDelta'] = $prefix;

  } else {
    $prefix = "css";
  }


  if ($exFile) {

    $name = str_replace("public://", "", $exFile);
    $name = explode("-", $name);

    if ( $name[0] == $prefix && !$Lock ) {
      $debug_message = t("THEME SETTINGS: <b>Mode 1</b> <ul>
      <li>File Exist (%file)</li>
      <li>File prefix matched (%prefix)</li>
      <li>Lock is not Active</li>
      </ul>", array(
        '%file'   => $exFile,
        '%prefix' => $prefix
      ));

      file_unmanaged_delete($exFile);
      $exFile = FALSE;

      if ($debug_mode) {
        drupal_set_message($debug_message,'warning');
        watchdog('debug', $debug_message );
      }

    }
    elseif ( $name[0] != $prefix && $Lock ) {
      // file_unmanaged_delete($exFile);

      $debug_message = t("THEME SETTINGS: <b>Mode 2</b> <ul>
        <li>File Exist (%file)</li>
        <li>File prefix is not matched (%prefix)</li>
        <li>Lock Active</li>
      </ul>",array(
        '%file'   => $exFile,
        '%prefix' => $prefix
      ));

      if ($debug_mode) {
        drupal_set_message($debug_message,'warning');
        watchdog('debug', $debug_message );
      }

      $exFile = FALSE;
    }
    elseif (  $name[0] == $prefix && $Lock  ) {
      // file_unmanaged_delete($exFile);
      // debug("Set 3  ( File exist({$exFile}) but set to False , File name is equal to prefix {$prefix}), Lock Mode Avtive ) ");

      $debug_message = t("THEME SETTINGS: <b>Mode 3</b> <ul>
        <li>File Exist but set to False! (%file)</li>
        <li>File prefix is matched (%prefix)</li>
        <li>Lock Active</li>
      </ul>",array(
        '%file'   => $exFile,
        '%prefix' => $prefix
      ));

      if ($debug_mode) {
        drupal_set_message($debug_message,'warning');
        watchdog('debug', $debug_message );
      }

      $exFile = FALSE;
    }

  } else {
    // debug("Theme FIRST TIME SAVED!!");
    watchdog('debug', "Theme FIRST TIME SAVED!" );
  }

  // dpm($exFile,"Ex file");

  if ($Lock) {
    $hash = "locked";

    if ($exFile) {
      $file = $exFile;
    } else {
      $file = "public://".$prefix."-".$hash.".css";
    }

  }
  else {
    $file = "public://".$prefix."-".$hash.".css";
  }

  $form_state['values']['css_uri'] = $file;
  $form_state['values']['savetime'] = strtotime("now");

}


function _biotic_typo_builder(&$form,$form_state) {
  global $theme_key;
  $theme = biotic_get_theme();

  // Display a warning if jquery_update isn't enabled.
  if ((!module_exists('jquery_update') || !version_compare(variable_get('jquery_update_jquery_version', 0), 1.7, '>=')) ) {
    drupal_set_message(t('jQuery Update is not enabled, <strong>Zenon Typo Builder</strong> requires a minimum jQuery version of 1.7 or higher.'), 'error');
    return false;
  }


  // TYPO BUILDER
  drupal_add_css( drupal_get_path('theme','biotic'). "/css/TYPO/typo-admin.css", array('weight' => 999, 'group' => CSS_THEME ) );
  // drupal_add_css( drupal_get_path('theme','biotic'). "/css/TYPO/main.css", array('weight' => 999, 'group' => CSS_THEME ) );

  // drupal_add_js("//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js");
  drupal_add_js( drupal_get_path('theme','biotic'). "/js/webfont.js" );

  drupal_add_js( drupal_get_path('theme','biotic'). "/js/TYPO/stock.js", array('scope' => 'footer') );
  drupal_add_js( drupal_get_path('theme','biotic'). "/js/TYPO/vendor.js", array('scope' => 'footer') );
  drupal_add_js( drupal_get_path('theme','biotic'). "/js/TYPO/typo-main.js", array('scope' => 'footer') );

  // Components.
  $form['typo_builder'] = array(
    '#type' => 'fieldset',
    '#title' => t('Typo Builder'),
    '#group' => 'bootstrap',
    '#weight' => -1
  );

  $form['typo_builder']['container'] = array(
    '#type' => 'container',
        '#attributes' => array(
           'id' => 'typo-container',
    ),
    '#weight' => -999,
  );

  $form['typo_builder']['container']['menu'] = array(
    '#markup'          => '<div id="typo-menu"></div>',
  );

  $form['typo_builder']['container']['table'] = array(
    '#markup'          => '<div class="container-fluid"> <div id="typo-table" class="row-fluid"></div> </div>',
  );

  $form['typo_builder']['container']['typo'] = array(
    '#markup'          => '<div class="container-fluid"> <div id="Typo" class="row-fluid"></div> </div>',
  );

  $form['typo'] = array(
    '#type' => 'hidden',
    // '#default_value' => theme_get_setting('typo') ? theme_get_setting('typo') : $theme->default_settings()
    '#default_value' => theme_get_setting('typo')

  );

}


function _biotic_layout_builder(&$form,$form_state){
  global $theme_key, $arguments;
  $theme = biotic_get_theme();

  // Display a warning if jquery_update isn't enabled.
  if ((!module_exists('jquery_update') || !version_compare(variable_get('jquery_update_jquery_version', 0), 1.7, '>=')) ) {
    drupal_set_message(t('jQuery Update is not enabled, <strong>Zenon Layout Builder</strong> requires a minimum jQuery version of 1.7 or higher.'), 'error');
  }


    // debug( system_get_info('module','media') );
    // debug( system_get_info('module','media')->version  );

    if ( module_exists('media') ) {

      $media_info = system_get_info('module','media');


      // Media 7.x-2.0-alpha3
      // -----------------------------------------------
      if ( $media_info['version'] == '7.x-2.0-alpha3' ) {
        include_once drupal_get_path('module', 'media') . '/wysiwyg_plugins/media.inc';
        media_include_browser_js();
      }

      // Media 7.x-2.0-alpha4
      // -----------------------------------------------
      if ( $media_info['version'] == '7.x-2.0-alpha4' ) {

        if( module_exists('media_wysiwyg') ) {
          media_wysiwyg_include_browser_js();
        } else {
          drupal_set_message(t("<b>Media WYSIWYG</b> Module Requeried!"), 'error', FALSE);
        }

      }

    } else {
      drupal_set_message(t("<b>Media</b> Module Requeried!"), 'error', FALSE);
      return false;
    }



  // LAYOUT BUILDER

  drupal_add_css( drupal_get_path('theme','biotic'). "/css/BUILDER/admin.css", array('weight' => 999, 'group' => CSS_THEME ) );
  drupal_add_css( drupal_get_path('theme','biotic'). "/css/BUILDER/admin-modules.css", array('weight' => 999, 'group' => CSS_THEME ) );
  drupal_add_js(array('loadMediaPreview' => $theme->media), 'setting');

  // System Libraries
  drupal_add_library('system', 'ui');
  drupal_add_library('system', 'ui.sortable');


  // Components.
  $form['layout_builder'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout Builder'),
    '#group' => 'bootstrap',
    '#weight' => -1
  );

  $form['layout_builder']['container'] = array(
    '#type' => 'container',
        '#attributes' => array(
          'id' => 'valve',
          'class' => array(),
    ),
    '#weight' => -999,
  );

  $form['layout_builder']['container']['menu'] = array(
    '#markup'          => '<ul id="menu" class=" nav nav-pills"></ul>',
  );

  $form['layout_builder']['container']['layout'] = array(
    '#markup'          => '<div class="container-fluid"> <div id="sections" class="row-fluid"></div> </div>',
  );


  $form['layouts'] = array(
    '#type' => 'hidden',
    '#default_value' => theme_get_setting('layouts') ? theme_get_setting('layouts') : $theme->default_settings()

  );

  drupal_add_js( drupal_get_path('theme','biotic'). "/js/BUILDER/vendor.js", array('scope' => 'footer') );
  drupal_add_js( drupal_get_path('theme','biotic'). "/js/BUILDER/bootstrap.js", array('scope' => 'footer') );
  drupal_add_js( drupal_get_path('theme','biotic'). "/js/BUILDER/plugins.js", array('scope' => 'footer') );
  drupal_add_js( drupal_get_path('theme','biotic'). "/js/BUILDER/main.js",  array('scope' => 'footer'));

  // end - LAYOUT BUİLDER


   $regions = array_keys(system_region_list($theme_key, REGIONS_ALL));
   $regions_hidden = array('page_top','page_bottom','dashboard_main','dashboard_sidebar','dashboard_inactive');
   $system_regions = array_diff($regions, $regions_hidden );
   $theme_regions = array_keys($theme->regions);

   $reg_diff_system = array_diff($system_regions, $theme_regions);
   $reg_diff_theme = array_diff($theme_regions,$system_regions);

   $inter_sys   =  array_intersect(  array_keys(array_flip( $reg_diff_system) ), $system_regions );
   $inter_theme =  array_intersect(  array_keys(array_flip( $reg_diff_theme) ), $theme_regions );

   // dpm($theme_regions,"theme_regions");

   /**
    *
    * Missing Region in the THEME SETTINGS file
    *
    */
   if ( $inter_sys && $inter_sys == array_keys(array_flip( $reg_diff_system) )) {
      $rsys = ( count($reg_diff_system) > 1 ) ? "Regions":"Region";
      $list = array(
        '#items' => $reg_diff_system,
        '#theme' => array('item_list'),
      );
      $message = t("Missing @Region in the theme <em>settings</em>. Please add the following @Region into your Layout Builder. !list",array('@Region'=>$rsys, '%diff' => implode(", ",$reg_diff_system),'!list' => drupal_render($list)  ));
      drupal_set_message($message,"error");
   }

   /**
    *
    * Missing Region in the INFO file
    *
    */
   if ( $inter_theme && $inter_theme == array_keys(array_flip( $reg_diff_theme) )) {
      $rtpl = ( count($reg_diff_theme) > 1 ) ? "Regions":"Region";
      $message = t("Missing @Region <b>(%diff)</b> in the theme <em>info</em> file.",array('@Region'=>$rtpl,'%diff' => implode(", ",$reg_diff_theme) ));
      drupal_set_message($message,"error");
   }


  $form['theme_settings']['page_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show page title'),
    '#default_value' => theme_get_setting('page_title'),
  );


  // $tt = ( array_search('delta', $arguments) ) ? "hidden" : "checkbox";


  $form['theme_settings']['lock'] = array(
    '#type' => (array_search('delta', $arguments)) ? "hidden" : "checkbox",
    '#title' => t('CSS Lock'),
    '#default_value' => theme_get_setting('lock'),
  );


   // dvm( array_intersect(  array_keys(array_flip( $reg_diff) ), $system_regions)  );
   // dvm( array_keys(array_flip( $reg_diff) )  );
   // dvm( $system_regions  );

   // dpm($reg_diff);

   // if ($reg_diff) {
   //   $message = t("System Settings Does not Fit the Theme Settings %diff",array('%diff' => implode(" ",$reg_diff) ));
   //   drupal_set_message($message,"error");
   // }


}


/**
 * Contains the form for the theme settings.
 *
 * @param array $form
 *   The form array, passed by reference.
 * @param array $form_state
 *   The form state array, passed by reference.
 */
function _biotic_settings_form(&$form, $form_state) {
  // Do not add Bootstrap specific settings to non-bootstrap based themes.
  $theme = !empty($form_state['build_info']['args'][0]) ? $form_state['build_info']['args'][0] : FALSE;
  // Global settings.
  if ($theme === FALSE) {
    return;
  }
  // Other theme settings.
  elseif ($theme !== 'biotic') {
    $base_theme = biotic_get_theme_info($theme, 'base theme');
    if (!$base_theme || (!is_array($base_theme) && $base_theme !== 'biotic') || (is_array($base_theme) && !in_array('biotic', $base_theme))) {
      return;
    }
  }

  // Display a warning if jquery_update isn't enabled.
  if ((!module_exists('jquery_update') || !version_compare(variable_get('jquery_update_jquery_version', 0), 1.7, '>=')) && !theme_get_setting('bootstrap_toggle_jquery_error')) {
    drupal_set_message(t('jQuery Update is not enabled, Bootstrap Features requires a minimum jQuery version of 1.7 or higher.<br/>Please enable <a href="https://drupal.org/project/jquery_update">jQuery Update module</a> 7.x-2.3 or higher, you must manually set this in the configuration after it is installed.'), 'error');
  }

  // Wrap global setting fieldsets in vertical tabs.
  $form['general'] = array(
    '#type' => 'vertical_tabs',
    '#prefix' => '<h2><small>' . t('Override Global Settings') . '</small></h2>',
    '#weight' => -9,
  );
  $form['theme_settings']['#group'] = 'general';
  $form['logo']['#group'] = 'general';
  $form['favicon']['#group'] = 'general';

  // Do not add Bootstrap specific settings to global settings.
  if (empty($form_state['build_info']['args'][0])) {
    unset($form['general']['#prefix']);
    return;
  }

  $form['bootstrap'] = array(
    '#type' => 'vertical_tabs',
    '#attached' => array(
      'js'  => array(drupal_get_path('theme', 'biotic') . '/js/bootstrap.admin.js'),
    ),
    // '#prefix' => '<h2><small>' . t('Advanced Theme Settings') . '</small></h2>',
    '#weight' => -10,
  );

  // Components.
  $form['components'] = array(
    '#type' => 'fieldset',
    '#title' => t('Components'),
    '#group' => 'bootstrap',
  );
  // Breadcrumbs.
  $form['components']['breadcrumbs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumbs'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['components']['breadcrumbs']['bootstrap_breadcrumb'] = array(
    '#type' => 'select',
    '#title' => t('Breadcrumb visibility'),
    '#default_value' => theme_get_setting('bootstrap_breadcrumb'),
    '#options' => array(
      0 => t('Hidden'),
      1 => t('Visible'),
      2 => t('Only in admin areas'),
    ),
  );
  $form['components']['breadcrumbs']['bootstrap_breadcrumb_home'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show "Home" breadcrumb link'),
    '#default_value' => theme_get_setting('bootstrap_breadcrumb_home'),
    '#description' => t('If your site has a module dedicated to handling breadcrumbs already, ensure this setting is enabled.'),
    '#states' => array(
      'invisible' => array(
        ':input[name="bootstrap_breadcrumb"]' => array('value' => 0),
      ),
    ),
  );
  $form['components']['breadcrumbs']['bootstrap_breadcrumb_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show current page title at end'),
    '#default_value' => theme_get_setting('bootstrap_breadcrumb_title'),
    '#description' => t('If your site has a module dedicated to handling breadcrumbs already, ensure this setting is disabled.'),
    '#states' => array(
      'invisible' => array(
        ':input[name="bootstrap_breadcrumb"]' => array('value' => 0),
      ),
    ),
  );

  // Navbar.
  // $form['components']['navbar'] = array(
  //   '#type' => 'fieldset',
  //   '#title' => t('Navbar'),
  //   '#collapsible' => TRUE,
  //   '#collapsed' => TRUE,
  // );
  // $form['components']['navbar']['bootstrap_navbar_position'] = array(
  //   '#type' => 'select',
  //   '#title' => t('Navbar Position'),
  //   '#description' => t('Select your Navbar position.'),
  //   '#default_value' => theme_get_setting('bootstrap_navbar_position'),
  //   '#options' => array(
  //     'static-top' => t('Static Top'),
  //     'fixed-top' => t('Fixed Top'),
  //     'fixed-bottom' => t('Fixed Bottom'),
  //   ),
  //   '#empty_option' => t('Normal'),
  // );
  // $form['components']['navbar']['bootstrap_navbar_inverse'] = array(
  //   '#type' => 'checkbox',
  //   '#title' => t('Inverse navbar style'),
  //   '#description' => t('Select if you want the inverse navbar style.'),
  //   '#default_value' => theme_get_setting('bootstrap_navbar_inverse'),
  // );

  // Region wells.
  // $wells = array(
  //   '' => t('None'),
  //   'well' => t('.well (normal)'),
  //   'well well-sm' => t('.well-sm (small)'),
  //   'well well-lg' => t('.well-lg (large)'),
  // );
  // $form['components']['region_wells'] = array(
  //   '#type' => 'fieldset',
  //   '#title' => t('Region wells'),
  //   '#description' => t('Enable the <code>.well</code>, <code>.well-sm</code> or <code>.well-lg</code> classes for specified regions. See: documentation on !wells.', array(
  //     '!wells' => l(t('Bootstrap Wells'), 'http://getbootstrap.com/components/#wells'),
  //   )),
  //   '#collapsible' => TRUE,
  //   '#collapsed' => TRUE,
  // );
  // foreach (system_region_list($theme) as $name => $title) {
  //   $form['components']['region_wells']['bootstrap_region_well-' . $name] = array(
  //     '#title' => $title,
  //     '#type' => 'select',
  //     '#attributes' => array(
  //       'class' => array('input-sm'),
  //     ),
  //     '#options' => $wells,
  //     '#default_value' => theme_get_setting('bootstrap_region_well-' . $name),
  //   );
  // }

  // JavaScript settings.
  $form['javascript'] = array(
    '#type' => 'fieldset',
    '#title' => t('JavaScript'),
    '#group' => 'bootstrap',
  );
  // Anchors.
  $form['javascript']['anchors'] = array(
    '#type' => 'fieldset',
    '#title' => t('Anchors'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['javascript']['anchors']['bootstrap_anchors_fix'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fix anchor positions'),
    '#default_value' => theme_get_setting('bootstrap_anchors_fix'),
    '#description' => t('Ensures anchors are correctly positioned only when there is margin or padding detected on the BODY element. This is useful when fixed navbar or administration menus are used.'),
  );
  $form['javascript']['anchors']['bootstrap_anchors_smooth_scrolling'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable smooth scrolling'),
    '#default_value' => theme_get_setting('bootstrap_anchors_smooth_scrolling'),
    '#description' => t('Animates page by scrolling to an anchor link target smoothly when clicked.'),
    '#states' => array(
      'invisible' => array(
        ':input[name="bootstrap_anchors_fix"]' => array('checked' => FALSE),
      ),
    ),
  );
  // Popovers.
  $form['javascript']['popovers'] = array(
    '#type' => 'fieldset',
    '#title' => t('Popovers'),
    '#description' => t('Add small overlays of content, like those on the iPad, to any element for housing secondary information.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['javascript']['popovers']['bootstrap_popover_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable popovers.'),
    '#description' => t('Elements that have the !code attribute set will automatically initialize the popover upon page load. !warning', array(
      '!code' => '<code>data-toggle="popover"</code>',
      '!warning' => '<strong class="error text-error">WARNING: This feature can sometimes impact performance. Disable if pages appear to "hang" after initial load.</strong>',
    )),
    '#default_value' => theme_get_setting('bootstrap_popover_enabled'),
  );
  $form['javascript']['popovers']['options'] = array(
    '#type' => 'fieldset',
    '#title' => t('Options'),
    '#description' => t('These are global options. Each popover can independently override desired settings by appending the option name to !data. Example: !data-animation.', array(
      '!data' => '<code>data-</code>',
      '!data-animation' => '<code>data-animation="false"</code>',
    )),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="bootstrap_popover_enabled"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_animation'] = array(
    '#type' => 'checkbox',
    '#title' => t('animate'),
    '#description' => t('Apply a CSS fade transition to the popover.'),
    '#default_value' => theme_get_setting('bootstrap_popover_animation'),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_html'] = array(
    '#type' => 'checkbox',
    '#title' => t('HTML'),
    '#description' => t("Insert HTML into the popover. If false, jQuery's text method will be used to insert content into the DOM. Use text if you're worried about XSS attacks."),
    '#default_value' => theme_get_setting('bootstrap_popover_html'),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_placement'] = array(
    '#type' => 'select',
    '#title' => t('placement'),
    '#description' => t('Where to position the popover. When "auto" is specified, it will dynamically reorient the popover. For example, if placement is "auto left", the popover will display to the left when possible, otherwise it will display right.'),
    '#default_value' => theme_get_setting('bootstrap_popover_placement'),
    '#options' => drupal_map_assoc(array(
      'top',
      'bottom',
      'left',
      'right',
      'auto',
      'auto top',
      'auto bottom',
      'auto left',
      'auto right',
    )),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_selector'] = array(
    '#type' => 'textfield',
    '#title' => t('selector'),
    '#description' => t('If a selector is provided, tooltip objects will be delegated to the specified targets. In practice, this is used to enable dynamic HTML content to have popovers added. See !this and !example.', array(
      '!this' => l(t('this'), 'https://github.com/twbs/bootstrap/issues/4215'),
      '!example' => l(t('an informative example'), 'http://jsfiddle.net/fScua/'),
    )),
    '#default_value' => theme_get_setting('bootstrap_popover_selector'),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_trigger'] = array(
    '#type' => 'checkboxes',
    '#title' => t('trigger'),
    '#description' => t('How a popover is triggered.'),
    '#default_value' => theme_get_setting('bootstrap_popover_trigger'),
    '#options' => drupal_map_assoc(array(
      'click',
      'hover',
      'focus',
      'manual',
    )),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_title'] = array(
    '#type' => 'textfield',
    '#title' => t('title'),
    '#description' => t("Default title value if \"title\" attribute isn't present."),
    '#default_value' => theme_get_setting('bootstrap_popover_title'),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_content'] = array(
    '#type' => 'textfield',
    '#title' => t('content'),
    '#description' => t("Default content value if \"data-content\" attribute isn't present."),
    '#default_value' => theme_get_setting('bootstrap_popover_content'),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_delay'] = array(
    '#type' => 'textfield',
    '#title' => t('delay'),
    '#description' => t('The amount of time to delay showing and hiding the popover (in milliseconds). Does not apply to manual trigger type.'),
    '#default_value' => theme_get_setting('bootstrap_popover_delay'),
  );
  $form['javascript']['popovers']['options']['bootstrap_popover_container'] = array(
    '#type' => 'textfield',
    '#title' => t('container'),
    '#description' => t('Appends the popover to a specific element. Example: "body". This option is particularly useful in that it allows you to position the popover in the flow of the document near the triggering element - which will prevent the popover from floating away from the triggering element during a window resize.'),
    '#default_value' => theme_get_setting('bootstrap_popover_container'),
  );
  // Tooltips.
  $form['javascript']['tooltips'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tooltips'),
    '#description' => t("Inspired by the excellent jQuery.tipsy plugin written by Jason Frame; Tooltips are an updated version, which don't rely on images, use CSS3 for animations, and data-attributes for local title storage. See !link for more documentation.", array(
      '!link' => l(t('Bootstrap tooltips'), 'http://getbootstrap.com/javascript/#tooltips'),
    )),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['javascript']['tooltips']['bootstrap_tooltip_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable tooltips'),
    '#description' => t('Elements that have the !code attribute set will automatically initialize the tooltip upon page load. !warning', array(
      '!code' => '<code>data-toggle="tooltip"</code>',
      '!warning' => '<strong class="error text-error">WARNING: This feature can sometimes impact performance. Disable if pages appear to "hang" after initial load.</strong>',
    )),
    '#default_value' => theme_get_setting('bootstrap_tooltip_enabled'),
  );
  $form['javascript']['tooltips']['bootstrap_tooltip_descriptions'] = array(
    '#type' => 'checkbox',
    '#title' => t('Convert simple form descriptions'),
    '#description' => t('Form items that contain simple descriptions (no HTML and shorter than 200 characters) will be converted into tooltips. This helps reduce the sometimes unnecessary noise of form item descriptions. By treating descriptions that contain longer text or HTML as actionable descriptions, we ensure it is always visible so its usability remains intact.'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_descriptions'),
    '#states' => array(
      'visible' => array(
        ':input[name="bootstrap_tooltip_enabled"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['javascript']['tooltips']['options'] = array(
    '#type' => 'fieldset',
    '#title' => t('Options'),
    '#description' => t('These are global options. Each tooltip can independently override desired settings by appending the option name to !data. Example: !data-animation.', array(
      '!data' => '<code>data-</code>',
      '!data-animation' => '<code>data-animation="false"</code>',
    )),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#states' => array(
      'visible' => array(
        ':input[name="bootstrap_tooltip_enabled"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_animation'] = array(
    '#type' => 'checkbox',
    '#title' => t('animate'),
    '#description' => t('Apply a CSS fade transition to the tooltip.'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_animation'),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_html'] = array(
    '#type' => 'checkbox',
    '#title' => t('HTML'),
    '#description' => t("Insert HTML into the tooltip. If false, jQuery's text method will be used to insert content into the DOM. Use text if you're worried about XSS attacks."),
    '#default_value' => theme_get_setting('bootstrap_tooltip_html'),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_placement'] = array(
    '#type' => 'select',
    '#title' => t('placement'),
    '#description' => t('Where to position the tooltip. When "auto" is specified, it will dynamically reorient the tooltip. For example, if placement is "auto left", the tooltip will display to the left when possible, otherwise it will display right.'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_placement'),
    '#options' => drupal_map_assoc(array(
      'top',
      'bottom',
      'left',
      'right',
      'auto',
      'auto top',
      'auto bottom',
      'auto left',
      'auto right',
    )),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_selector'] = array(
    '#type' => 'textfield',
    '#title' => t('selector'),
    '#description' => t('If a selector is provided, tooltip objects will be delegated to the specified targets.'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_selector'),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_trigger'] = array(
    '#type' => 'checkboxes',
    '#title' => t('trigger'),
    '#description' => t('How a tooltip is triggered.'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_trigger'),
    '#options' => drupal_map_assoc(array(
      'click',
      'hover',
      'focus',
      'manual',
    )),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_delay'] = array(
    '#type' => 'textfield',
    '#title' => t('delay'),
    '#description' => t('The amount of time to delay showing and hiding the tooltip (in milliseconds). Does not apply to manual trigger type.'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_delay'),
  );
  $form['javascript']['tooltips']['options']['bootstrap_tooltip_container'] = array(
    '#type' => 'textfield',
    '#title' => t('container'),
    '#description' => t('Appends the tooltip to a specific element. Example: "body"'),
    '#default_value' => theme_get_setting('bootstrap_tooltip_container'),
  );

  // Advanced settings.
  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced'),
    '#group' => 'bootstrap',
  );
  // BootstrapCDN.
  // $form['advanced']['bootstrap_cdn'] = array(
  //   '#type' => 'fieldset',
  //   '#title' => t('BootstrapCDN'),
  //   '#description' => t('Use !bootstrapcdn to serve the Bootstrap framework files. Enabling this setting will prevent this theme from attempting to load any Bootstrap framework files locally. !warning', array(
  //     '!bootstrapcdn' => l(t('BootstrapCDN'), 'http://bootstrapcdn.com', array(
  //       'external' => TRUE,
  //     )),
  //     '!warning' => '<div class="alert alert-info messages info"><strong>' . t('NOTE') . ':</strong> ' . t('While BootstrapCDN (content distribution network) is the preferred method for providing huge performance gains in load time, this method does depend on using this third party service. BootstrapCDN is under no obligation or commitment to provide guaranteed up-time or service quality for this theme. If you choose to disable this setting, you must provide your own Bootstrap source and/or optional CDN delivery implementation.') . '</div>',
  //   )),
  //   '#collapsible' => TRUE,
  //   '#collapsed' => TRUE,
  // );

  // $form['advanced']['bootstrap_cdn']['bootstrap_cdn'] = array(
  //   '#type' => 'select',
  //   '#title' => t('BootstrapCDN version'),
  //   '#options' => drupal_map_assoc(array(
  //     '3.0.0',
  //     '3.0.1',
  //     '3.0.2',
  //   )),
  //   '#default_value' => theme_get_setting('bootstrap_cdn'),
  //   '#empty_option' => t('Disabled'),
  //   '#empty_value' => NULL,
  // );

  // Bootswatch.
  // $bootswatch_themes = array();
  // $request = drupal_http_request('http://api.bootswatch.com/3/');
  // if ($request && $request->code === '200' && !empty($request->data)) {
  //   if (($api = drupal_json_decode($request->data)) && is_array($api) && !empty($api['themes'])) {
  //     foreach ($api['themes'] as $bootswatch_theme) {
  //       $bootswatch_themes[strtolower($bootswatch_theme['name'])] = $bootswatch_theme['name'];
  //     }
  //   }
  // }

  // $form['advanced']['bootstrap_cdn']['bootstrap_bootswatch'] = array(
  //   '#type' => 'select',
  //   '#title' => t('Bootswatch theme'),
  //   '#description' => t('Use !bootstrapcdn to serve a Bootswatch Theme. Choose Bootswatch theme here.', array(
  //     '!bootstrapcdn' => l(t('BootstrapCDN'), 'http://bootstrapcdn.com', array(
  //       'external' => TRUE,
  //     )),
  //   )),
  //   '#default_value' => theme_get_setting('bootstrap_bootswatch'),
  //   '#options' => $bootswatch_themes,
  //   '#empty_option' => t('Disabled'),
  //   '#empty_value' => NULL,
  //   '#suffix' => '<div id="bootswatch-preview"></div>',
  //   '#states' => array(
  //     'invisible' => array(
  //       ':input[name="bootstrap_cdn"]' => array('value' => ''),
  //     ),
  //   ),
  // );

  // if (empty($bootswatch_themes)) {
  //   $form['advanced']['bootstrap_cdn']['bootstrap_bootswatch']['#prefix'] = '<div class="alert alert-danger messages error"><strong>' . t('ERROR') . ':</strong> ' . t('Unable to reach Bootswatch API. Please ensure the server your website is hosted on is able to initiate HTTP requests.') . '</div>';
  // }



  // Rebuild registry.
  $form['advanced']['bootstrap_rebuild_registry'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rebuild theme registry on every page.'),
    '#default_value' => theme_get_setting('bootstrap_rebuild_registry'),
    '#description' => t('During theme development, it can be very useful to continuously !rebuild. !warning', array(
      '!rebuild' => l(t('rebuild the theme registry'), 'http://drupal.org/node/173880#theme-registry'),
      '!warning' => '<div class="alert alert-warning messages warning"><strong>' . t('WARNING') . ':</strong> ' . t('This is a huge performance penalty and must be turned off on production websites. ') . '</div>',
    )),
  );
  // Suppress jQuery message.
  $form['advanced']['bootstrap_toggle_jquery_error'] = array(
    '#type' => 'checkbox',
    '#title' => t('Suppress jQuery version error message'),
    '#default_value' => theme_get_setting('bootstrap_toggle_jquery_error'),
    '#description' => t('Enable this if the version of jQuery has been upgraded to 1.7+ using a method other than the !jquery_update module.', array(
      '!jquery_update' => l(t('jQuery Update'), 'https://drupal.org/project/jquery_update'),
    )),
  );

  // $xtheme = biotic_get_theme();


  // Rebuild registry.
  $form['advanced']['debug_mode'] = array(
    '#type' => 'checkbox',
    '#title' => t('Debug mode'),
    '#default_value' => theme_get_setting('debug_mode')
  );

  $form['advanced']['layout_builder_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout builder output'),
    '#weight' => 5,
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );


  $form['advanced']['layout_builder_container']['layoutbuilder_output'] = array(
    // '#type' => 'container',
    '#markup' => "<pre>". biotic_get_theme()->themeInfo()."</pre>"
  );




}
