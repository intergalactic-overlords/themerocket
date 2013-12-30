<?php

/**
 * @file
 * Linked 1 column template.
 */
?>

<<?php print $ds_content_wrapper; print $layout_attributes; ?> class="1col-linked <?php print $classes;?> clearfix">

  <?php if (isset($title_suffix['contextual_links'])): ?>
  <?php print render($title_suffix['contextual_links']); ?>
  <?php endif; ?>

  <?php if (isset($variables['node_url'])): ?>
    <a class="wrap-link" href="<?php print $variables['node_url']; ?>">
  <?php endif; ?>

    <?php print $ds_content; ?>

  <?php if (isset($variables['node_url'])): ?>
    </a>
  <?php endif; ?>
</<?php print $ds_content_wrapper ?>>

<?php if (!empty($drupal_render_children)): ?>
  <?php print $drupal_render_children ?>
<?php endif; ?>