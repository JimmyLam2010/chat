<?php
include_once("../includes/init.php");

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $book = $db->select()->from("book")->where("id={$id}")->find();
    @is_file(ASSETS_PATH.$book['thumb']) && @unlink(ASSETS_PATH.$book['thumb']);
    
    $result = $db->delete()->from('book')->where("id={$id}")->getResult();
    
    if($result) {
        show("删除成功","recycle.php");die;
    }else{
        show("删除失败","recycle.php");die;
    }
}

if($ids){
    $result = $db->deleteAll('book', $ids, 'id')->getResult();
    if($result) {
         show("删除成功","recylce.php");die;
     }else{
         show("删除失败","recycle.php");die;
     } 
 }else{
     show("请选择要删除的条目","recycle.php");die;
 }
