<?php
include_once("../includes/init.php");
include_once("../includes/pagination.php");
include_once("common.php");

$count = $db->count('admin');
$count = $count['count'];
// 当前是第几页
$page = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1;

// 每页多少条数据
$limit = 6;
// 下标 
$offset = ($page - 1 ) * $limit;
// 总页数
$totalPage = ceil($count / $limit);

// 总数大于0，进行分页。
if( $count ) {
    // 2. 连表 倒序 查询 文章和文章分类
    $adminlist = $db->select()->from("admin")->limit($offset,$limit)->all();
    // foreach($data as $key=>$value){
    //    $data[$key]['title_cut'] = cutstr($value['title'], 0, 10, '...');
    // }
}
// 3. 生成分页字符串
$pageStr = pagination($count, $page, $limit, $totalPage);

date_default_timezone_set('PRC');
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
            <h1 class="page-title">管理员列表</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">Home</a> <span class="divider">/</span></li>
            <li class="active">Index</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onclick="location='admin_add_edit.php'"><i class="icon-list"></i> 添加管理员</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                      <form action="admin_delete.php" method="post">
                        <table class="table">
                          <tr>
                              <th><input type="checkbox" name="delete" id="delete" /></th>
                              <th>id</th>
                              <th>用户名</th>
                              <th>头像</th>
                              <th>邮箱</th>
                              <th>注册时间</th>
                              <th>操作</th>
                          </tr>
                          <?php foreach($adminlist as $item) {?>
                          <tr>
                              <td><input type="checkbox" class="items" name="adminid[]" value="<?php echo $item['id'];?>" /></td>
                              <td><?php echo $item['id'];?></td>
                              <td><?php echo $item['username'];?></td>
                              <?php if(!empty($item['thumb'])){?>
                              <td>
                                <div class="user_thumb">
                                  <img src="<?php echo UPLOAD_PATH.$item['thumb'];?>" />
                                </div>
                              </td>
                              <?php }else{?>
                              <td>
                                <div class="user_thumb">
                                  <img class="img-responsive" src="<?php echo ADMIN_PATH.'/images/cover.png';?>" />
                                </div>
                              </td>
                              <?php }?>
                              <td><?php echo $item['email'];?></td>
                              <td><?php echo date( "Y-m-d H:i",$item['register_time']);?></td>
                              <td>
                                  <a href='admin_add_edit.php?id=<?php echo $item['id'];?>'><i class="icon-pencil"></i></a>
                                  <a href='admin_delete.php?id=<?php echo $item['id'];?>'><i class="icon-remove"></i></a>
                              </td>
                          </tr>
                          <?php }?>
                          <tr>
                            <td colspan="20" style="text-align:left;">
                              <input class="btn btn-primary" type="submit" value="批量删除">
                            </td>
                          </tr>
                        </table>
                      </form>
                      </div>
                      <nav aria-label="Page navigation">
                        <ul class="pagination float-right">
                            <?php echo $pageStr; ?>
                        </ul>
                    </nav>
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
    <script src="<?php echo ADMIN_PATH;?>/js/common.js" type="text/javascript"></script>
  </body>
</html>