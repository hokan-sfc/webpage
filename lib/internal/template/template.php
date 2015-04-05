<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="keywords" content="星空観賞サークル,ほかん,hokan,SFC,天文">
<meta name="description" content="慶應義塾大学SFC 星空観賞サークル オフィシャルサイト">
<meta name="copyright" content="慶應義塾大学 星空観賞サークル">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>星空観賞サークル</title>

<!-- Humans Text -->
<link rel="author" type="text/plain" href="/humans.txt">

<!-- Fabicon -->
<link rel="icon" type="image/vnd.microsoft.icon" href="/lib/internal/template/img/favicon.ico">

<!-- Style sheets -->
<link rel="stylesheet" type="text/css" href="/lib/external/normalize/css/normalize.css">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Nunito:300">
<link rel="stylesheet" type="text/css" href="/lib/external/glyphicons/css/glyphicons.css">
<link rel="stylesheet" type="text/css" href="/lib/internal/template/css/navigation.css">
<link rel="stylesheet" type="text/css" href="/lib/internal/template/css/contents.css">
<link rel="stylesheet" type="text/css" href="/lib/internal/template/css/common.css">
<link rel="stylesheet" type="text/css" href="/lib/internal/template/css/footer.css">
<?php   if(isset($stylesheets)): ?>
<?php       if(file_exists($stylesheets)): ?>
<?php           include $stylesheets ?>
<?php       else: ?>
<?php           echo $stylesheets ?>
<?php       endif; ?>
<?php   endif; ?>

<!-- JavaScript -->
<?php   if(isset($javascripts)): ?>
<?php       if(file_exists($javascripts)): ?>
<?php           include $javascripts ?>
<?php       else: ?>
<?php           echo $javascripts ?>
<?php       endif; ?>
<?php   endif; ?>

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if (gte IE 6)&(lte IE 8)]>
<script src="http://cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js"></script>
<![endif]-->
</head>

<body>
<div id="main">
<?php   if(isset($body)): ?>
<?php       if(file_exists($body)): ?>
<?php           include $body ?>
<?php       else: ?>
<?php           echo $body ?>
<?php       endif; ?>
<?php   endif; ?>
</div>
<footer><p>&copy; 2014 Starlit sky appreciation circle</p></footer>
</body>
</html>
