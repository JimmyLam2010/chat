<?php
include_once("../includes/init.php");
include_once("../includes/pagination.php");
include_once("common.php");

$count = $db->count('moment');
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
    $momentlist = $db->select()->from("moment")->limit($offset,$limit)->all();
    
    foreach($momentlist as $key=>$item){
        $from = $db->select()->from('user')->where("id={$item['userid']}")->all();
        $item['fromname'] = $from[0]['username'];
        $momentlist[$key] = $item;
    }
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
            <h1 class="page-title">用户列表</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">Home</a> <span class="divider">/</span></li>
            <li class="active">Index</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onclick="location='user_add_edit.php'"><i class="icon-list"></i> 添加用户</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                      <form action="user_delete.php" method="post">
                        <table class="table">
                          <tr>
                              <th><input type="checkbox" name="delete" id="delete" /></th>
                              <th>id</th>
                              <th>发送者</th>
                              <th>内容</th>
                              <th>发送时间</th>
                              <th>查看人数</th>
                              <th>点赞人数</th>
                              <th>操作</th>
                          </tr>
                          <?php foreach($momentlist as $item) {?>
                          <tr>
                              <td><input type="checkbox" class="items" name="cateid[]" value="<?php echo $item['id'];?>" /></td>
                              <td><?php echo $item['id'];?></td>
                              <td><?php echo $item['fromname'];?></td>
                              <td><?php echo $item['content'];?></td>
                              <td><?php echo date( "Y-m-d H:i",$item['createtime']);?></td>
                              <td><?php echo $item['view'];?></td>
                              <td><?php echo $item['like'];?></td>
                              <td>
                                  <a href='user_add_edit.php?id=<?php echo $item['id'];?>'><i class="icon-pencil"></i></a>
                                  <a href='user_delete.php?id=<?php echo $item['id'];?>'><i class="icon-remove"></i></a>
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