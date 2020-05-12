<div class="<?php print $classes; ?>">
  <div class="field-label"><?php print $label; ?></div>
  <ul class="field-items list-group">
    <?php foreach ($quantities as $quantity): ?>
      <?php extract($quantity); ?>
      <li class="field-item list-group-item col-xs-6 col-sm-4 col-md-3 col-lg-2">
        <span class="badge"><?php print $value; ?> <?php print $units; ?></span>
        <?php print $label; ?> <?php print $measure; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
