<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" Content="text/html; charset=utf-8" />
<Title>网站顶部</Title>
<link href="{tpl_path}style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{root_path}js/jquery/jquery.js"></script>
<script language="javascript">
var displayBar=true;
function switchBar(obj)
{
	if (displayBar)
	{
		top.document.getElementById("mframe").cols="0,*";
		displayBar=false;
		document.getElementById("switch").src="{tpl_path}images/open.gif"
	}
	else {
		top.document.getElementById("mframe").cols="180,*";
		displayBar=true;
		document.getElementById("switch").src="{tpl_path}images/close.gif";
	}
}
function get_time()
{
	var t = new Date();
	var tStr = "";
	tStr += t.getFullYear()+"年"+ parseInt(t.getMonth() + 1) +"月"+t.getDate()+"日 ";
	switch(t.getDay())
	{
		case 0 :
			tStr += "星期日";
			break;
		case 1 :
			tStr += "星期一";	
			break;
		case 2 :
			tStr += "星期二";
			break;	
		case 3 :
			tStr += "星期三";
			break;
		case 4 :
			tStr += "星期四";
			break;
		case 5 :
			tStr += "星期五";
			break;	
		case 6 :
			tStr += "星期六";
			break;	
	}
	tStr += " ";
	tStr += t.getHours()+":"+t.getMinutes()+":"+t.getSeconds();
	document.getElementById("CurrTime").innerHTML = '今天是：'+tStr;
}
setInterval("get_time()",1000);
</script>
<style type="text/css">
body{margin:0px;}
</style>
</head>

<body>
<div id="top">
  <div class="logo">
    <div class="t-right">
       <div class="t-bat">
	   
		 <font color="#FFFFFF"><strong>技术支持：</strong></font><a href="http://bbs.soke5.com/" target="_blank" style="color:#FDF405"><strong>搜客淘宝客官方网站</strong></a>  	   
	   
	   <a href="<?php echo site_url(CTL_FOLDER."admin/modify_password"); ?>" target="main"><img src="{tpl_path}images/topmodps.jpg" /></a><a href="<?php echo site_url(CTL_FOLDER."main/welcome"); ?>" target="main"><img src="{tpl_path}images/bat01.gif" /></a><a href="<?php echo base_url(); ?>" target="_blank"><img src="{tpl_path}images/bat02.gif" /></a><a href="<?php echo site_url(CTL_FOLDER."login/logout"); ?>" target="_top"><img src="{tpl_path}images/bat03.gif" /></a></div>
       <div class="f_r" id="CurrTime"></div>
    </div>
  </div>
</div>
</body>
</html>