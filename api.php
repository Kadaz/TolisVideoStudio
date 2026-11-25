<?php
header('Content-Type: application/json; charset=utf-8');
function done($x,$c=200){http_response_code($c);echo json_encode($x,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);exit;}
function bad($s,$c=400){done(['ok'=>false,'error'=>$s],$c);}
$root=__DIR__;foreach(['storage/uploads','storage/projects','storage/exports','storage/tmp'] as $d)if(!is_dir("$root/$d"))mkdir("$root/$d",0775,true);
function project(){ $p=json_decode($_POST['project']??'',true);if(!is_array($p))bad('Invalid project data');return $p;}
$a=$_GET['action']??'';
if($a==='upload'){
 if(empty($_FILES['file'])||$_FILES['file']['error']!==UPLOAD_ERR_OK)bad('Upload failed. Check PHP upload limits.');
 $f=$_FILES['file'];if($f['size']>2*1024*1024*1024)bad('Maximum file size is 2 GB.');
 $mime=mime_content_type($f['tmp_name'])?:'';$type=str_starts_with($mime,'video/')?'video':(str_starts_with($mime,'image/')?'image':(str_starts_with($mime,'audio/')?'audio':''));
 if(!$type)bad('Unsupported media type.');
 $ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));if(!preg_match('/^[a-z0-9]{1,8}$/',$ext))$ext='dat';$id=bin2hex(random_bytes(12));$file="$id.$ext";
 if(!move_uploaded_file($f['tmp_name'],"$root/storage/uploads/$file"))bad('Could not save uploaded media',500);
 done(['ok'=>true,'id'=>$id,'type'=>$type,'path'=>"storage/uploads/$file",'url'=>"storage/uploads/$file"]);
}
if($a==='save'){
 $p=project();$name=preg_replace('/[^A-Za-z0-9_-]/','_',substr($p['name']??'Project',0,50));$f=date('Ymd_His')."_$name.json";$json=json_encode($p,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);if($json===false)bad('Project data could not be encoded as JSON.');if(file_put_contents("$root/storage/projects/$f",$json,LOCK_EX)===false)bad('Could not save the project. Check Laragon storage folder permissions.',500);done(['ok'=>true,'file'=>$f]);
}

bad('Unknown action',404);
?>