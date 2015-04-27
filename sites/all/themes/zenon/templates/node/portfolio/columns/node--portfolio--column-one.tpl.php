<?php

/**
 *   COLUM ONE [8/4]
 */
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

// SETTINGS
  // preview image
    $content['field_portfolio_preview_image'][0]['#item']['attributes']['class'][] = 'img-responsive';
  // LINK
    $link = url("node/{$node->nid}");
  //Effect
    $effect = "effect-".$content['field_effect']['#items'][0]['value'];


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

?>
<div id="node-<?php print $node->nid; ?>" class="col-md-12 <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <div class="row-fluid">
    <div class="col-md-8">
      <?php print render($content['field_portfolio_preview_image']); ?>
      <?php // print render($content['field_portfolio_images']); ?>
    </div>
    <div class="col-md-4">
      <h3><?php print $title; ?></h3>
      <p><?php print render( $content['field_portfolio_category'] ) ?></p>
      <p><?php print render($content['field_portfolio_description']) ?></p>
      <a href="<?php print $link ?>" class="btn btn-flat">Take a look</a>

    </div>
  </div>

</div>
