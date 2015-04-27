<?php
// $Id: submitted.tpl.php $
/**
 * @file
 * Default theme implementation to present the submitted information for a
 * node object.
 *
 * Available variables:
 * - $username: Name of the user who submitted the node.
 *
 * - $datetime: The timestamp on which the node has been created.
 *
 * @see template_preprocess_submitted()
 */
?>
<p class="submitted">
  <?php // print t('<span class="fa fa-user"></span> !author <span class="fa fa-clock-o"></span> @date', array('!author' => $username, '@date' => $datetime) ); ?>
  <?php print t('Submitted by !author on @date', array('!author' => $username, '@date' => $datetime) ); ?>
</p>
