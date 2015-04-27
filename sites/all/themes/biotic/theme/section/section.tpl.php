
<div <?php print $wrapper_attributes; ?> >
    <section <?php print $attributes; ?> >
        <div id="<?php print $id ?>">
            <div class="<?php print ($fluid == "on") ? "row-fluid": "row"; ?>">
                <?php print $content; ?>
            </div>
        </div>
    </section>
</div>