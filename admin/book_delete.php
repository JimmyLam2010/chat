<?php
include_once("../includes/init.php");

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
$ids = isset($_POST['bookid']) && !empty($_POST['bookid']) ? $_POST['bookid'] : '';

if($id) {
    $book = $db->select()->from("book")->where("id={$id}")->find();
    
    $result = $db->update("book")->set("display='false'")->where("id={$id}")->getResult();
    
    if($result) {
        show("删除成功","book-list.php");die;
    }else{
        show("删除失败","book-list.php");die;
    }
}

if($ids){
   $result = $db->deleteAll('book', $ids, 'id')->getResult();
   if($result) {
        show("删除成功","book-list.php");die;
    }else{
        show("删除失败","book-list.php");die;
    } 
}else{
    show("请选择要删除的条目","book-list.php");die;
}