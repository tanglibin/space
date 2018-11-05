<?php
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

include "Uploader.php";

//文件名称
$filename = $_GET['filename'];
$config = array(
    "pathFormat" => '/upload/download/'.$filename, //上传地址
    "maxSize" => 204800000,//限制大小：200M
    "allowFiles" => [".rar", ".zip", '.jpg', '.png' ]//上传文件格式
);

//表单名称， 就是 type="file" 对应文本的name
$fileField = 'file';
/* 生成上传实例对象并完成上传 */
$up = new Uploader($fileField , $config);

/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

/* 返回数据 */
echo json_encode($up->getFileInfo());