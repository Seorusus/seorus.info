<?php

/**
 * Implements hook_libraries_info().
 */
// function zenon_libraries_info() {
// 	$libraries = array();
// 	$libraries['masonry'] = array(
// 		'name' => 'Masonry',
// 		'files' => array(
// 			'js'  => array( 'jquery.masonry.min.js' ),
// 			// 'css' => array( 'dist/magnific-popup.css' ),
// 		),
// 	);
// 	return $libraries;
// }

require_once('includes/icon.vars.php');
require_once('includes/icons.inc');

/**
 * Implements hook_preprocess_html().
 *
 * @param  [type] $variables [description]
 * @param  [type] $hook      [description]
 * @return [type]            [description]
 */
function zenon_preprocess_html(&$variables, $hook) {

  // Return early, so the maintenance page does not call any of the code below.
  if ($hook != 'html') {
    return;
  }

  // Add $grddl_profile as link-tag.
  drupal_add_html_head_link(array(
    'rel' => 'profile',
    'href' => $variables['grddl_profile'],
  ));

  // Serialize RDF Namespaces into an RDFa 1.1 prefix attribute.
  if ($variables['rdf_namespaces']) {
    $prefixes = array();
    foreach (explode("\n  ", ltrim($variables['rdf_namespaces'])) as $namespace) {
      // Remove xlmns: and ending quote and fix prefix formatting.
      $prefixes[] = str_replace('="', ': ', substr($namespace, 6, -1));
    }
    $variables['rdf_namespaces'] = ' prefix="' . implode(' ', $prefixes) . '"';
  }


}


// function zenon_rdf_namespaces() {
//
//   // Serialize RDF Namespaces into an RDFa 1.1 prefix attribute.
//   if ($variables['rdf_namespaces']) {
//     $prefixes = array();
//     foreach (explode("\n  ", ltrim($variables['rdf_namespaces'])) as $namespace) {
//       // Remove xlmns: and ending quote and fix prefix formatting.
//       $prefixes[] = str_replace('="', ': ', substr($namespace, 6, -1));
//     }
//     $variables['rdf_namespaces'] = ' prefix="' . implode(' ', $prefixes) . '"';
//   }
//
//
//
//   return array(
//     prefix
//     // 'content' => 'http://purl.org/rss/1.0/modules/content/',
//     // 'dc' => 'http://purl.org/dc/terms/',
//     // 'foaf' => 'http://xmlns.com/foaf/0.1/',
//     // 'og' => 'http://ogp.me/ns#',
//     // 'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
//     // 'sioc' => 'http://rdfs.org/sioc/ns#',
//     // 'sioct' => 'http://rdfs.org/sioc/types#',
//     // 'skos' => 'http://www.w3.org/2004/02/skos/core#',
//     // 'xsd' => 'http://www.w3.org/2001/XMLSchema#',
//   );
// }


function zenon_theme() {

  $theme = array(
    'post_submitted' => array(
      'arguments' => array('node' => NULL),
      'template' => 'submitted',
      'path' => drupal_get_path('theme', 'zenon').'/templates',
      'preprocess functions' => array(
          'template_preprocess',
          'template_preprocess_post_submitted',
      ),
      'process functions' => array(
          'template_process',
          'template_process_post_submitted',
      ),
    )
  );

  return $theme;
}

/**
 * [template_preprocess_post_submitted description]
 * @param  [type] $variables [description]
 * @return [type]            [description]
 */
function template_preprocess_post_submitted(&$variables) {
  $node = $variables;
  $variables['username'] = $node['name'];
  $variables['datetime'] = format_date($node['created'], 'custom', 'F j, Y');
}


/**
 * Implements theme_menu_tree__menu_block().
 */
function zenon_menu_tree__menu_top_menu(&$variables) {
  return '<ul class="nav navbar-nav n-size-sm pull-right">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_menu_tree__menu_block().
 */
function zenon_menu_tree__menu_main_mobile(&$variables) {
  return '<ul class="nav navbar-nav xn-size-sm xpull-right">' . $variables['tree'] . '</ul>';
}



/**
 * Implements hook_preprocess_page().
 */
function zenon_preprocess_page(&$variables) {

    /* DEBUG MODE VARIABLE ADDED TO DRUPAL.SETTIGS JAVASCRIPT */
    drupal_add_js( array('zenon'=>array('debug' => theme_get_setting('debug_mode') )), 'setting' );


    /* SVG FALLBACKS */
    if ( theme_get_setting('logo_svg_path') && file_exists( theme_get_setting('logo_svg_path') )  ) {
      drupal_add_js( array('zenon'=>array('logo_svg_path' => theme_get_setting('logo_svg_path') )), 'setting' );
    }

    // drupal_add_library('system', 'ui');
    // drupal_add_library('system', 'ui.sortable');
    // drupal_add_library('system','effects');

    // dpm($variables);

}

function zenon_preprocess_maintenance_page(&$variables){

  // $variables['logo_w_svg'] = drupal_get_path('theme','zenon'). "/assets/images/logo-white.svg";

  // $variables['logo_svg_path'] = (theme_get_setting('logo_svg_path')) ? theme_get_setting('logo_svg_path') : FALSE;


  /* SVG FALLBACKS */
  if ( theme_get_setting('logo_svg_path') && file_exists( theme_get_setting('logo_svg_path') )  ) {
    drupal_add_js( array('zenon'=>array('logo_svg_path' => theme_get_setting('logo_svg_path') )), 'setting' );
  }

  // $image = drupal_get_path('theme','zenon'). "/assets/images/maintenance-background.jpg";

  // $variables['attributes_array']['style'][] = 'background:red;';
  // $variables['attributes_array']['style'][] = 'background-image: url('.$image.');';
  // $variables['attributes_array']['style'][] = 'background-size: cover';

  // dpm($variables);

}

/**
 * [zenon_page_alter description]
 * @param  [type] $page [description]
 * @return [type]       [description]
 */
function zenon_page_alter(&$page) {
  $item = menu_get_item();

  if($item['path'] == 'admin/structure/block/demo/zenon' ) {

    if (!$item['access']) {
      // @TODO: BETTER ACCESS CONTROL
      // drupal_access_denied();
      module_invoke_all('exit');
      exit();
    }

    foreach ( $page['data'] as $key => $section) {
      $page['data'][$key]['#demostration'] = TRUE;
    }

  }

  /* SET THE DEBUG MODE VARIABLE */
  if ( theme_get_setting('debug_mode') ) {
    if ( isset($page['data']) ) {
      foreach ( $page['data'] as $key => $section) {
        $page['data'][$key]['#debug_mode'] = TRUE;
      }
    }
  }


}


function zenon_form_user_login_block_alter( &$form, &$form_state, $form_id ) {

  $form['name']['#title_display'] = 'invisible';
  $form['name']['#attributes']['placeholder'] = t( 'Username' );
  $form['pass']['#title_display'] = 'invisible';
  $form['pass']['#attributes']['placeholder'] = t( 'Password' );

  $form['actions']['submit']['#value' ] = t('Sign In');
  $form['actions']['submit']['#attributes' ]['class'] = array("btn-success","btn-sm") ;

  // $form['pass']['#field_suffix']= l(t('Forgot?'), 'user/password',array('attributes' => array('class'=>'btn btn-default') ) );

  if (variable_get('user_register', USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL)) {
    $markup = l(t('Sign Up'), 'user/register', array('attributes' => array('title' => t('Create a new user account.'), 'class' => 'btn btn-sm btn-default register-link')));
    $markup .= l(t('Forgot?'), 'user/password', array('attributes' => array('title' => t('Forgot your password?'), 'class'=>'btn btn-sm btn-default') ) );

  }else{
    // $markup = "";
    $markup = l(t('Forgot?'), 'user/password', array('attributes' => array('title' => t('Request new password via e-mail.'), 'class'=>'btn btn-sm btn-default') ) );
  }

  // $markup .= " " . l(t('Forgot your password?'), 'user/password', array('attributes' => array('title' => t('Request new password via e-mail.'),'class'=>'btn')));
  //
  $markup .= render($form['actions']);

  $markup = '<div class="btn-group clearfix">' . $markup . '</div>';
  $form['links']['#markup'] = $markup;


}

/**
 * [zenon_preprocess_views_view description]
 * @param  [type] $vars [description]
 * @return [type]       [description]
 */
function zenon_preprocess_views_view(&$vars) {

  // dpm($vars);

  $view = &$vars['view'];
  // Make sure it's the correct view
  if ($view->name == 'portfolio_masonry') {
    // $library = libraries_detect('masonry');
    // $version = $library['version'];
    // libraries_load('masonry');
  }


  if (isset($vars['view']->name)) {
    $function = 'zenon_preprocess_views_view__'.$vars['view']->name;
    if (function_exists($function)) {
     $function($vars);
    }
  }

}

/**
 * [zenon_form_alter description]
 * @param  [type] $form       [description]
 * @param  [type] $form_state [description]
 * @param  [type] $form_id    [description]
 * @return [type]             [description]
 */
function zenon_form_alter(&$form, &$form_state, $form_id) {

  if ( module_exists('views') ) {
    $view = views_get_current_view();

    if ( isset($view->name)  && $view->name == "faq") {
      $form['search']['#attributes']['placeholder'] = t("How can we help?");
      $form['search']['#attributes']['class'][] = "input-lg";

      // SUBMIT BUTTON
      $form['submit']['#attributes']['class'][] = "btn-lg btn-default";
      $form['submit']['#value'] = "Search";
      // dpm($form);
    }
  }
}

/**
 * [template_preprocess_views_exposed_form description]
 * @param  [type] $vars [description]
 * @return [type]       [description]
 */
// function zenon_preprocess_views_exposed_form(&$vars) {
//   $view = views_get_current_view();
//   // dpm($view);
//   if ($view->name == "faq") {
//
//     $vars['form']['title']['#attributes'] = array(
//
//       'placeholder' => 'Salla gitsin'
//
//     );
//
//     // ['placeholder'] = "Salla gitsin";
//     //
//     $vars['form']['title']['#attributes']['class'][] = "Salla gitsin";
//     dpm($vars);
//
//
//   }
//
//
// }


function zenon_preprocess_node(&$variables) {
    // Portfolio type variables
  if ($variables['type'] == "portfolio") {

    if( $variables['view_mode'] == 'teaser') {
      $variables['theme_hook_suggestions'][] = 'node__' . "portfolio". '__teaser';
      // $variables['theme_hook_suggestions'][] = 'node__' . "portfolio". '__teaser__col4';
      // $variables['theme_hook_suggestions'][] = 'node__' . "portfolio". '__column_one';
    }

    $node = node_load( $variables['nid'] );
    $field = field_get_items('node', $node, 'field_portfolio_layout');
    $field_value = field_view_value('node', $node, 'field_portfolio_layout', $field[0]);
    $layout = render($field_value);

    if (!empty($layout)) {
      $template = strtolower( str_replace(' ', '_', trim($layout) ) );
      $variables['classes_array'][] = "portfolio-".$template;

      $variables['theme_hook_suggestions'][] = 'node__' . "portfolio_" .$template;
      if( $variables['view_mode'] == 'teaser') {
        $variables['theme_hook_suggestions'][] = 'node__' . "portfolio_". $template . '__teaser';
      }
      if( $variables['view_mode'] == 'full') {
        $variables['theme_hook_suggestions'][] = 'node__' . "portfolio_". $template . '__full';
      }
    }

  }

  if ($variables['type'] == "blogs") {

    if( $variables['view_mode'] == 'teaser') {
      $variables['theme_hook_suggestions'][] = 'node__' . "blogs". '__teaser';
    }

    $blog_node = node_load( $variables['nid'] );
    $field = field_get_items('node', $blog_node, 'field_blog_format');
    $field_value = field_view_value('node', $blog_node, 'field_blog_format', $field[0]);
    $format = render($field_value);

    if (!empty($format)) {
      $format = strtolower( str_replace(' ', '_', trim($format) ) );
      $variables['theme_hook_suggestions'][] = 'node__' . "blogs_" .$format;
      $variables['classes_array'][] = $format."-blog";
      if( $variables['view_mode'] == 'teaser') {
        $variables['theme_hook_suggestions'][] = 'node__' . "blogs_". $format . '__teaser';
      }
    }

  }

  if ( $variables['type'] == "testimonials" ) {
    if( $variables['view_mode'] == 'teaser') {
      $variables['theme_hook_suggestions'][] = 'node__' . "testimonials". '__teaser';
    }
  }

  /* PRICING PLANS */
  if ( $variables['type'] == "pricing_plan" ) {
    $variables['title_attributes_array']['class'][] = "pricing-plan--heading";
    $variables['title_attributes_array']['class'][] = "heavy";

    if( $variables['field_pricing_plan_highlighted'][0]['value'] ) {
      $variables['classes_array'][] = 'highlighted';
    }

    if(  $variables['field_pricing_plan_highlighted'][0]['value'] ) {
      $variables['highlighted'] = TRUE;
    } else {
      $variables['highlighted'] = FALSE;
    }

    // Add Color scheme class
    if( isset($variables['field_pricing_plan_color_scheme'][0]['value'] ) ){
      $color_scheme =  Biotic::get_html_id( $variables['field_pricing_plan_color_scheme'][0]['value'] );
      $variables['classes_array'][] = 'scheme-'.$color_scheme;
    }

  }

  /* Better Submitted */
  $variables['post_submitted'] = theme('post_submitted', $variables);

  /* Better Readmore */
  $button_class = "btn btn-xs btn-flat";
  $add_comment_icon = 'fa fa-plus';

   if (isset($variables['content']['links']['node']['#links']['node-readmore'])) {
     $variables['content']['links']['node']['#links']['node-readmore']['attributes']['class'][] = $button_class;
   }
   if (isset($variables['content']['links']['comment']['#links']['comment-add'])) {
    //  $variables['content']['links']['comment']['#links']['comment-add']['html'] = true;
    //  $variables['content']['links']['comment']['#links']['comment-add']['title'] = t('<i class="@icon"></i> Add Comment',array( '@icon' => $add_comment_icon ) );
     $variables['content']['links']['comment']['#links']['comment-add']['attributes']['class'][] = $button_class;
   }
   if (isset($variables['content']['links']['comment']['#links']['comment-comments'])) {
     $variables['content']['links']['comment']['#links']['comment-comments']['attributes']['class'][] = $button_class;
   }
   if (isset($variables['content']['links']['comment']['#links']['comment-new-comments'])) {
     $variables['content']['links']['comment']['#links']['comment-new-comments']['attributes']['class'][] = $button_class;
   }

}

function  zenon_preprocess_comment_wrapper(&$variables) {
  // dpm($variables,"Wrappers");
  $variables['classes_array'][] = "panel panel-default";
}

function zenon_preprocess_comment(&$variables) {
  $variables['classes_array'][] = "media";
  // dpm($variables);

}

function zenon_preprocess_user_picture(&$variables) {
  $account = $variables['account'];

  if( isset( $account->type ) ) {
    // dpm( $account->type, "Type added");
    $variables['theme_hook_suggestions'][] = 'user_picture__'.$account->type;
  }

  if ( isset( $account->node_type ) ) {
    if ( $account->node_type == 'comment_node_blogs' ) {
      $variables['theme_hook_suggestions'][] = 'user_picture__comment_node_blogs';
    }
  }

  // dpm($variables);

}


/**
   * Implementation of hook_link_alter
   */
  function zenon_comment_view_alter(&$build) {

    // if ( isset( $build['links']) ) {
    //   $build['links']['#attributes']['class'][] = "text-right";
    // }

    // comment-delete
    if (isset($build['links']['comment']['#links']['comment-delete']) ) {
      $build['links']['comment']['#links']['comment-delete']['title'] = '<i class="fa fa-times"></i> &nbsp;' . t('Delete');
      // $build['links']['comment']['#links']['comment-delete']['attributes']['class'][] = "btn btn-xs btn-flat";
    }
    // comment-edit
    if (isset($build['links']['comment']['#links']['comment-edit']) ) {
      $build['links']['comment']['#links']['comment-edit']['title'] = '<i class="fa fa-pencil-square-o"></i> &nbsp;' . t('Edit');
      // $build['links']['comment']['#links']['comment-edit']['attributes']['class'][] = "btn btn-xs btn-flat";
    }
    // comment-reply
    if (isset($build['links']['comment']['#links']['comment-reply']) ) {
      $build['links']['comment']['#links']['comment-reply']['title'] = '<i class="fa fa-reply"></i> &nbsp;' . t('Reply');
      // $build['links']['comment']['#links']['comment-reply']['attributes']['class'][] = "btn btn-xs btn-flat";
    }
    // comment-approve
    if (isset($build['links']['comment']['#links']['comment-approve']) ) {
      $build['links']['comment']['#links']['comment-approve']['title'] = '<i class="fa fa-thumbs-o-up"></i> &nbsp;' . t('Approve');
      // $build['links']['comment']['#links']['comment-approve']['attributes']['class'][] = "btn btn-xs btn-flat";
    }

    // dpm($build,"build");

  }

// function zenon_preprocess_image_style(&$variables) {
//   $variables['attributes']['class'][] = 'img-responsive'; // can be 'img-rounded', 'img-circle', or 'img-thumbnail'
// }

/**
 * [zenon_process_views_view__blogs description]
 * @param  [type] $variables [description]
 * @return [type]            [description]
 */
// function zenon_preprocess_views_view__blogs(&$variables){
//   $fields = $variables['view']->field;
//   dpm($fields);
// }

function zenon_node($node, $mode = 'n') {
  if (!function_exists('prev_next_nid')) {
    return NULL;
  }

  $icon_p = $icon_n = "";

  switch($mode) {
    case 'prev':
      $n_nid = prev_next_nid($node->nid, 'prev');
      // $link_text = 'previous';
      $icon_p = ' <i class="fa fa-long-arrow-left"></i> ';
      // $icon_p = '&larr;';
      break;

    case 'next':
      $n_nid = prev_next_nid($node->nid, 'next');
      // $link_text = 'next';
      $icon_n = ' <i class="fa fa-long-arrow-right"></i> ';
      // $icon_n = '&rarr;';
      break;

    default:
      return NULL;
  }

  if ($n_nid) {
    $n_node = node_load($n_nid);

    $options = array(
      'attributes' => array('class' => 'btn btn-flat'),
      'html'  => TRUE,
    );
    switch($n_node->type) {
      // For image nodes only
      case 'portfolio':
        $link_text = $icon_p.$n_node->title.$icon_n;
        $html = l($link_text, "node/$n_nid", $options );
        return $html;
      default:
        // Add other node types here if you want.
    }
  }

}


function zenon_preprocess_section(&$vars) {

  if( isset( $vars['elements']['#demostration'] ) ) {
    // dpm("THIS IS THE DEMO SECTIONS");
    $vars['demostration'] = true;
  }else{
    $vars['demostration'] = false;
  }

  if( isset( $vars['elements']['#debug_mode'] ) ) {
    // dpm("THIS IS THE DEMO SECTIONS");
    $vars['debug'] = true;
  }else{
    $vars['debug'] = false;
  }

}

/**
 * Implements theme_double_field().
 */
// function zenon_double_field($vars) {
//   $element = $vars['element'];
//   $settings = $element['#display']['settings'];
//
//   if ($settings['style'] == 'link') {
//     $output = l($element['#item']['first'], $element['#item']['second']);
//   }
//   else {
//     $output = "";
//     $class = $settings['style'] == 'block' ? 'clearfix' : 'container-inline';
//     // $output = '<div class="' . $class . '">';
//     $output .= '<span class="double-field-first">'  . $element['#item']['first'] . '</span>';
//     $output .= '<span class="double-field-second">' . $element['#item']['second'] . '</span>';
//     // $output .= '</div>';
//   }
//   return $output;
// }
