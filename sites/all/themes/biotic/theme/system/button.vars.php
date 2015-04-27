<?php
/**
 * @file
 * button.vars.php
 */

/**
 * Implements hook_preprocess_button().
 */
function biotic_preprocess_button(&$vars) {
  $vars['element']['#attributes']['class'][] = 'btn';
  if (isset($vars['element']['#value'])) {
    if ($class = _biotic_colorize_button($vars['element']['#value'])) {
      $vars['element']['#attributes']['class'][] = $class;
    }
  }
}
