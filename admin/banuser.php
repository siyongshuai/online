<?php

include("../include/session.php");

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function activeUsers(){
   global $database;
   $q = "SELECT * "
       ."FROM active_users ";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "信息错误";
      return;
   }
   if($num_rows == 0){
      echo "暂无匹配内容";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr><td><b>用户名</b></td><td><b>最后活跃时间</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");     
      $time   = mysql_result($result,$i,"timestamp");
      echo "<tr><td><a href=\"../userinfo.php?user=$uname\">$uname</a></td><td>".date("d-m-y h:m:s",$time)."</td></tr>\n";
   }
   echo "</table><br>\n";
}
function activeGuests(){
   global $database;
   $q = "SELECT * "
       ."FROM active_guests ";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "暂无匹配内容";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr><td><b>IP Address</b></td><td><b>Last Active</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"ip");     
      $time   = mysql_result($result,$i,"timestamp");
      echo "<tr><td><a href=../userinfo.php?user=$uname>$uname</a></td><td>".date("d-m-y h:m:s",$time)."</td></tr>\n";
   }
   echo "</table><br>\n";
}
/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 */
function displayBannedUsers(){
   global $database;
   $q = "SELECT username,timestamp "
       ."FROM ".TBL_BANNED_USERS." ORDER BY username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "暂无匹配内容";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr><td><b>Username</b></td><td><b>Time Banned</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname = mysql_result($result,$i,"username");
      $time  = mysql_result($result,$i,"timestamp");

      echo "<tr><td>$uname</td><td>$time</td></tr>\n";
   }
   echo "</table><br>\n";
}
   
/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAdmin()){
   header("Location: ../");
}
else{
/**
 * Administrator is viewing page, so display all
 * forms.
 */
?>
<html>
<title>Talent Hunt:ban users & activeusers</title>
<link rel="icon" href="../favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />

<style>
 body{background:url(../images/banner.jpg) top center no-repeat ;margin-top:250px;}
 </style>
<body>
<h1>管理中心:禁用账户和激活用户</h1>

 [<a href="../">返回主页</a>]<br><br>
<?php
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}
?>
<table align="left" border="0" cellspacing="5" cellpadding="5">
<tr><td>
<?php
/**
 * Display Users Table
 */
?>
<h3>活动账户:</h3>
<?php
activeUsers();
?>
</td></tr>
<tr>
<tr><td>
<?php
/**
 * Display active guests Table
 */
?>
<h3>活动来宾账户:</h3>
<?php
activeGuests();
?>
</td></tr>
<tr>
<td>
<?php
/**
 * Delete Inactive Users
 */
?>
<h3>删除非活跃账户</h3>
<!-- This will delete all users (not administrators), who have not logged in to the site<br> -->

<!-- within a certain time period. You specify the days spent inactive.<br><br> -->
这会根据你指定的时间段删除那些没有登录的账户(非管理员)
<table>
<form action="adminprocess.php" method="POST">
<tr><td>
天数:<br>
<select name="inactdays">
<option value="3">3
<option value="7">7
<option value="14">14
<option value="30">30
<option value="100">100
<option value="365">365
</select>
</td>
<td>
<br>
<input type="hidden" name="subdelinact" value="1">
<input type="submit" value="删除所有非活跃账户">
</td>
</form>
</table>
</td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?php
/**
 * Ban User
 */
?>
<h3>禁用账户</h3>
<?php echo $form->error("banuser"); ?>
<form action="adminprocess.php" method="POST">
用户名:<br>
<input type="text" name="banuser" maxlength="30" value="<?php echo $form->value("banuser"); ?>">
<input type="hidden" name="subbanuser" value="1">
<input type="submit" value="禁用账户">
</form>
</td>
</tr>
<tr>
<td><hr></td>
</tr>
<tr><td>
<?php
/**
 * Display Banned Users Table
 */
?>
<h3>禁用账户列表:</h3>
<?php
displayBannedUsers();
?>
</td></tr>
<tr>
<td><hr></td>
</tr>
<tr>
<td>
<?php
/**
 * Delete Banned User
 */
?>
<h3>删除禁用账户</h3>
<?php echo $form->error("delbanuser"); ?>
<form action="adminprocess.php" method="POST">
用户名:<br>
<input type="text" name="delbanuser" maxlength="30" value="<?php echo $form->value("delbanuser"); ?>">
<input type="hidden" name="subdelbanned" value="1">
<input type="submit" value="删除禁用账户">
</form>
</td>
</tr>
</table>

</body>
</html>
<?php
}
?>

