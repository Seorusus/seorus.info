<?php
/**
 * @file
 * menu-tree.func.php
 */

/**
 * Overrides theme_menu_tree().
 */
function biotic_menu_tree(&$variables) {
  return '<ul class="menu nav">' . $variables['tree'] . '</ul>';
}

/**
 * Bootstrap theme wrapper function for the primary menu links.
 */
function biotic_menu_tree__primary(&$variables) {
  return '<ul class="menu nav navbar-nav" style="padding:20px;">' . $variables['tree'] . '</ul>';
}

/**
 * Bootstrap theme wrapper function for the secondary menu links.
 */
function biotic_menu_tree__secondary(&$variables) {
  return '<ul class="menu nav navbar-nav secondary">' . $variables['tree'] . '</ul>';
}
