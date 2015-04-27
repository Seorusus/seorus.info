
<div <?php print $wrapper_attributes; ?> >

<?php if($demostration || $debug): ?>
<span class="pos-abs label label-danger section-demostration"><?php print $title; ?></span>
<?php endif; ?>

    <div <?php print $attributes; ?> >
        <div id="<?php print $id ?>">
            <div class="<?php print ($fluid == "on") ? "row-fluid": "row"; ?>">
                <?php print $content; ?>
            </div>
        </div>
    </div>
</div>
