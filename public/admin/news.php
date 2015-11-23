<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);


$database = new STCAdmin\Database();
if (isset($_GET['id'])) {
    $news = $database->getNewsByID($_GET['id']);
} else {
    $news = $database->getAllNews();
}

foreach ($news as $article) {
    echo "\t<div class=pagebox>\n";
    echo "\t\t<div class=news_article>\n";
    echo "\t\t\t<h4>" . $article['header'] . "</h4>\n";
    echo "\t\t\t<p>" . date('Y-m-d', strtotime($article['timestamp'])) . "</p>\n";
    echo "\t\t\t<p>" . nl2br($article['message']) . "</p>\n";
    echo "\t\t</div>\n";
    echo "\t</div>\n";
    echo "\t<br />\n";
}

require_once($CONFIG['footer']);
