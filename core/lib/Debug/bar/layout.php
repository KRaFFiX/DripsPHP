<?php

use DripsPHP\Config\Config;
use DripsPHP\Routing\Request;
use DripsPHP\Language\Detector;

$showPhpInfo = Config::get('debug-bar-phpinfo');
$route = Request::$currentRoute;
?>
<!-- [BEGIN] DripsPHP DebugBar -->
<style type="text/css">
	<?php include __DIR__.'/style.css';?>
</style>

<div id="dp-debug-bar">
	<nav>
		<a href="javascript:dpDebugShowBox('dp-debug-bar-variables')">Variables</a>
		<?php if ($showPhpInfo): ?>
		<a href="javascript:dpDebugShowBox('dp-debug-bar-phpinfo')">PHP</a>
		<?php endif;?>
	</nav>
	<div>
		<span><?=$_SERVER['REQUEST_METHOD'];?></span>
		<span><?=$route->getName();?></span>
		<?php
        if (!is_callable($route->getCallbacks()[0])) {
            echo '<span>'.$route->getCallbacks()[0].'</span>';
        }
        ?>
		<span><?=http_response_code();?></span>
		<span><?=$_ENV['DB_CONNECTED'] ? "<strong style='color:green;'>DB</strong>" : "<strong style='color:red;'>DB</strong>";?></span>
		<span><?=Detector::getCurrentLanguage();?></span>
		<span><?=date('d.m.y H:i');?></span>
		<span><?=sprintf('%.3fs', DRIPS_DURATION);?></span>
	</div>
</div>

<div id='dp-debug-bar-box'>
	<div class='dp-debug-bar-box-item' id="dp-debug-bar-variables">
		<?php
        $variables = array(
            '$_POST' => $_POST,
            '$_GET' => $_GET,
            '$_SESSION' => $_SESSION,
            '$_COOKIE' => $_COOKIE,
            '$_SERVER' => $_SERVER,
        );
        foreach ($variables as $key => $var) {
            if (!empty($var)) {
                echo "<h1>$key</h1>";
                echo "<table width='100%' cellpadding='5'>";
                foreach ($var as $k => $v) {
                    echo "<tr><td>$k</td><td>";
                    var_dump($v);
                    echo '</td></tr>';
                }
                echo '</table>';
                echo '<hr/>';
            }
        }
        ?>
	</div>
	<?php if ($showPhpInfo): ?>
	<div class='dp-debug-bar-box-item' id="dp-debug-bar-phpinfo">
		<?php
        ob_start();
        phpinfo();
        $phpinfo = ob_get_contents();
        ob_end_clean();
        echo $phpinfo;
        ?>
	</div>
	<?php endif;?>
</div>

<script type="text/javascript">
	var dpDebugBoxIsOpen = false;
	var dpDbeugIdBefore = "";

	function dpDebugShowBox(id){
		if(!dpDebugBoxIsOpen){
			dpDebugBoxOpen();
		} else if(dpDbeugIdBefore == id){
			dpDebugBoxClose();
		}
		var boxItems = document.getElementsByClassName("dp-debug-bar-box-item");
		var boxItemSize = boxItems.length;
		for(var i = 0; i < boxItemSize; i++){
			boxItems[i].style.display = "none";
		}
		document.getElementById(id).style.display = "block";
		dpDbeugIdBefore = id;
	}

	function dpDebugBoxOpen(){
		document.getElementById("dp-debug-bar").className = "dp-open";
		document.getElementById("dp-debug-bar-box").className = "dp-open";
		dpDebugBoxIsOpen = true;
	}

	function dpDebugBoxClose(){
		document.getElementById("dp-debug-bar").className = "";
		document.getElementById("dp-debug-bar-box").className = "";
		dpDebugBoxIsOpen = false;
	}
</script>
<!-- [END] DripsPHP DebugBar -->
