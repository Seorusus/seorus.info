<div id="views-bootstrap-accordion-<?php print $id ?>" class="<?php print $classes ?>">

<?php if (!empty($title)): ?>
  <header class="margin-30px">
    <h2 class="h4 line"><span><?php print $title; ?></span></h2>
  </header>
<?php endif; ?>

  <?php foreach ($rows as $key => $row): ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle"
             data-toggle="collapse"
             data-parent="#views-bootstrap-accordion-<?php print $id ?>"
             href="#collapse<?php print $key ?>">
            <?php print $titles[$key] ?>
          </a>
        </h4>
      </div>

      <div id="collapse<?php print $key ?>" class="panel-collapse collapse">
        <div class="panel-body">
          <?php print $row ?>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>
