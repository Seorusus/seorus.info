<?php
  /** TEASER */
  // We hide the comments and links now so that we can render them later.
  hide($content['comments']);
  hide($content['links']);
  hide($content['field_tags']);

  hide($content['field_blog_audio']);
  hide($content['field_blog_format']);
  hide($content['field_blog_gallery']);
  hide($content['field_blog_link']);
  hide($content['field_blog_preview_image']);
  hide($content['field_blog_quote']);
  hide($content['field_blog_video']);


  // Settings
  $content['field_blog_preview_image'][0]['#item']['attributes']['class'][] = 'img-responsive';

  // Title
  $variables['title_attributes_array']['class'][] = "blog-title blog-title-teaser";

  // Add Custom Classes
  // $classes .= " ".$category;
  $title_attributes = drupal_attributes($variables['title_attributes_array']);




  // $variables['title_attributes'] = $variables['title_attributes_array'] ? drupal_attributes($variables['title_attributes_array']) : '';

  // dpm($variables);
?>
<article id="node-<?php print $node->nid; ?>" class="row <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <div class="col-md-6">
    <?php print render($content['field_blog_video']); ?>
  </div>

  <div class="col-md-6">
    <header>
      <?php print render($title_prefix); ?>
        <h2<?php print $title_attributes; ?>><?php print $title; ?></h2>
      <?php print render($title_suffix); ?>

      <?php if ($display_submitted): ?>
        <div class="submitted">
          <?php print $post_submitted; ?>
        </div>
      <?php endif; ?>


    </header>

    <?php print render($content); ?>

    <footer class="margin-top-20px">
      <?php print render($content['links']); ?>
    </footer>



  </div>

</article>


<?php

// <article>
//   <header>
//     <h1>Internet Explorer 9</h1>
//     <p><time pubdate datetime="2011-03-15"></time></p>
//   </header>
//   <p>Windows Internet Explorer 9 (abbreviated as IE9) was released to
//   the  public on March 14, 2011 at 21:00 PDT.....</p>
// </article>


 ?>
