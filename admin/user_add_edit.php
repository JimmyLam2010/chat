<?php
include_once("../includes/init.php");
include_once("common.php");

date_default_timezone_set('PRC');

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $cate = $db->select()->from("cate")->where("id={$id}")->find();
} else{
    $catelist = $db->select()->from("cate")->all();
}



if($_POST) {
    $name = isset($_POST['name']) && !empty($_POST['name']) ? trim($_POST['name']) : '';

    $coloum = array(
        'name' => $name,
    );

    if($id) {
        $result = $db->update("cate")->set($coloum)->where("id={$id}")->getResult();

        if($result) {
            show("编辑成功","cate.php");die;
        }else{
            show("编辑失败","cate.php");die;
        } 
    }else{
        $result = $db->insert("cate", $coloum)->getResult();
        
        if($result) {
            show("添加成功","cate.php");die;
        }else{
            show("添加失败","cate.php");die;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once('meta.php');?>
  </head>

  <body> 

    <!-- 引入头部 -->
    <?php include_once('header.php');?>
    
    <?php include_once('menu.php');?>

    <div class="content">
        <div class="header">
            <h1 class="page-name"><?php echo $id ? '编辑' : '添加'?>分类</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">首页</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $id ? '编辑' : '添加'?></li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onClick="location='cate.php'"><i class="icon-list"></i> 返回分类列表</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                        <form method="post">
                            <label>分类名称</label>
                            <input type="text" name="name" class="input-xxlarge" placeholder="请输入分类" value="<?php echo $id ? $cate['name'] : ''?>"/>
                            <label></label>
                            <input class="btn btn-primary" type="submit" value="提交" />
                        </form>
                      </div>
                  </div>
                </div>
                
                <footer>
                    <hr>
                    <p>&copy; 2017 <a href="#" target="_blank">copyright</a></p>
                </footer>
                    
            </div>
        </div>
    </div>
    
    <?php include_once('footer.php');?>

  </body>
</html>


