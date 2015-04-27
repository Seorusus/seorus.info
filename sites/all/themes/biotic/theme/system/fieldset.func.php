<?php
/**
 * @file
 * fieldset.func.php
 */

/**
 * Overrides theme_fieldset().
 */
function biotic_fieldset($variables) {
  return theme('bootstrap_panel', $variables);
}
