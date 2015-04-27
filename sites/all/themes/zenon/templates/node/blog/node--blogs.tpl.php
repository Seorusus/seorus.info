<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */

$node = $variables['node'];
$author = user_load($node->uid);
$about = field_view_field('user', $author, 'field_user_about');


hide($content['field_blog_audio']);
hide($content['field_blog_format']);
hide($content['field_blog_gallery']);
hide($content['field_blog_link']);
hide($content['field_blog_preview_image']);
hide($content['field_blog_quote']);
hide($content['field_blog_video']);

// Settings
$content['field_blog_preview_image'][0]['#item']['attributes']['class'][] = 'img-responsive';

$format = $content['field_blog_format']['#items'][0]['value'];

// dpm( $format );


?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>


  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>


  <?php if ($format == 'Standard'): ?>
    <!-- STANDART POST FORMAT -->
    <?php print render($content['field_blog_preview_image']); ?>
  <?php endif ?>

  <?php if ($format == 'Image'): ?>
    <!-- IMAGE POST FORMAT -->
    <?php print render($content['field_blog_preview_image']); ?>
  <?php endif ?>

  <?php if ($format == 'Link'): ?>
    <!-- LINK POST FORMAT -->
    <div class="pos-rel">
      <?php print render($content['field_blog_preview_image']); ?>
      <div class="overlayer">
        <p class="link-container">
        <?php print render($content['field_blog_link']); ?>
        </p>
      </div>
    </div>
  <?php endif ?>

  <?php if ($format == 'Video'): ?>
    <!-- VIDEO POST FORMAT -->
    <?php print render($content['field_blog_video']); ?>
  <?php endif ?>

  <?php if ($format == 'Gallery'): ?>
    <!-- GALLERY POST FORMAT -->
    <?php print render($content['field_blog_gallery']); ?>
  <?php endif ?>

  <?php if ($format == 'Audio'): ?>
    <!-- AUDIO POST FORMAT -->
    <div class="wrapper">
      <?php print render($content['field_blog_preview_image']); ?>
      <div class="overlayer">
        <?php print render($content['field_blog_audio']); ?>
      </div>
    </div>
  <?php endif ?>

  <?php if ($format == 'Quote'): ?>
    <!-- QUOTE POST FORMAT -->
    <div class="pos-rel margin-bottom-20px">
      <?php print render($content['field_blog_preview_image']); ?>
      <div class="overlayer">
        <blockquote class="quote-container">
          <!-- <i class="fa fa-quote-left"></i> -->
          <p><?php print render($content['field_blog_quote']); ?></p>
          <footer><cite title="<?php print render($content['field_blog_quote_author']); ?>"><?php print render($content['field_blog_quote_author']); ?></cite></footer>
        </blockquote>
      </div>
    </div>
  <?php endif ?>


  <?php if ($display_submitted): ?>
    <div class="submitted margin-top-15px">
      <?php print $post_submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content margin-bottom-30px"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <div class="panel panel-info">

    <div class="panel-body">
      <div class="media">
        <div class="pull-left">
          <?php print $user_picture; ?>
        </div>
        <div class="media-body">
          <h4 class="media-heading">About the Author</h4>
          <?php print render($about); ?>
          <?php // dpm($about); ?>
        </div>


      </div>

    </div>

  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
