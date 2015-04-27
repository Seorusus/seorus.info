<?php

/**
 * Implements hook_theme().
 */
function tb_menu_theme() {
  return array(
    'tb_menu_tree' => array(
      'variables' => array(
        'menu_name' => NULL,
        'mlid' => NULL,
        'depth' => -1,
        'menu' => NULL,
        'respect_expanded' => 0,
      ),
    ),
    'tb_menu_build' => array(
      'variables' => array(
        'menu' => NULL,
        'depth' => -1,
        'trail' => NULL,
        'respect_expanded' => 0,
      ),
    ),
    'tb_menu' => array(
      'variables' => array(
        'id' => NULL,
        'menu_name' => NULL,
        'mlid' => NULL, 'direction' =>
        'right', 'depth' => -1,
        'respect_expanded' => 0,
        'menu' => NULL,
      ),
    ),
    'tb_menu_link' => array(
        'variables' => array()
    ),
    'tb_menu_main_menu' => array(
      'variables' => array('direction' => 'down', 'depth' => -1),
    ),
    'tb_menu_secondary_menu' => array(
      'variables' => array('direction' => 'down', 'depth' => -1),
    ),
  );
}

/**
 * Builds the final Nice menu.
 *
 * @return mixed
 *   An HTML string of properly nested Nice menu lists.
 */
function theme_tb_menu_tree($variables) {
  // load nice_menus js and css.
  // _load_nice_menus_library();

  /*
   * The top-level menu name that contains the menu to use (e.g. navigation
   * or main-menu) for Drupal menus. For custom $menus this is just the
   * name for menu display.
   */
  $menu_name = $variables['menu_name'];

  /*
   * The menu ID from which to start building the items, i.e. the parent
   * of the displayed menu.
   */
  $mlid = $variables['mlid'];

  /*
   * The number of children levels to display. Use -1 to display all children
   * and use 0 to display no children.
   */
  $depth = $variables['depth'];

  /*
   * Optional. A custom menu array to use for theming -- it should have.
   * the same structure as that returned by menu_tree_all_data().
   */
  $menu = $variables['menu'];
  $respect_expanded = $variables['respect_expanded'];
  // Load the full menu array.
  $menu = isset($menu) ? $menu : menu_tree_all_data($menu_name);
  if (isset($menu)) {
    $page_menu = menu_tree_page_data($menu_name);
    $trail = tb_menu_build_page_trail($page_menu);
    unset($page_menu);
  }

  // Allow i18n module to translate strings where available.
  if (module_exists('i18n_menu')) {
    $menu = i18n_menu_localize_tree($menu);
  }
  // Assume depth == 0 by default, overriden if mlid is specified.
  $parent_depth = 0;

  // For custom $menus and menus built all the way from the top-level we.
  // don't need to "create" the specific sub-menu and we need to get the title.
  // from the $menu_name since there is no "parent item" array.
  // Create the specific menu if we have a mlid.
  if (!empty($mlid)) {
    // Load the parent menu item.
    $item = menu_link_load($mlid);
    $title = check_plain($item['title']);
    // The depth for our parent item, if it exists.
    $parent_depth = ($item['depth']) ? $item['depth'] : 0;

    // Narrow down the full menu to the specific sub-tree we need.
    for ($p = 1; $p < 10; $p++) {
      if ($sub_mlid = $item["p$p"]) {
        $subitem = menu_link_load($sub_mlid);
        // Menu sets these ghetto-ass keys in _menu_tree_check_access().
        $menu = $menu[(50000 + $subitem['weight']) . ' ' . $subitem['title'] . ' ' . $subitem['mlid']]['below'];
      }
    }
  }
  // Otherwise just set a title and move on.
  else {
    // Get the title from the DB since we don't have it in the $menu.
    $result = db_query("SELECT title FROM {menu_custom} WHERE menu_name = :menu_name", array(':menu_name' => $menu_name))->fetchField();
    $title = check_plain($result);
  }

  $output['content'] = '';
  $output['subject'] = $title;

  if ($menu) {
    // Set the total menu depth counting from this parent if we need it.
    $depth = ($depth > 0) ? ($parent_depth + $depth) : $depth;
    $output['content'] .= theme('tb_menu_build', array(
      'menu' => $menu,
      'depth' => $depth,
      'trail' => $trail,
      'respect_expanded' => $respect_expanded,
    ));
  }

  return $output;
}

/**
 * Helper function that builds the nested lists of a Nice menu.
 *
 * @param array $variables
 *   Menu arguments.
 */
function theme_tb_menu_build($variables) {
  // Menu array from which to build the nested lists.
  $menu = $variables['menu'];

  // The number of children levels to display. Use -1 to display all children
  // and use 0 to display no children.
  $depth = $variables['depth'];

  // An array of parent menu items.
  $trail = $variables['trail'];
  // "Show as expanded" option.
  $respect_expanded = $variables['respect_expanded'];

  $output = '';
  // Prepare to count the links so we can mark first, last, odd and even.
  $index = 0;
  $count = 0;
  foreach ($menu as $menu_count) {
    if ($menu_count['link']['hidden'] == 0) {
      $count++;
    }
  }
  // Get to building the menu.
  foreach ($menu as $menu_item) {
    $mlid = $menu_item['link']['mlid'];
    // Check to see if it is a visible menu item.
    if (!isset($menu_item['link']['hidden']) || $menu_item['link']['hidden'] == 0) {
      // Check our count and build first, last, odd/even classes.
      $index++;
      $first_class = ($index == 1) ? 'first' : '';
      $oddeven_class = ($index % 2 == 0) ? 'even' : 'odd';
      $last_class = ($index == $count) ? 'last' : '';
      // Build class name based on menu path
      // e.g. to give each menu item individual style.
      $class = str_replace(array('http', 'https', '://', 'www'), '', $menu_item['link']['href']);
      // Strip funny symbols.
      $class = drupal_html_class('menu-path-' . $class);
      if ($trail && in_array($mlid, $trail)) {
        $class .= ' active-trail';
      }
      // If it has children build a nice little tree under it.
      // Build a nice little tree under it if it has children, and has been set
      // to expand (when that option is being respected).
      if ((!empty($menu_item['link']['has_children'])) &&
          (!empty($menu_item['below'])) && $depth != 0 &&
          ($respect_expanded == 0 || $menu_item['link']['expanded'])) {
        // Keep passing children into the function 'til we get them all.
        if ($menu_item['link']['depth'] <= $depth || $depth == -1) {
          $children = array(
            '#theme' => 'tb_menu_build',
            '#prefix' => '<ul>',
            '#suffix' => '</ul>',
            '#menu' => $menu_item['below'],
            '#depth' => $depth,
            '#trail' => $trail,
            '#respect_expanded' => $respect_expanded,
          );
        }
        else {
          $children = '';
        }
        // Set the class to parent only of children are displayed.
        $parent_class = ($children && ($menu_item['link']['depth'] <= $depth || $depth == -1)) ? 'menuparent ' : '';
        $element = array(
          '#below' => $children,
          '#title' => $menu_item['link']['title'],
          '#href' => $menu_item['link']['href'],
          '#localized_options' => $menu_item['link']['localized_options'],
          '#attributes' => array(
            'class' => array(
              'menu-' . $mlid,
              $parent_class,
              $class,
              $first_class,
              $oddeven_class,
              $last_class,
            ),
          ),
          '#original_link' => $menu_item['link'],
        );
        $variables['element'] = $element;

        // Check for context reactions menu.
        if (module_exists('context')) {
          context_preprocess_menu_link($variables);
          if (isset($variables['element']['#localized_options']['attributes']['class']) &&
            in_array('active', $variables['element']['#localized_options']['attributes']['class']) &&
            !in_array('active-trail', $variables['element']['#attributes']['class'])) {
            $variables['element']['#attributes']['class'][] = ' active-trail';
          }
        }

        $output .= theme('tb_menu_link', $variables);
      }
      else {
        $element = array(
          '#below' => '',
          '#title' => $menu_item['link']['title'],
          '#href' => $menu_item['link']['href'],
          '#localized_options' => $menu_item['link']['localized_options'],
          '#attributes' => array(
            'class' => array(
              'menu-' . $mlid,
              $class,
              $first_class,
              $oddeven_class,
              $last_class,
            ),
          ),
          '#original_link' => $menu_item['link'],
        );
        $variables['element'] = $element;

        // Check for context reactions menu.
        if (module_exists('context')) {
          context_preprocess_menu_link($variables);
          if (isset($variables['element']['#localized_options']['attributes']['class']) &&
            in_array('active', $variables['element']['#localized_options']['attributes']['class']) &&
            !in_array('active-trail', $variables['element']['#attributes']['class'])) {
            $variables['element']['#attributes']['class'][] = ' active-trail';
          }
        }

        $output .= theme('tb_menu_link', $variables);
      }
    }
  }
  return $output;
}

/**
 * Theme function to allow any menu tree to be themed as a Nice menu.
 *
 * @param array $variables
 *   is an array, menu arguments.
 *
 * @return mixed
 *   An HTML string of Nice menu links.
 */
function theme_tb_menu($variables) {
  $output = array(
    'content' => '',
    'subject' => '',
  );

  // The Nice menu ID.
  $id = $variables['id'];
  // The top parent menu name from which to build the full menu.
  $menu_name = $variables['menu_name'];
  // The menu ID from which to build the displayed menu.
  $mlid = $variables['mlid'];
  // Optional. The direction the menu expands. Default is 'right'.
  $direction = $variables['direction'];
  // The number of children levels to display. Use -1 to display all children
  // and use 0 to display no children.
  $depth = $variables['depth'];
  /*
   * Optional. A custom menu array to use for theming --
   * it should have the same structure as that returned
   * by menu_tree_all_data(). Default is the standard menu tree.
   */
  $menu = $variables['menu'];
  // "Show as expanded" option.
  $respect_expanded = $variables['respect_expanded'];
  if ($menu_tree = theme('tb_menu_tree', array(
    'menu_name' => $menu_name,
    'mlid' => $mlid,
    'depth' => $depth,
    'menu' => $menu,
    'respect_expanded' => $respect_expanded))) {
    if ($menu_tree['content']) {
      $output['content'] = '<nav id="main-menu"><ul class="tb-menu '   . $menu_name . '" id="tb-menu-' . $id . '">' . $menu_tree['content'] . '</ul></nav>' . "\n";
      $output['subject'] = $menu_tree['subject'];
    }
  }
  return $output;
}

function theme_tb_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  $caret = '';

  $option = array(
    'html' => true
  );

  $options = array_merge($option, $element['#localized_options'] );

  // dpm($options);

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
    // $caret = '<i class="fa fa-angle-down"></i>';
  }

  $output = l("<span>".$element['#title']."</span>".$caret, $element['#href'], $options );
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}