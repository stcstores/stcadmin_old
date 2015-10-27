<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
checkLogin();
require_once($CONFIG['header']);


$database = new DatabaseConnection();
if (isset($_GET['id'])) {
    $query= "SELECT * FROM stcadmin_news WHERE id={$_GET['id']};";
} else {
    $query = "SELECT * FROM stcadmin_news WHERE display=TRUE ORDER BY timestamp DESC;";
}
$results = $database -> selectQuery($query);
?>


<?php
foreach ($results as $record) {
  ?>
  <div class=pagebox>
        <div class=news_article>
          <h4><?php echo $record['header']; ?></h4>
          <p><?php echo date('Y-m-d', strtotime($record['timestamp']));?></p>
          <p><?php echo nl2br($record['message']); ?></p>
      </div>
  </div>
  <br />
  <?php
}

require_once($CONFIG['footer']);