<?php
include_once("../includes/init.php");
include_once("common.php");

$act = isset($_GET['act']) ? trim($_GET['act']) : '';

if($act == "logout")
{
  session_unset();
  show("退出成功","login.php");
  exit;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once("meta.php");?>
  </head>

  <body>
    
    <!-- 引入头部 -->
    <?php include_once('header.php');?>
    
    <?php include_once('menu.php');?>

    <div class="content">
        <div class="header">
            <h1 class="page-title">后台首页</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li class="active">Index</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                <footer>
                    <hr>
                    <p>&copy; 2017 <a href="#" target="_blank">copyright</a></p>
                </footer>
            </div>
        </div>
    </div>
  </body>
</html>
<?php include_once('footer.php');?>

