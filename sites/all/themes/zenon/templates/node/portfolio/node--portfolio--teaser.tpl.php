<?php
  /** TEASER */
  // We hide the comments and links now so that we can render them later.
  hide($content['comments']);
  hide($content['links']);
  hide($content['field_portfolio_description']);
  hide($content['field_portfolio_layout']);
  hide($content['field_portfolio_images']);
  hide($content['field_portfolio_preview_demo']);
  hide($content['field_portfolio_category']);
  hide($content['field_portfolio_video']);
  hide($content['field_tags']);
  hide($content['field_effect']);

  // Settings
  $content['field_portfolio_preview_image'][0]['#item']['attributes']['class'][] = 'img-responsive';

  // Title
  $variables['title_attributes_array']['class'][] = "portfolio-title portfolio-title-teaser";

  //Categories
  //#formatter tb_formatter_machine_name
  $cat = array();
  $display = array('type' => 'tb_formatter_machine_name');
  $fields = field_get_items('node', $node, 'field_portfolio_category');
  foreach ($fields as $key => $field) {
    $data = field_view_value('node', $node, 'field_portfolio_category', $field ,$display);
    $cat[] = $data['#markup'];
  }
  $category = implode(" ",$cat);


  // Add Custom Classes
  $classes .= " ".$category;
  $title_attributes = drupal_attributes($variables['title_attributes_array']);




  // $variables['title_attributes'] = $variables['title_attributes_array'] ? drupal_attributes($variables['title_attributes_array']) : '';

  // dpm($variables);
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <div class="col-md-6">
    <?php print render($content['field_portfolio_preview_image']); ?>
  </div>

  <div class="col-md-6">

    <?php print render($title_prefix); ?>
      <h2<?php print $title_attributes; ?>><?php print $title; ?></h2>
    <?php print render($title_suffix); ?>

    <?php print render($content); ?>

    <footer class="xtext-right margin-top-20px">
      <a href="<?php print $node_url; ?>" class="btn btn-flat">Take a look</a>
    </footer>



  </div>

</div>
