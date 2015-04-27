<?php
  /** TEASER */
  // We hide the comments and links now so that we can render them later.
  hide($content['comments']);
  hide($content['links']);
  hide($content['field_portfolio_category']);
  hide($content['field_portfolio_client']);
  hide($content['field_portfolio_date']);
  hide($content['field_portfolio_description']);
  hide($content['field_portfolio_images']);
  hide($content['field_portfolio_layout']);
  hide($content['field_portfolio_preview_image']);
  hide($content['field_portfolio_url']);
  hide($content['field_portfolio_video']);
  hide($content['field_portfolio_skills']);
  hide($content['field_tags']);
  hide($content['field_effect']);

  hide($content['group_details']);

  // Settings
  $content['field_portfolio_preview_image'][0]['#item']['attributes']['class'][] = 'img-responsive';
  $content['field_portfolio_preview_image'][0]['#image_style'] = 'portfolio_portrait_full';

  /* Flexslider Add Controll */
  if( isset( $content['field_portfolio_images']['#settings'] ) ) {
    if ( count($content['field_portfolio_images']['#items']) > 1 ) {
      $content['field_portfolio_images']['#settings']['attributes']['class'][] = "has-control";
    }
  }

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


  $has_images = (render($content['field_portfolio_images']) ) ? true : false;
  $has_video = (render($content['field_portfolio_video']) ) ? true : false;

  // $variables['title_attributes'] = $variables['title_attributes_array'] ? drupal_attributes($variables['title_attributes_array']) : '';

  // dpm( $content['field_portfolio_preview_image'] );

?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <div class="row">

    <div class="col-md-8">

      <?php if ($has_images): ?>
        <?php print render($content['field_portfolio_images']); ?>
      <?php endif ?>

      <?php if ($has_video): ?>
        <?php print render($content['field_portfolio_video']); ?>
      <?php endif ?>

      <?php if (!$has_video && !$has_images): ?>
        <?php print render($content['field_portfolio_preview_image']); ?>
      <?php endif ?>

    </div>

    <div class="margin-sm-top-30px margin-md-top-0px col-md-4">
      <?php print render($content); ?>
      <br>
      <?php print render($content['group_details']); ?>

    </div>

  </div>

</div>

<nav>
  <ul class="pager" >
    <li class="previous"><?php print zenon_node($node, 'prev'); ?></li>
    <li class="next"><?php print zenon_node($node, 'next'); ?></li>
  </ul>
</nav>
