<?php
/**
 * @file
 * Returns HTML for a region.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728112
 */
?>


<?php 
    
    if ( isset($clearfix) ) {
        
        if ($clearfix == 1 || $clearfix == 3) {
            print t('<div class="clearfix before-@key"></div>',array('@key' => $key));
        }
    }

?>


<?php if ($content): ?>
  <div class="<?php print $classes; ?>" <?php print $attributes; ?> >
    <div class="row-fluid">

<?php print $breadcrumb; ?>
<?php print $messages; ?>

<?php if(theme_get_setting('page_title')): ?>
<?php print render($title_prefix); ?>
<?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
<?php print render($title_suffix); ?>
<?php endif; ?>
<?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
<?php print render($page['help']); ?>
<?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>


        <?php print $content; ?>
    </div>
  </div>
<?php endif; ?>


<?php 
    
    if ( isset($clearfix) ) {
        
        if ($clearfix == 2 || $clearfix == 3) {
            print t('<div class="clearfix after-@key"></div>',array('@key' => $key));
        }
    }

?>