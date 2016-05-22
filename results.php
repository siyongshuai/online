<?php
include("include/session.php");

?>
<html>
<head>
<title>Talent Hunt-results</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<script src="js/jquery.js"></script>
<style>
#data tr{background-color:#BC7E7E;color:#301A1A;}
#data td{font-size:19px;}
#data{width:500px;padding-left:3px;}
a img {border:none;}
</style>
</head>
<body>
<?php
global $database;
global $session;
if(isset($_GET['admin']))
{
 if($session->isAdmin()){
   include("selectexam.html.php");
 }
}
if(isset($_POST['topic']))
{
  echo "<table align='center' border='1'>";
  $rows=$database->getreport($_POST['topic']);
  $count=1;
  foreach ($rows as $row) {
     echo "<tr><td>".$count++."</td><td>".$row['username']."</td><td>".$row['result']."</td></tr>";
  }
  echo "</table>";
}
if(isset($_GET['id']))
{
 $examid=$_GET['id'];
  if(!$database->valid($examid))
  {
  echo "<font color=red><b>not a valid exam</b></font>";
  exit();
  }
 $result=$database->getresults($examid);
 echo "<table>";
 echo "<tr ><td colspan=2 align=\"center\"><b>成绩</b></td></tr>";
 echo "<tr><td colspan=2 align=\"center\"><image src=\"imageloader.php?num=".$result['got']."&den=".$result['for']."\"></td></tr>";
 echo "<tr><td><b>得分</b></td><td>".$result['got']."</td></tr>";
 echo "<tr><td><b>满分</b></td><td>".$result['for']."</td></tr>";
 echo "<tr><td><b>章节 :</b></td><td>".$result['topic']."</td></tr>";
 echo "<tr><td><b>日期</b></td><td>".$result['date']."</td></tr>";
 echo "<tr><td><b>时间</b></td><td>".$result['time']."</td></tr>";
 echo "</table>";
 echo "<a href=\"?user=".$result['username'].'" title="Go back"><img src="images/back.png"></a>';
}
else if(isset($_GET['user']))
{
 if($_GET['result']=='latest')
 {
  $result=$database->getresults($database->latestExamID($_GET['user']));
  echo "<table>";
  echo "<tr ><td colspan=2 align=\"center\"><b>Result</b></td></tr>";
  echo "<tr><td colspan=2 align=\"center\"><image src=\"imageloader.php?num=".$result['got']."&den=".$result['for']."\"></td></tr>";
  echo "<tr><td><b>得分</b></td><td>".$result['got']."</td></tr>"; 
  echo "<tr><td><b>满分</b></td><td>".$result['for']."</td></tr>";
  echo "<tr><td><b>章节 :</b></td><td>".$result['topic']."</td></tr>";
  echo "<tr><td><b>日期</b></td><td>".$result['date']."</td></tr>";
  echo "<tr><td><b>时间</b></td><td>".$result['time']."</td></tr>";
  echo "</table>";
  echo "<a href=\"?user=".$result['username'].'" title="Go back"><img src="images/back.png"></a>';
 }
 else{
  $username=$_GET['user'];
   if($database->noresults(($username)))
   {
    echo "<b><font color=red size=6>".$username."</font></b> hasn't attended exams until now";
    exit();
   }
  $exams=$database->getExams($username);
  echo "<h1>考试列表(从新到旧)</h1>";
  echo "<table cellpadding=5 cellspacing=0 id=data>"; 
  echo "<tr id=\"tag\"><td ><b>章节</b></td><td><b>日期</b></td><td><b>成绩</b></td></tr>";
  foreach($exams as $exam)
  echo "<tr><td>".$exam['topic']."</td><td>".$exam['date']."</td><td><a href=\"?id=".$exam['id']."\">查看成绩</a></td></tr>";
  echo "</table>";
  echo "<a href=\"./\" title=\"返回\"><img src=\"images/back.png\"></a>";
 }
}
?>

