<?php
/**
 * @file
 * block.vars.php
 */

/**
 * Implements hook_preprocess_block().
 */
function biotic_preprocess_block(&$variables) {
  // Use a bare template for the page's main content.
  if ($variables['block_html_id'] == 'block-system-main') {
    $variables['theme_hook_suggestions'][] = 'block__no_wrapper';
  }
  $variables['title_attributes_array']['class'][] = 'block-title';

  // $valve = array();
  // $block = $variables['block'];
  // $cols  = array('col-xs-','col-sm-','col-md-','col-lg-');
  
  // foreach ($cols as $value) {
  //   for ($i=1; $i <= 12 ; $i++) {
  //     $valve[] = $value.$i;
  //   }
  // }


  // if ($block->css_class) {
  //   $pieces = explode(" ", $block->css_class);
  //   if (!array_intersect($valve, $pieces)  ) {
  //     // dpm($variables);
  //     $variables['classes_array'][] = 'col-sm-12';
  //   }
  // }else{
  //     $variables['classes_array'][] = 'col-sm-12';
  // }

}

/**
 * Implements hook_process_block().
 */
function biotic_process_block(&$variables) {
  // Drupal 7 should use a $title variable instead of $block->subject.
  $variables['title'] = $variables['block']->subject;
}
