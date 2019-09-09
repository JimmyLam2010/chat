<?php
include_once("../includes/init.php");
include_once("../includes/pagination.php");
include_once("common.php");

$count = $db->select("COUNT(id) AS c")->from("book")->where("display = 'true'")->find();
$count = $count['c'];
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
    $booklist = $db->select("pre_book.*,cate.name")->from("book")->join("cate","pre_book.cateid = cate.id")->where("display='true'")->orderby("pre_book.id","desc")->limit($offset,$limit)->all();
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
            <h1 class="page-title">书籍列表</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">Home</a> <span class="divider">/</span></li>
            <li class="active">Index</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onclick="location='book_add_edit.php'"><i class="icon-list"></i> 添加书籍</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                      <form action="book_delete.php" method="post">
                        <table class="table">
                          <tr>
                              <th><input type="checkbox" name="delete" id="delete" /></th>
                              <th>id</th>
                              <th>书名</th>
                              <th>作者</th>
                              <th>修改时间</th>
                              <th>封面</th>
                              <th>分类</th>
                              <th>章节</th>
                              <th>操作</th>
                          </tr>
                          <?php foreach($booklist as $item) {?>
                          <tr>
                              <td><input type="checkbox" class="items" name="bookid[]" value="<?php echo $item['id'];?>" /></td>
                              <td><?php echo $item['id'];?></td>
                              <td><?php echo $item['title'];?></td>
                              <td><?php echo $item['author'];?></td>
                              <td><?php echo date( "Y-m-d H:i",$item['register_time']);?></td>
                              <?php if(!empty($item['thumb'])){?>
                                <td>
                                  <div class="book_thumb">
                                    <img src="<?php echo ASSETS_PATH.$item['thumb'];?>" />
                                  </div>  
                                </td>
                                <?php }else{?>
                                <td>
                                  <div class="book_thumb">
                                    <img class="img-responsive" src="<?php echo ADMIN_PATH.'/images/cover.png';?>" />
                                  </div>
                                </td>
                                <?php }?>
                              <td><?php echo $item['name'];?></td>
                              <td>
                              <a href='chapter-list.php?bookid=<?php echo $item['id'];?>'><i class="icon-play"></i></a></td>                            
                              <td>
                                  <a href='book_add_edit.php?id=<?php echo $item['id'];?>'><i class="icon-pencil"></i></a>
                                  <a href='book_delete.php?id=<?php echo $item['id'];?>'><i class="icon-remove"></i></a>
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

                <form method="post">
                <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Delete Confirmation</h3>
                    </div>
                    <div class="modal-body">
                        <p class="error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete this</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button onclick="location='book_delete.php?id=<?php echo $item['id'];?>'" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                </form>
                
                <footer>
                    <hr>
                    <p>&copy; 2017 <a href="#" target="_blank">copyright</a></p>
                </footer>
                    
            </div>
        </div>
    </div>
    
    <?php include_once('footer.php');?>
    <script src="<?php echo ADMIN_PATH;?>/js/common.js" type="text/javascript"></script>
    <script>
    function del(bookid)
    {
      $("#bookid").val(bookid);
    }
  </script>
  </body>
</html>