<?php
include_once("../includes/init.php");
include_once("../includes/pagination.php");
include_once("common.php");

$bookid = isset($_GET['bookid']) && !empty($_GET['bookid']) ? $_GET['bookid'] : 1;
$count = $db->select("COUNT(id) AS c")->from("chapter")->where("bookid=$bookid")->find();
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
    $chapterlist = $db->select("pre_chapter.*,book.title")->from("chapter")->join("book","pre_chapter.bookid = book.id")->where("pre_chapter.bookid=$bookid")->limit($offset,$limit)->all();
    // $sql = $db->select("pre_chapter.*,book.name")->from("chapter")->join("book","pre_chapter.cateid = book.id")->limit($offset,$limit)->getSQL();
    // var_dump($chapterlist);
    // echo $sql;
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
            <h1 class="page-title">章节列表</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">Home</a> <span class="divider">/</span></li>
            <li class="active">Index</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onclick="location='book-list.php'"><i class="icon-list"></i> 返回书籍列表</button>
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
                              <th>标题</th>
                              <th>添加时间</th>
                              <th>书名</th>
                              <th>操作</th>
                          </tr>
                          <?php foreach($chapterlist as $item) {?>
                          <tr>
                              <td><input type="checkbox" class="items" name="chpaterid[]" value="<?php echo $item['id'];?>" /></td>
                              <td><?php echo $item['id'];?></td>
                              <td><?php echo $item['chapter_title'];?></td>
                              <td><?php echo date( "Y-m-d H:i",$item['register_time']);?></td>
                              <td><?php echo $item['title'];?></td>                            
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