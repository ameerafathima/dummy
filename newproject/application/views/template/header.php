<html>
        <head>
        <link rel = "stylesheet" type = "text/css" 
   href = "<?php echo css_url.'/style.css'?>">

        
        </head>
<body>
               <div id="header">
               <?php echo "Welcome" ?>
               <div align="left"><a href='<?php echo "http://192.168.20.245/codeigniterproject/index.php";?>'>Dashboard</a></div>
                 <?php if($value):?> 
                
     <div align="left"> <a href='<?php echo BASE_URL."/welcome/userprofile";?>'>Myprofile</a></div>
     <div align="left"> <a href='<?php echo BASE_URL."/welcome/logout";?>'>logout</a></div>
     
    <?php endif ?> 
    </div>

<!-- <?php if($login):?>



 <?php endif ?> -->

