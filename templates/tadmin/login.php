<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" Content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo $site_title;?></title>
<link href="{tpl_path}style/login_style.css" rel="stylesheet" />
<script type="text/javascript" src="{root_path}js/validator.js"></script>
<script language="javascript">
window.onload = function(){document.getElementById('user_name').focus();}
</script>
</head>

<body>
<div class="bg">
  <div style="padding-top:100px; width:228px; margin:0 auto;">
    <form method="post" action="<?php echo site_url(CTL_FOLDER."login/check_login"); ?>" onsubmit="return Validator.Validate(this,2)">
      <table width="228" border="0" cellpadding="0" cellspacing="0" style="background:url({tpl_path}images/login-bg.gif) no-repeat">
        <tr>
          <td width="79" height="32"></td>
          <td height="32"><input name="user_name" id="user_name" type="text" class="login-kk01" dataType="Require" msg="����Ա�˺ű���" maxlength="30"/></td>
        </tr>
        <tr>
          <td height="7" colspan="2"></td>
        </tr>
        <tr>
          <td width="79" height="32"></td>
          <td height="32"><input name="password" type="password" class="login-kk01" dataType="Require" msg="����Ա�������" maxlength="30"/></td>
        </tr>
        <tr>
          <td height="7" colspan="2"></td>
        </tr>
      </table>
      <table width="228" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="58" align="left" valign="bottom"><div style="padding-left:53px">
              <input type="image" src="{tpl_path}images/bat-login.gif" title="��¼" alt="��¼" width="124" height="45" /></div></td>
        </tr>
      </table>
    </form>
  </div>
  <div id="bottom"><a href="<?php echo ROOT_PATH;?>">ǰ̨��ҳ</a> | <a href="<?php echo site_url(CTL_FOLDER."login/find_password");?>">�һع���Ա����</a> |   ����֧�֣�</font><a href="http://bbs.soke5.com/" target="_blank" >�ѿ��Ա���</a></div>
</div>
</body>
</html>
