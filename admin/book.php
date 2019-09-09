<?php
include_once("../includes/init.php");
include_once("../includes/collect.php");
include_once("common.php");

error_reporting( E_ALL&~E_NOTICE );

$websitelist = $db->select()->from("website")->all();

if($_POST)
{

  $url = isset($_POST['url']) ? trim($_POST['url']) : false;
  $bookid = isset($_POST['bookid']) ? trim($_POST['bookid']) : 0;
  $websiteid = isset($_POST['websiteid']) ? trim($_POST['websiteid']) : 0;

  if(!$url)
  {
    show('采集地址不对','book.php');
    exit;
  }

  $ulname = $db->select('ul_name')->from('website')->where("id={$websiteid}")->find();
  $contentname = $db->select('content_name')->from('website')->where("id={$websiteid}")->find();
  // var_dump($contentname);

  $res = getList($url,$ulname['ul_name']);

  $lilist = $res[1];
  $liReg = "/<a.*href=\"(.*)\".*>(.*)<\/a>/misU";
  preg_match_all($liReg,$lilist,$result);
  $urllist = $result[1];
  $titlelist = $result[2];

  $chapterList = [];

  foreach($urllist as $key=>$item)
  {
    $text = getContent($item, $contentname['content_name']);
    $content = strip_tags($text[1]);
    // var_dump(file_get_contents($item));
    // echo "<br>";
    $arr = array("title"=>$titlelist[$key],"content"=>strip_tags($content));
    $json = json_encode($arr);
    //保存文件
    $time = date("Ymd");
    $path = APP_PATH."/assets/book/$time";
    if(!is_dir($path))
    {
      mkdir($path,0777,true);
    }
    $filename = $Strings->randomStr(20,false).".json";
    $length = @file_put_contents($path."/".$filename,$json);
    if($length > 0)
    {
      $chapterList[] = array(
        "register_time"=>time(),
        "title"=>$titlelist[$key],
        "content"=>"/book/$time/$filename",
        "bookid"=>$bookid
      );
    }
    
  }

  if(is_array($chapterList) && count($chapterList) > 0)
  {
    var_dump($chapterlist);
    $affectRow = $db->addAll("chapter",$chapterList);
    show("该书籍新增了{$affectRow}章内容","book-list.php");
    exit;
  }else{
    // var_dump($res);
    show("当前采集节点无数据","book.php");
    exit;
  }

}


$booklist = $db->select()->from("book")->all();

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
            <h1 class="page-title">发布文章</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="book-list.php">Home</a> <span class="divider">/</span></li>
            <li class="active">Index</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    
                <div class="btn-toolbar">
                    <button class="btn btn-primary" onClick="location='book-list.php'"><i class="icon-list"></i> 返回书籍列表</button>
                  <div class="btn-group">
                  </div>
                </div>

                <div class="well">
                    <div id="myTabContent" class="tab-content">
                      <div class="tab-pane active in" id="home">
                        <form method="post">
                            <label>书籍名称</label>
                            <select name="bookid" class="input-xlarge" required>
                              <option value="">请选择</option>
                              <?php foreach($booklist as $item){?>
                                <option value="<?php echo $item['id'];?>"><?php echo $item['title'];?></option>
                              <?php }?>
                            </select>
                            <label>选择采集节点</label>
                            <select name="websiteid" class="input-xlarge" required>
                              <option value="">请选择</option>
                              <?php foreach($websitelist as $item){?>
                                <option value="<?php echo $item['id'];?>"><?php echo $item['website_name'];?></option>
                              <?php }?>
                            </select>
                            <label>采集地址</label>
                            <input type="text" name="url" required value="" class="input-xxlarge" placeholder="请输入采集地址" />
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


