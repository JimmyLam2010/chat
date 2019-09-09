<?php
include_once("../includes/init.php");

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';

if($id) {
    $book = $db->select()->from("book")->where("id={$id}")->find();
    
    $result = $db->update("book")->set("display='true'")->where("id={$id}")->getResult();
    
    if($result) {
        show("还原成功","recyclebin.php");die;
    }else{
        show("删除失败","recyclebin.php");die;
    }
}