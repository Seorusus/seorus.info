<?php

// if (module_exists('devel_themer')) {
//   drupal_set_message(t("Theme developer module is active.
//     Theme settings <strong>turned off</strong> in order to prevent an unexpected problem.
//     Please deactivate the Theme Developer module and try again.
//     "), 'warning', FALSE);
// }


/**
 * Implements hook_form_FORM_ID_alter().
 */
function zenon_form_system_theme_settings_alter(&$form, $form_state, $form_id = NULL) {

  global $base_url;


  // Work-around for a core bug affecting admin themes.
  // @see https://drupal.org/node/943212
  if (isset($form_id)) {
    return;
  }


  drupal_add_css( drupal_get_path('theme','zenon'). "/assets/css/zenon-custom-admin.css", array('weight' => 999, 'group' => CSS_THEME ) );


    // Components.
  $form['svg'] = array(
    '#type' => 'fieldset',
    '#title' => t('SVG Fallbacks'),
    '#group' => 'bootstrap',
    // '#weight' => -1
  );

  $svg_path =  theme_get_setting('logo_svg_path');
  $attributes_class = "";
  if ( theme_get_setting('logo_svg_path') ) {
    $attributes_class = (file_exists($svg_path)) ? NULL : "error";
  }


  $form['svg']['logo_svg_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Logo SVG path'),
    '#default_value' => theme_get_setting('logo_svg_path'),
    '#size' => 60,
    '#maxlength' => 256,
    // '#required' => TRUE,
    '#attributes' => array(
      'class' => array($attributes_class)
    )

  );

  if ( theme_get_setting('logo_svg_path') ) {

    if ( file_exists($svg_path) ) {

      $form['svg']['logo_svg_media'] = array(
        '#markup' => t('<img src="/!path" alt="Logo SVG Path" />',
          array(
            '!path' => theme_get_setting('logo_svg_path')
          )
        ),
      );

    } else {

      $message = t('<b>Logo SVG file</b> does not exist! Please make sure the path is correct and file is accessible. ( <b>%path</b> )',
        array(
          '%path' => theme_get_setting('logo_svg_path')
        )
      );

      drupal_set_message($message, 'error');

    }


  }


}
