<?php

/**
 *   TEASER
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
    // $content['field_portfolio_preview_image'][0]['#item']['attributes']['class'][] = 'img-responsive';
  // LINK
    $link = url("node/{$node->nid}");
  //Effect
    $effect = "effect-".$content['field_effect']['#items'][0]['value'];

  //Categories
   $cats = array();
   foreach (element_children( $content['field_portfolio_category'] ) as $key ) {
      $cats[] = $content['field_portfolio_category'][$key]['#markup'];
   }
   $category = implode(" ",$cats);


   // Add Custom Classes
   //
   $classes .= " ".$category;

?>
<div id="node-<?php print $node->nid; ?>" class="portfolio-column-tree col-md-4 portfolio-effects <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <figure class="<?php print $effect ?>" >
    <?php print render($content['field_portfolio_preview_image']); ?>
      <figcaption>
          <h2><?php print $title; ?></h2>
          <p><?php print render($content['field_portfolio_description']) ?></p>
          <a href="<?php print $link ?>">Take a look</a>
          <!-- <button class="btn btn-default btn-xs" name="button">Button</button> -->
      </figcaption>
  </figure>

</div>
