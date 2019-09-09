<?php
include_once("../includes/init.php");
include_once("common.php");

date_default_timezone_set('PRC');

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $admin = $db->select()->from("admin")->where("id={$id}")->find();
} else{
    $adminlist = $db->select()->from("admin")->all();
}



if($_POST) {
    $username = isset($_POST['username']) && !empty($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) && !empty($_POST['password']) ? intval($_POST['password']) : '';
    $email = isset($_POST['email']) && !empty($_POST['email']) ? trim($_POST['email']) : '';

    $coloum = array(
        'username' => $username,
        'password' => md5($password.$salt),
        'salt' => $String->randomStr(),
        'email' => $email,
        'register_time' => time()
    );

    if($id) {
        $result = $db->update("admin")->set($coloum)->where("id={$id}")->getResult();

        if($result) {
            show("编辑成功","admin.php");die;
        }else{
            show("编辑失败","admin.php");die;
        } 
    }else{
        $result = $db->insert("admin", $coloum)->getResult();
        
        if($result) {
            show("添加成功","admin.php");die;
        }else{
            show("添加失败","admin.php");die;
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
            <h1 class="page-username"><?php echo $id ? '编辑' : '添加'?>管理员</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">首页</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $id ? '编辑' : '添加'?></li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onClick="location='admin.php'"><i class="icon-list"></i> 返回管理员列表</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                        <form method="post">
                            <label>用户名</label>
                            <input type="text" name="username" class="input-xxlarge" placeholder="请输入用户名" value="<?php echo $id ? $admin['username'] : ''?>"/>
                            <label>密码</label>
                            <input type="password" name="password" class="input-xxlarge" placeholder="请输入密码" value=""/>
                            <label>邮箱</label>
                            <input type="email" name="email" class="input-xxlarge" placeholder="请输入邮箱" value="<?php echo $id ? $admin['email'] : ''?>"/>
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


