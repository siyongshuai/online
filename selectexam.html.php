<?php
global $database;
 echo '<form action="" align="center" method="post">选择章节<select name="topic">';
 $database->topicslist();
 echo '</select><input type="submit" value="查看"></form>';
?>