﻿<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view(TPL_FOLDER."header");?>
<table width="100%" cellspacing="0" class="widefat">
  <thead>
    <tr>
      <th colspan="2">ϵͳ��Ϣ</th>
    </tr>
  </thead>
  <tbody id="check_box_id">
    <tr>
      <td height="30"><strong>������ (IP���˿�)��</strong><?php echo $_SERVER['SERVER_NAME']?>(<?php echo $this->input->ip_address().":".$_SERVER['SERVER_PORT'];?>)</td>
      <td ><strong>����Ŀ¼��</strong><?php echo str_replace('templates\tadmin','',dirname( 'DADCCCDFFACFBA'  ));?></td>
    </tr>
    <tr>
      <td height="30" ><strong>Web��������</strong><?php echo $_SERVER['SERVER_SOFTWARE']?></td>
      <td ><strong>PHP ���з�ʽ��</strong><?php echo PHP_SAPI?></td>
    </tr>
    <tr>
      <td height="30" ><strong>PHP�汾��</strong><?php echo PHP_VERSION?>??<span style="color:#999999">(��Ҫ >= PHP4.0.2)</span></td>
      <td ><strong>����ϴ����ƣ�</strong><?php echo ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<span style="color:red">Disabled</span>';?></td>
    </tr>
    <tr>
      <td height="30" ><strong>���ִ��ʱ�䣺</strong><?php echo ini_get('max_execution_time')?> seconds</td>
      <td ><strong>Զ���ļ���ȡ��</strong><?php echo ini_get('allow_url_fopen') ? '֧��' : '��֧��'?>??<span style="color:#FF6600">(�����������֧�ֽ��޷�ʹ�øó���)</span></td>
    </tr>
    <tr>
      <td height="30" ><strong>����Ա�˺ţ�</strong><?php echo $sys_user_name;?></td>
      <td ><strong>�ϴε�½IP��</strong><?php echo $sys_last_login_ip;?></td>
    </tr>
    <tr>
      <td height="30" ><strong>�ϴε�½ʱ�䣺</strong><?php echo $sys_last_login_time;?></td>
      <td ><strong>��½������</strong><?php echo $sys_hits;?></td>
    </tr>
    <tr>
      <td height="30" ><strong>�����Ŷӣ�</strong><a href="http://bbs.soke5.com" target="_blank" style="color:#FF0000">�ѿ��Ա��͹ٷ���վ</a> ?? <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=732515587&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:732515587:45" alt="���������ҷ���Ϣ" title="���������ҷ���Ϣ" style="vertical-align:middle"> 732515587</a></td>
      <td ><strong>����汾��</strong><script type="text/javascript" src="http://bbs.soke5.com/ad/1005/789.js"></script> </td>
    </tr>
  </tbody>
</table>
<br />
<table class="widefat" cellspacing="0">
  <thead>
    <tr>
      <th><div class="allbtn" style="width:100px"><span class="ui-icon ui-icon-info"></span> <span>���������Ϣ</span></div></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="left"><div class="w_tips">
          <ul>
          <li>����ɾ�����е����²������ݣ����·���ɲ�ɾ����Ȼ�����²ɼ����ɾ�̬ҳ�档</li>
            <li>������ǵ�һ��ʹ�ø���վ��̨��<a style="color:#dd0000;" href="<?php echo site_url(CTL_FOLDER.'site_config');?>">���ȵ�������޸���վ��Ϣ����</a>,�޸�ʱ���ϸ�����ʾ��д����¼����ԭ�����ִ����ǰ�档 </li>
            <li>������ǵ�һ��ʹ�ø���վ��̨�������ҳ�棺����Ա���á���>�޸����� �޸����Ĺ������䣬�����ǹ��������ʱ�� ����ͨ���������һ����롣 </li>
            
          </ul>
        </div></td>
    </tr>
  </tbody>
</table>

<table class="widefat" cellspacing="0">
  <thead>
    <tr>
      <th><div class="allbtn" style="width:100px"><span class="ui-icon ui-icon-info"></span> <span>���¹ٷ�����</span></div></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="left"><div class="w_tips2">
          <ul>
 
          </ul>
        </div></td>
    </tr>
  </tbody>
</table>

<?php $this->load->view(TPL_FOLDER."footer");?>
