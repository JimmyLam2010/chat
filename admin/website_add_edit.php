<?php
include_once("../includes/init.php");
include_once("common.php");

date_default_timezone_set('PRC');

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $website = $db->select()->from("website")->where("id={$id}")->find();
} else{
    $websitelist = $db->select()->from("website")->all();
}

if($_POST) {
    $website_name = isset($_POST['website_name']) && !empty($_POST['website_name']) ? trim($_POST['website_name']) : '';
    $url = isset($_POST['url']) && !empty($_POST['url']) ? trim($_POST['url']) : '';
    $ul_name = isset($_POST['ul_name']) && !empty($_POST['ul_name']) ? trim($_POST['ul_name']) : '';
    $content_name = isset($_POST['content_name']) && !empty($_POST['content_name']) ? trim($_POST['content_name']) : '';

    $coloum = array(
        'website_name' => $website_name,
        'url' => $url,
        'ul_name' => $ul_name,
        'content_name' => $content_name
    );

    if($id) {
        
        $result = $db->update("website")->set($coloum)->where("id={$id}")->getResult();
        var_dump($result);

        if($result) {
            show("编辑成功","website.php");die;
        }else{
            show("编辑失败","website.php");die;
        } 
    }else{
        $result = $db->insert("website", $coloum)->getResult();
        
        if($result) {
            show("添加成功","website.php");die;
        }else{
            show("添加失败","website.php");die;
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
            <h1 class="page-website_name"><?php echo $id ? '编辑' : '添加'?>采集节点</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">首页</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $id ? '编辑' : '添加'?></li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onClick="location='website.php'"><i class="icon-list"></i> 返回采集节点列表</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                        <form method="post">
                            <label>网站名称</label>
                            <input type="text" name="website_name" class="input-xxlarge" placeholder="请输入网站名称" value="<?php echo $id ? $website['website_name'] : ''?>"/>
                            <label>地址</label>
                            <input type="text" name="url" class="input-xxlarge" placeholder="请输入地址" value="<?php echo $id ? $website['url'] : ''?>"/>
                            <label>目录容器</label>
                            <input type="text" name="ul_name" class="input-xxlarge" placeholder="请输入目录容器" value="<?php echo $id ? $website['ul_name'] : ''?>"/>
                            <label>内容容器</label>
                            <input type="text" name="content_name" class="input-xxlarge" placeholder="请输入内容容器" value="<?php echo $id ? $website['content_name'] : ''?>"/>
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


