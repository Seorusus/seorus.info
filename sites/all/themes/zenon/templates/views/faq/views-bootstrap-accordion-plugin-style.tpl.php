<?php
  // dpm($variables);
  // dpm( $id );
 ?>

<?php if ( fmod($id, 2) ): ?>
  <div class="row">
<?php endif ?>

<div class="col-sm-6">
<div id="views-bootstrap-accordion-<?php print $id ?>" class="<?php print $classes ?>">

<?php if (!empty($title)): ?>
  <header class="margin-30px">
    <h2 class="h3 line heavy"><span><?php print $title; ?></span></h2>
  </header>
<?php endif; ?>

  <?php foreach ($rows as $key => $row): ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title">
          <h3 class="h5 margin-0px">
            <a class="accordion-toggle"
               data-toggle="collapse"
               data-parent="#views-bootstrap-accordion-<?php print $id ?>"
               href="#collapse<?php print $key ?>">
              <?php print $titles[$key] ?>
            </a>
          </h3>
        </div>
      </div>

      <div id="collapse<?php print $key ?>" class="panel-collapse collapse">
        <div class="panel-body">
          <?php print $row ?>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>
</div>

<?php if ( !fmod($id, 2)): ?>
  </div> <!-- end row -->
<?php endif ?>
