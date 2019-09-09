<?php
include_once("../includes/init.php");
include_once("common.php");

date_default_timezone_set('PRC');
error_reporting( E_ALL&~E_NOTICE );

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $config = $db->select()->from("config")->where("id={$id}")->find();
} else{
    $configlist = $db->select()->from("config")->all();
}

if($_POST) {
    $name = isset($_POST['name']) && !empty($_POST['name']) ? trim($_POST['name']) : '';
    $title = isset($_POST['title']) && !empty($_POST['title']) ? trim($_POST['title']) : '';
    $value = isset($_POST['value']) && !empty($_POST['value']) ? trim($_POST['value']) : '';
    $url = isset($_POST['url']) && !empty($_POST['url']) ? trim($_POST['url']) : null;

    $coloum = array(
        'name' => $name,
        'title' => $title,
        'value' => $value,
        'url' => $url,
        'img' => $id ? $config['img'] : null,
    );

    if($id) {
        if($uploads->isFile('img'))
        {
            //判断文件是否上传成功
            if($uploads->upload('img'))
            {
                @is_file(ASSETS_PATH.$config['img']) && @unlink(ASSETS_PATH.$config['img']);
                //获取上传的文件名
                $coloum['img'] = $uploads->savefile();

                $result = $db->update("config")->set($coloum)->where("id={$id}")->getResult();

                if($result) {
                // var_dump($_FILES['img']['size']);
                show("编辑成功","config.php");die;
                }else{
                show("编辑失败","config.php");die;
                } 
            }else{
                //显示错误信息
                show($uploads->getMessage());
                exit;
            }
        }else{
            $coloum['img'] = null;
            $result = $db->update("config")->set($coloum)->where("id={$id}")->getResult();
            if($result) {
                // var_dump($_FILES['img']['size']);
                show("编辑成功","config.php");die;
            }else{
                show("编辑失败","config.php");die;
            }
        }
    }else{
        //判断是否有文件上传
        if($uploads->isFile('img'))
        {
            //判断文件是否上传成功
            if($uploads->upload('img'))
            {
                //获取上传的文件名
                $coloum['img'] = $uploads->savefile();

                $result = $db->insert("config", $coloum)->getResult();

                if($result) {
                // var_dump($_FILES['img']['size']);
                show("编辑成功","config.php");die;
                }else{
                show("编辑失败","config.php");die;
                } 
            }else{
                //显示错误信息
                show($uploads->getMessage());
                exit;
            }
        }else{
            $coloum['img'] = null;
            $result = $db->insert("config", $coloum)->getResult();
            if($result) {
                // var_dump($_FILES['img']['size']);
                show("编辑成功","config.php");die;
            }else{
                show("编辑失败","config.php");die;
            } 
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
            <h1 class="page-name"><?php echo $id ? '编辑' : '添加'?>设置</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">首页</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $id ? '编辑' : '添加'?></li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onClick="location='config.php'"><i class="icon-list"></i> 返回网站设置</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                        <form method="post" enctype="multipart/form-data">
                            <label>名称</label>
                            <input type="text" name="name" class="input-xxlarge" placeholder="请输入名称" value="<?php echo $id ? $config['name'] : ''?>"/>
                            <label>标题</label>
                            <input type="text" name="title" class="input-xxlarge" placeholder="请输入标题" value="<?php echo $id ? $config['title'] : ''?>"/>
                            <label>值</label>
                            <input type="text" name="value" class="input-xxlarge" placeholder="请输入值" value="<?php echo $id ? $config['value'] : ''?>"/>
                            <label>链接</label>
                            <input type="text" name="url" class="input-xxlarge" placeholder="请输入链接" value="<?php echo $id ? $config['url'] : ''?>"/>
                            <label>图片</label>
                            <input type="file" class="input-xxlarge" name="img" />
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


