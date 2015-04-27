<?php

/**
 * @file
 * Override of the default maintenance page.
 *
 * This is an override of the default maintenance page. Used for Garland and
 * Minnelli, this file should not be moved or modified since the installation
 * and update pages depend on this file.
 *
 * This mirrors closely page.tpl.php for Garland in order to share the same
 * styles.
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <title><?php print $head_title ?></title>
    <?php print $head ?>
    <?php print $styles ?>
    <?php print $scripts ?>

    <style>
      html, body { height:100%; }
    </style>

  </head>
  <body class="<?php print $classes ?>"   <?php print $attributes; ?>>

<!-- Layout -->
  <div id="header-region" class="clearfix"><?php print $header; ?></div>

    <div id="wrapper">
    <div id="container" class="container clearfix">

        <?php
          // Prepare header
          // $site_fields = array();
          //
          if ($site_name) {
            $site_fields[] = $site_name;
          }
          if ($site_slogan) {
            $site_fields[] = $site_slogan;
          }
          $site_title = implode(' ', $site_fields);

          if ($site_fields) {
            $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
          }
          $site_html = implode(' ', $site_fields);
          //
          // if ($logo || $site_title) {
          //   print '<h1 id="branding"><a href="' . $base_path . '" title="' . $site_title . '">';
          //   if ($logo) {
          //     print '<img src="' . $logo . '" alt="' . $site_title . '" id="logo" />';
          //   }
          //   print $site_html . '</a></h1>';
          // }

        ?>



      <div class="row margin-md-top-100px">
        <div class="col-md-8 col-md-offset-2">

          <?php if($logo): ?>
            <header>
              <h1 class="text-center">
                <?php // print '<img src="' . $logo_w_svg . '" alt="' . $site_title . '" id="logo" width="250px" />';  ?>

                <?php

                // if ($logo || $site_title) {
                //   print '<h1 id="branding"><a href="' . $base_path . '" title="' . $site_title . '">';
                //   if ($logo) {
                //     print '<img src="' . $logo . '" alt="' . $site_title . '" id="logo" />';
                //   }
                //   print $site_html . '</a></h1>';
                // }

                print '<span id="logo"><img src="' . $logo . '" alt="' . $site_title . '" id="logo-image" /></span>';

                ?>


                <span class="element-invisible"><?php print $site_title; ?></span>
              </h1>
            </header>
          <?php endif; ?>

          <div class="content text-center">
          <?php if ($title): ?><h2 class="text-uppercase h3" style="font-weight: bold;"><?php print $title ?></h2><?php endif; ?>
            <?php print $messages; ?>
            <?php print $help; ?>
            <div class="clearfix h4 maintenance-content">
              <?php print $content ?>
            </div>
          </div>

        </div>
      </div>



    </div> <!-- /container -->
  </div>
<!-- /layout -->

  </body>
</html>
