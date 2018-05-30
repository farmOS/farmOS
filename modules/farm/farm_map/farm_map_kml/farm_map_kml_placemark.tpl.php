    <Placemark id="<?php print $pid; ?>">
      <name><?php print $name; ?></name>
      <?php if (!empty($description)): ?><description><?php print $description; ?></description><?php endif; ?>
<?php if (!empty($kml)) print $kml; ?>
    </Placemark>
