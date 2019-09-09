<?php
include_once("../includes/init.php");

$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
$ids = isset($_POST['adminid']) && !empty($_POST['adminid']) ? $_POST['adminid'] : '';

if($id) {
    $result = $db->delete()->from('admin')->where("id={$id}")->getResult();
    if($result) {
        show("删除成功","admin.php");die;
    }else{
        show("删除失败","admin.php");die;
    }
}

if($ids){
   $result = $db->deleteAll('admin', $ids, 'id')->getResult();
   if($result) {
        show("删除成功","admin.php");die;
    }else{
        show("删除失败","admin.php");die;
    } 
}else{
    show("请选择要删除的条目","admin.php");die;
}