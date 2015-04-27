<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $options['type'] will either be ul or ol.
 * @ingroup views_templates
 */
?>

<!-- Portfolio Filter View -->

<nav id="portfolio-filter">
  <?php if (!empty($title)) : ?>
    <h3><?php print $title; ?></h3>
  <?php endif; ?>
  <?php print $list_type_prefix; ?>
    <li>
      <button class="btn btn-filter active" data-filter="*"><?php print t("All"); ?></button>
    </li>
    <?php foreach ($rows as $id => $row): ?>
      <li class="<?php print $classes_array[$id]; ?>" >
        <?php print $row; ?>
      </li>
    <?php endforeach; ?>
  <?php print $list_type_suffix; ?>
</nav>

<!-- @end Portfolio filter -->
