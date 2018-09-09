<?php
//设定是否重写
define("REWRITE", true);
//载入配置文件
$Config =json_decode(file_get_contents("./config/conf.json"),true);
$Pages = json_decode(file_get_contents("./config/page.json"),true);
//载入Markdown解析库
include './Library/Markdown/MarkdownExtra.inc.php';
use \Michelf\MarkdownExtra;
include "Library/Lib.php";
$lib = new Lib();
$Page = array();
$Page['Name'] = isset($_GET['page']) ? stripslashes($_GET['page']) : 'index';
//$Page['List'] = explode('/', $Page['Name']);

/*if (count($Page['List']) > 1) {
  $Page['Title'] = $Pages[$Page['List'][0]][$Page['List'][1]];
} else {
  $Page['Title'] = isset($Pages[$Page['Name']]) ? $Pages[$Page['Name']] : '';
}*/

$Page['File'] = 'file/' . $Page['Name'] . '.md';
$Page['Title'] = $Page['Name'];
if (!file_exists($Page['File'])) {
  die('404 Not Found');
}

$handle = @fopen($Page['File'], "r");
if ($handle) {
  $heading = 0;
  while (!feof($handle)) {
    $Page['ContentTemp'] = fgets($handle, 4096);
    $heading = 1;
    $temparray[] = $Page['ContentTemp'];
  }
  $Page['Heading']  = '';
  $temptext = '';

  if ($heading === 1) {
    foreach ($temparray as $key => $val) {
      $temptext = $temptext . "\n" . $val;
    }
    $Page['Heading'] = MarkdownExtra::defaultTransform($temptext);
  } else {
    $Page['Heading'] = '<h1>无标题</h1>';
  }

  $Page['Content'] = '';

  while (!feof($handle)) {
    $Page['Content'] = $Page['Content'] . fgets($handle, 4096);
  }
  fclose($handle);
}
$Page['Output'] = MarkdownExtra::defaultTransform($Page['Content']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $Page['Title']?> - <?php echo $Config['Title']?></title>
  <link href="http://cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://v3.bootcss.com/examples/jumbotron/jumbotron.css" rel="stylesheet">
</head>

<body>
  <style type="text/css">
    body {font-family:"Helvetica Neue",Helvetica,"Segoe UI",Ubuntu,"Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",sans-serif;}
  </style>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><?php echo $Config['Title']?></a>
      </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
                    if(empty($Pages['page'])){

                    }else{
                        foreach($Pages['page'] as $pk=>$pv)
                        {
                            if(is_array($pv)){
                                echo $lib->recursiveList($pv);
                            }else{
                                echo '<li class="active"><a  href="index.php?page='.$pv.'" >'.$pv.'</a></li>';
                            }
                        }
                    }
                 ?>
            </ul>
        </div>

  </nav>

  <div class="jumbotron">
    <div class="container">
      <?php echo $Page['Heading'];?>
    </div>
  </div>

  <main id="main" class="container">
    <?php echo $Page['Output']; ?>
    <hr>
  </main>

  <script src="/JavaScript/jquery.min.js"></script>
  <script src="/JavaScript/bootstrap.min.js"></script>
</body>
</html>