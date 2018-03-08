<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view(TPL_FOLDER."header");?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" >
  <tr>
    <td width="3%" align="left" ><img src="{tpl_path}images/forward.jpg" /></td>
    <td width="97%" align="left" >您现在的位置：焦点图片</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:solid 1px #3C4952; margin-bottom:5px; margin-top:5px">
  <tr>
    <td height="24" align="center" ><span style="cursor:pointer" onClick="RqData()"><img src="{tpl_path}images/add.png"> 添加焦点图片</span> | <a href="<?php echo my_site_url(CTL_FOLDER.'flash/re_cache');?>"><img src="{tpl_path}images/refresh.jpg"  /> 更新缓存</a></td>
  </tr>
</table>
<form method="post" name="list_form" id="list_form">
  <table width="100%" cellspacing="0" class="widefat">
    <thead>
      <tr>
        <th width="5%"><input type="checkbox" onclick="check_box(this.form.rd_id)" /></th>
        <th width="33%">缩略图</th>
        <th width="42%">标题</th>
        <th width="12%">排序</th>
        <th width="8%">操作</th>
      </tr>
    </thead>
    <tfoot>
    </tfoot>
    <tbody id="check_box_id">
      <?php foreach($query as $row) {?>
      <tr>
        <td><input name="rd_id[]" type="checkbox" id="rd_id" value="<?php echo $row->id;?>"></td>
        <td><img src="{tpl_path}images/loading.gif" rel="<?php echo get_real_path($row->pic_path);?>" border="0" class="loading_150" /></td>
        <td><?php echo $row->title;?></td>
        <td><input name="sort<?php echo $row->id;?>" value="<?php echo $row->seqorder;?>" type="text"  id="sort<?php echo $row->id;?>" size="2" maxlength="10" dataType="Integer" msg="排序号必须为数字" title="数字越大越靠前"></td>
        <td><a href="<?php echo site_url(CTL_FOLDER."flash/edit_record/".$row->id);?>">修改</a></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td style="padding-top:5px"><input type="checkbox" onclick="check_box(this.form.rd_id)" />
        全选 <select name="action_url" id="action_url">
          <option value="" style="color:#FF0000">-选择操作-</option>
          <option value="<?php echo site_url(CTL_FOLDER."flash/del_record");?>">删除</option>
          <option value="<?php echo site_url(CTL_FOLDER."flash/sort_record");?>">排序</option>
        </select>&nbsp;<input type="button" name="submit_list_button" class="button-style2" onclick="submit_list_form(this.form,this)" id="submit_list_button" value="执行操作" /> <img src="{tpl_path}images/notice.gif"  title="操作步骤：在左边勾选要操作的记录-->选择需要执行的操作-->点击“执行操作”按钮-->完成" /></td>
    </tr>
  </table>
</form>
<div id="dialog_add" style="display:none; position:relative">
    <form action="<?php echo site_url(CTL_FOLDER."flash/add_record");?>" method="post" enctype="multipart/form-data" name="dialog_add_form" id="dialog_add_form">
        <table width="100%"  border="0" cellpadding="3" cellspacing="0" >
          <tr>
            <td width="133"><font color="#FF0000">*</font>焦点图片标题：</td>
            <td width="592"><input Name="title" type="text" Id="title" size="30" dataType="Require" msg="该项必须填写">
            </td>
          </tr>
          <tr>
            <td width="133">推广链接：</td>
            <td width="592"><input Name="hplink" type="text" Id="hplink" value="#" size="50"> </td>
          </tr>
          <tr>
            <td>上传图片：</td>
            <td><input type="text" name="pic_path" id="pic_path" size="25" /> <input name="pic" type="file" id="pic" size="15" dataType="Filter" accept="<?php echo str_replace("|",",",UP_IMAGES_EXT);?>" require="false" msg="文件格式必须是：<?php echo UP_IMAGES_EXT;?>" title="上传的文件格式必须是：<?php echo UP_IMAGES_EXT;?>,且单个文件不能超过 <?php echo ini_get("upload_max_filesize");?>">  <input type="button" name="sfbtn" class="button-style2" onclick="GetFileDialog('pic_path')" value="选择文件" />
            </td>
          </tr>
           <tr>
        <td><font color="#FF0000">*</font>排序号：</td>
        <td><input name="seqorder" type="text" id="seqorder" size="4" value="0" datatype="Integer" msg="该项必须填写数字" />
        </td>
      </tr>
        </table>
    </form>
</div>
<script language="javascript">
function RqData(){
	$('#dialog_add').dialog({  
		hide:'',      
		autoOpen:false,
		width:750,
		height:300,  
		modal:true, //蒙层  
		title:'添加焦点图片', 
		close: function(){ 
			document.getElementById("dialog_add_form").reset();
			//$(this).dialog('destroy');
		},  
		overlay: {  
			opacity: 0.5, 
			background: "black"
		},  
		buttons:{  
			'取消':function(){$(this).dialog("close");},
			'添加焦点图片':function(){dialog_add();}  
		}
	});
	$('#dialog_add').dialog('open');
}	

function dialog_add(){
	var form_ob=document.getElementById("dialog_add_form"); 
	if (!Validator.Validate(form_ob,3)) return false;
	form_ob.submit();
}
$(function(){
	$(".loading_150").each(function(){
		LoadImage($(this),$(this).attr("rel"),150);
	});
});
</script>
<?php $this->load->view(TPL_FOLDER."footer");?>