<?php
include_once("../includes/init.php");
include_once("common.php");

date_default_timezone_set('PRC');
error_reporting( E_ALL&~E_NOTICE );

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $book = $db->select()->from("book")->where("id={$id}")->find();
} else{
    $booklist = $db->select()->from("book")->all();
}

$catelist = $db->select()->from("cate")->all();

if($_POST) {
    $title = isset($_POST['title']) && !empty($_POST['title']) ? trim($_POST['title']) : '';
    $author = isset($_POST['author']) && !empty($_POST['author']) ? trim($_POST['author']) : '';
    $cateid = isset($_POST['cateid']) && !empty($_POST['cateid']) ? trim($_POST['cateid']) : '';
    $content = isset($_POST['content']) && !empty($_POST['content']) ? trim($_POST['content']) : '';
    $flag = isset($_POST['flag']) && !empty($_POST['flag']) ? trim($_POST['flag']) : 'new';
    // var_dump($thumb);

    $coloum = array(
        'title' => $title,
        'author' => $author,
        'register_time' => time(),
        'content' => $content,
        'thumb' => $id ? $book['thumb'] : '',
        'cateid' => $cateid,
        'flag' => $flag
    );

    if($id) {
        $bookcheck = $db->select()->from("book")->where("title = '$title' AND id != $id")->find();

        //当书籍存在的时候
        if($bookcheck)
        {
          show("该书籍已经存在了，请重新修改","book_add_edit.php?id=$id");
          exit;
        } else{
          //判断是否有文件上传
          if($uploads->isFile('thumb'))
          {
            //判断文件是否上传成功
            if($uploads->upload('thumb'))
            {
              @is_file(ASSETS_PATH.$book['thumb']) && @unlink(ASSETS_PATH.$book['thumb']);
              //获取上传的文件名
              $coloum['thumb'] = $uploads->savefile();

              $result = $db->update("book")->set($coloum)->where("id={$id}")->getResult();

              if($result) {
                // var_dump($_FILES['thumb']['size']);
                show("编辑成功","book-list.php");die;
              }else{
                show("编辑失败","book-list.php");die;
              } 
            }else{
              //显示错误信息
              show($uploads->getMessage());
              exit;
            }
          }else{
            $result = $db->update("book")->set($coloum)->where("id={$id}")->getResult();
            if($result) {
              show("编辑成功","book-list.php");die;
            }else{
              show("编辑失败","book-list.php");die;
            } 
          }
        }

        
    }else{
        $book = $db->select()->from("book")->where("title = '$title'")->find();

        //当书籍存在的时候
        if($book)
        {
          show("该书籍已经存在了，请重新添加","book_add_edit.php");
          exit;
        } else {
          //判断是否有文件上传
          if($uploads->isFile('thumb'))
          {
            //判断文件是否上传成功
            if($uploads->upload('thumb'))
            {
              //获取上传的文件名
              $coloum['thumb'] = $uploads->savefile();
              // echo $uploads->savefile();

              $result = $db->insert("book", $coloum)->getResult();
          
              if($result) {
                  show("添加成功","book-list.php");die;
              }else{
                  show("添加失败","book-list.php");die;
              }
            }else{
              //显示错误信息
              show($uploads->getMessage());
              exit;
            }
          }else{
            $result = $db->insert("book", $coloum)->getResult();
            if($result) {
              show("添加成功","book-list.php");die;
            }else{
              show("添加失败","book-list.php");die;
            }
          }
        }   
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once('meta.php');?>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH?>/plugin/kindeditor/themes/default/default.css" />
    <script src="<?php echo ASSETS_PATH?>/plugin/kindeditor/kindeditor-min.js"></script>
    <script src="<?php echo ASSETS_PATH?>/plugin/kindeditor/lang/zh_CN.js"></script>
    <script>
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="content"]', {
					allowFileManager : true
				});
			});
		</script>
    <style>
      .book_thumb{
        margin: 0;
      }
    </style>
  </head>

  <body> 

    
    <?php include_once('header.php');?>

    <?php include_once('menu.php');?>

    <div class="content">
        <div class="header">
            <h1 class="page-title"><?php echo $id ? '编辑' : '添加'?>书籍</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.php">首页</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $id ? '编辑' : '添加'?></li>
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
                        <form method="post" enctype="multipart/form-data">
                            <label>书籍名称</label>
                            <input type="text" name="title" value="<?php echo $id ? $book['title'] : ''?>" class="input-xxlarge" placeholder="请输入书籍名称">
                            <label>作者</label>
                            <input type="text" name="author" value="<?php echo $id ? $book['author'] : ''?>" class="input-xxlarge" placeholder="请输入作者">
                            <label>封面</label>
                            <input type="file" value="<?php echo $id ? $book['thumb'] : ''?>" class="input-xxlarge" name="thumb" />
                            <?php if(!empty($book['thumb'])){?>
                              <div class="book_thumb">
                                <img class="img-responsive" src="<?php echo ASSETS_PATH.$book['thumb'];?>" />
                              </div>
                            <?php }else{?>
                              <div class="book_thumb">
                                <img class="img-responsive" src="<?php echo ADMIN_PATH.'/images/cover.png';?>" />
                              </div>
                            <?php }?>
                            <br>
                            <br>
                            <label>内容</label>
                            <textarea value="Smith" rows="3" name="content" class="input-xxlarge" placeholder="请输入内容"><?php echo $id ? $book['content'] : '';?></textarea>
                            <label>分类</label>
                            <select name="cateid" class="input-xlarge">
                              <?php foreach($catelist as $item) {?>
                              <option <?php echo $id && $book['cateid']==$item['id'] ? "selected":"";?> value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
                              <?php } ?>
                            </select>
                            <label>Flag</label>
                            <select name="flag" class="input-xlarge">
                              <option value="new" <?php echo $id && $book['flag']=="new" ? "selected":"";?>>最新书籍</option>
                              <option value="hot" <?php echo $id && $book['flag']=="hot" ? "selected":"";?>>网友推荐</option>
                              <option value="top" <?php echo $id && $book['flag']=="top" ? "selected":"";?>>书籍置顶</option>
                            </select>
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
    
    
  </body>
</html>
<?php include_once('footer.php');?>

