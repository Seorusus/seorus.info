<?php
/**
 * @file
 * region.vars.php
 */

/**
 * Implements hook_preprocess_region().
 */
function biotic_preprocess_region(&$variables) {
  // global $theme;
  // static $wells;

  // if (!isset($wells)) {
  //   foreach (system_region_list($theme) as $name => $title) {
  //     $wells[$name] = theme_get_setting('bootstrap_region_well-' . $name);
  //   }
  // }

  // debug($variables);

  switch ($variables['region']) {
    // @todo is this actually used properly?
    // case 'content':
    //   $variables['theme_hook_suggestions'][] = 'region__no_wrapper';
    //   break;

    case 'help':
      $variables['content'] = _biotic_icon('question-sign') . $variables['content'];
      $variables['classes_array'][] = 'alert';
      $variables['classes_array'][] = 'alert-info';
      break;
  }

  // if (!empty($wells[$variables['region']])) {
  //   $variables['classes_array'][] = $wells[$variables['region']];
  // }

  if (isset($variables['elements']['#data'])) {
    //xs,sm,md,lg
    $variables['xscolumns'] = $variables['elements']['#data']['colxs'];
    $variables['smcolumns'] = $variables['elements']['#data']['colsm'];
    $variables['mdcolumns'] = $variables['elements']['#data']['colmd'];
    $variables['lgcolumns'] = $variables['elements']['#data']['collg'];
    $variables['key']       = $variables['elements']['#data']['key'];

    $variables['clearfix']  = false;

    if($variables['xscolumns']){
      $variables['classes_array'][] = "col-xs-".$variables['xscolumns'];
    }

    if($variables['smcolumns']){
      $variables['classes_array'][] = "col-sm-".$variables['smcolumns'];
    }

    if($variables['mdcolumns']){
      $variables['classes_array'][] = "col-md-".$variables['mdcolumns'];
    }

    if($variables['lgcolumns']){
      $variables['classes_array'][] = "col-lg-".$variables['lgcolumns'];
    }

    if ( isset($variables['elements']['#data']['clearfix']) ) {
        // Clearfix
        if ( is_array( $variables['elements']['#data']['clearfix'] ) ) {

          $variables['clearfix']  = 3;

        } else{

          if ( $variables['elements']['#data']['clearfix'] == "after" ) {
            $variables['clearfix']  = 2;
          }
          if ( $variables['elements']['#data']['clearfix'] == "before" ) {
            $variables['clearfix']  = 1;
          }

        }
    }

    // ------------------------------
    // Add Custom Classes to Wrapper
    // ------------------------------
    if ( isset($variables['elements']['#data']['custom_classes']) ) {
      $variables['classes_array'][] = $variables['elements']['#data']['custom_classes'];
    }

    // -----------------------------
    // Add Animations and Delay
    // -----------------------------

    if (!empty($variables['elements']['#data']['animation'])) {
      $variables['attributes_array']['data-animation'][] = $variables['elements']['#data']['animation'];
      $variables['classes_array'][] = "wow ".$variables['elements']['#data']['animation'];
    }
    if (!empty($variables['elements']['#data']['delay'])) {
      $variables['attributes_array']['data-delay'][] = $variables['elements']['#data']['delay'];
      $variables['attributes_array']['data-wow-delay'][] = ($variables['elements']['#data']['delay']/1000)."s";
    }

  }

   switch ($variables['region']) {
    case 'content':
      # code...
      $theme = biotic_get_theme();
      // Remove Column Classes
      $variables['classes_array'] = array_filter($variables['classes_array'], 'filterCol');
      $cols = $theme->calc_column();

      $variables['classes_array'][] =  "col-xs-".$cols['xs'];
      $variables['classes_array'][] =  "col-sm-".$cols['sm'];
      $variables['classes_array'][] =  "col-md-".$cols['md'];
      $variables['classes_array'][] =  "col-lg-".$cols['lg'];

      break;
   }

}

/**
 * Array Filter [filterCol description]
 * @param  [type] $elem [description]
 * @return [type]       [description]
 */
function filterCol($elem) {
    return strpos($elem, 'col-') === false;
}


/**
 * Implement hook_process_region
 */
function biotic_process_region(&$vars) {
  $theme = biotic_get_theme();

  switch ($vars['elements']['#region']) {
    case 'content':
      $vars['action_links'] = $theme->page['action_links'];
      $vars['breadcrumb']   = $theme->page['breadcrumb'];
      $vars['feed_icons']   = $theme->page['feed_icons'];
      $vars['messages']     = $theme->page['messages'];
      $vars['tabs']         = $theme->page['tabs'];
      $vars['title']        = $theme->page['title'];
      $vars['title_prefix'] = $theme->page['title_prefix'];
      $vars['title_suffix'] = $theme->page['title_suffix'];
    break;

    case 'navigation':
      # code...
      $vars['primary_nav']    = $theme->page['primary_nav'];
      $vars['secondary_nav']  = $theme->page['secondary_nav'];
      $vars['logo']           = $theme->page['logo'];
      $vars['site_name']      = $theme->page['site_name'];
      $vars['navbar_classes'] = $theme->page['navbar_classes'];
      $vars['front_page']     = $theme->page['front_page'];

      break;
  }

}
