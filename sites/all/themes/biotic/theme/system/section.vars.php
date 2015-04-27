<?php

/**
 * [template_preprocess_section description]
 * @param  [type] $vars [description]
 * @return [type]       [description]
 */
function template_preprocess_section(&$vars) {

  // dpm( $vars['elements']['#data'] );

  $vars['theme_hook_suggestions'][] = 'section__' . $vars['elements']['#data']['key'];
  $vars['section'] = $vars['elements']['#section'];
  $vars['key']     = $vars['elements']['#data']['key'];
  $vars['id']      = drupal_html_id( Biotic::camelCase($vars['key']) );
  $vars['title']   = $vars['elements']['#data']['section_name'];
  $vars['content'] = $vars['elements']['#children'];

  //Settings
  $vars['fluid']   = $vars['elements']['#data']['fluid'];

  // WRAPPER
  $vars['wrapper_attributes_array']['id'] = drupal_html_id(Biotic::camelCase($vars['key'])."Wrapper");
  $vars['wrapper_attributes_array']['class'][] = "wrapper";
  $vars['wrapper_attributes_array']['class'][] = "clearfix";

  // PARALLAX SETTINGS

  if ( $vars['elements']['#data']['background'] && $vars['elements']['#data']['background_type'] == "parallax"  ) {

    $vars['wrapper_attributes_array']['class'][] = "parallax-section";

    // data-bottom-top="background-position: 0% 0px; opacity:0;"
    // data-center="background-position: 50% 0px; opacity:1;"
    // data-top-bottom="background-position: -50% 0px; opacity:0"

    // $vars['wrapper_attributes_array']['data-bottom-top'][]  = "background-position: 50% 0%; ";
    // $vars['wrapper_attributes_array']['data-center'][]      = "background-position: 33% 0%; ";
    // $vars['wrapper_attributes_array']['data-top-bottom'][]  = "background-position: 50% 50%; ";



    // Parallax Speed
    if ( $vars['elements']['#data']['parallax_xpos'] ) {
      // $parallax_xpos = $vars['elements']['#data']['parallax_xpos'];
      $vars['wrapper_attributes_array']['data-parallax-xpos'][] = $vars['elements']['#data']['parallax_xpos'];
    }

    // Parallax Ratio
    if ( $vars['elements']['#data']['parallax_ratio'] ) {
      // $parallax_ratio = $vars['elements']['#data']['parallax_ratio'];
      $vars['wrapper_attributes_array']['data-parallax-ratio'][] = $vars['elements']['#data']['parallax_ratio'];;

    }


  }


  //Section Container ID
  $vars['attributes_array']['id'] = drupal_html_id(Biotic::camelCase($vars['key'])."Container");



  if($vars['fluid'] == "on"){
    $vars['attributes_array']['class'][] = "container-fluid";
    $vars['attributes_array']['class'][] = "clearfix";
  }else {
    $vars['attributes_array']['class'][] = "container";
  }


  // ------------------------------
  // Add Custom Classes to Wrapper
  // ------------------------------
  if ( isset($vars['elements']['#data']['custom_classes']) ) {
    $vars['wrapper_attributes_array']['class'][] = $vars['elements']['#data']['custom_classes'];
  }

  // ------------------------------
  // Add Responsive Classes
  // ------------------------------
  if ( isset($vars['elements']['#data']['hidden']) || isset($vars['elements']['#data']['visible']) ) {

    if ( isset($vars['elements']['#data']['hidden']) ) {
      $hidden = $vars['elements']['#data']['hidden'];
      if ( is_array($hidden) ) {
        foreach ($hidden as &$value){$value = 'hidden-'.$value;}
        $hidden = implode(" ", $hidden);
      } else {
        $hidden = "hidden-".$hidden;
      }

      // Add Hidden Classes
      $vars['wrapper_attributes_array']['class'][] = $hidden;

    }

    if ( isset($vars['elements']['#data']['visible']) ) {
      $visible = $vars['elements']['#data']['visible'];
      if ( is_array($visible) ) {
        foreach ($visible as &$value){$value = 'visible-'.$value;}
        $visible = implode(" ", $visible);
      } else {
        $visible = "visible-".$visible;
      }

      // $visible = ( is_array($visible) ) ? implode(" visible-", $visible) : "visible-".$visible;

      // Add visible Classes
      $vars['wrapper_attributes_array']['class'][] = $visible;

    }


  }

}



function template_process_section(&$vars) {

  $vars['wrapper_attributes'] = drupal_attributes( $vars['wrapper_attributes_array'] );
  $data = $vars['elements']['#data'];
  $data['id_wrapper'] = $vars['wrapper_attributes_array']['id'];
  $data['id_container'] = $vars['attributes_array']['id'];
  // dpm($data);
  // $theme = biotic_get_theme();

  // $css = $theme->sectionCSS($data);

  // $theme->set_section_css($css);

  // Biotic::sectionCSS($data);
  // $css = false;

  // dpm($css);

  // if ($css) {
  //   # code...
  //   drupal_add_css($css,array(
  //       'group' => CSS_THEME,
  //       'type' => 'inline',
  //       'media' => 'all',
  //       'preprocess' => FALSE,
  //       'weight' => '9999',
  //     ));
  // }

}
