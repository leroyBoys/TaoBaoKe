<?php
 if (!defined('BASEPATH')) exit('No direct script access allowed'); function get_file_size($url) { return filesize($url)?filesize($url):0; } function get_real_path($url) { if(!$url) return ''; if(strpos($url, '//') === 0) { return 'http:' . $url; } else if(strpos($url, "http://") === FALSE && strpos($url, 'https://') === FALSE) { return ROOT_PATH . $url; } else { return $url; } } function clear_space($str) { if(!$str) return $str; $search = array ("'([\s])+'"); $replace = array (''); return @preg_replace($search, $replace, $str); } function get_taobao_item($detail_url) { $c = get_url_content($detail_url); if(!$c) return false; $c = @iconv("gbk", "utf-8//IGNORE", $c); $t = & get_instance(); $c = $t -> trans -> t2c($c); unset($detail_url); @preg_match('/<title>(.*?)<\/title>/i', $c, $a); if(!isset($a[1])) return FALSE; $title = str_replace('-�Ա���', '', $a[1]); $shop_price = 0; preg_match('/<em class="tb-rmb-num">(.*?)<\/em>/i', $c, $a); if(isset($a[1])) $shop_price = $a[1]; if(strpos($shop_price, '-') !== FALSE) { $shop_price = strstr($shop_price, '-'); $shop_price = str_replace('-', '', $shop_price); $shop_price = (float)$shop_price; } if($shop_price == 0) return false; $nick = ''; preg_match('/sellerNick:"(.*?)"/i', $c, $a); if(isset($a[1])) { $nick = $a[1]; } $user_id = 0; preg_match('/userid=(\d+)/i', $c, $a); if(isset($a[1]) && $a[1]) { $user_id = $a[1]; } $pic_url = array(); preg_match_all('/<img data-src="(.*?)" \/>/i', $c, $a); if(isset($a[1])) { foreach($a[1] as $v) { $pic_url[] = substr($v, 0, strrpos($v, '_')); } } if(empty($pic_url)) { preg_match_all('/<a href="#"><img src="((.*?)_60x60(.*?)\.jpg)" \/><\/a>/i', $c, $a); if(isset($a[2])) $pic_url = $a[2]; } $content = ''; $t -> load -> config('is_desc'); if($t -> config -> item('is_desc') == 1) { preg_match('/"apiItemDesc":"(.*?)"/i', $c, $a); if(isset($a[1])) { $content = get_url_content(get_real_path($a[1])); $content = @iconv("gbk", "utf-8//IGNORE", $content); $content = $t -> trans -> t2c($content); $content = ltrim($content, "var desc='"); $content = str_replace("';", '', $content); } } unset($c); return array( 'title' => $title, 'nick' => $nick, 'user_id' => $user_id, 'shop_price' => $shop_price, 'pic_url' => $pic_url, 'content' => $content ); } function get_tmall_item($detail_url) { $c = get_url_content($detail_url); if(!$c) return false; $c = @iconv("gbk", "utf-8//IGNORE", $c); $t = & get_instance(); $c = $t -> trans -> t2c($c); unset($detail_url); @preg_match('/<title>(.*?)<\/title>/i', $c, $a); if(!isset($a[1])) return FALSE; $title = str_replace('-tmall.com��è', '', $a[1]); $shop_price = 0; preg_match('/"price":"(.*?)"/i', $c, $a); if(isset($a[1])) $shop_price = (float)$a[1]; if($shop_price == 0) { preg_match('/price:(.*?),/i', $c, $a); if(isset($a[1])) $shop_price = (float)$a[1]; } if($shop_price == 0) { preg_match('/"defaultItemPrice":"(.*?)"/i', $c, $a); if(isset($a[1])) $shop_price = (float)$a[1]; } if(strpos($shop_price, '-') !== FALSE) { $shop_price = strstr($shop_price, '-'); $shop_price = (float)str_replace('-', '', $shop_price); } if($shop_price == 0) return false; $nick = ''; preg_match('/<input type="hidden" name="seller_nickname" value="(.*?)" \/>/i', $c, $a); if(isset($a[1])) { $nick = $a[1]; } $user_id = 0; preg_match('/userid=(\d+)/i', $c, $a); if(isset($a[1]) && $a[1]) { $user_id = $a[1]; } $pic_url = array(); preg_match_all('/<a href="#"><img src="((.*?)_60x60(.*?)\.jpg)" \/><\/a>/i', $c, $a); if(isset($a[2])) $pic_url = $a[2]; $content = ''; $t -> load -> config('is_desc'); if($t -> config -> item('is_desc') == 1) { preg_match('/"descUrl":"(.*?)"/i', $c, $a); if(isset($a[1])) { $content = get_url_content(get_real_path($a[1])); $content = @iconv("gbk", "utf-8//IGNORE", $content); $content = $t -> trans -> t2c($content); $content = ltrim($content, "var desc='"); $content = str_replace("';", '', $content); } } unset($c); return array( 'title' => $title, 'nick' => $nick, 'user_id' => $user_id, 'shop_price' => $shop_price, 'pic_url' => $pic_url, 'content' => $content ); } function get_product($num_iid) { $item = get_dv($num_iid); if($item['is_tmall']) { $detail_url = 'http://detail.tmall.com/item.htm?id=' . $num_iid; $r_a = get_tmall_item($detail_url); } else { $detail_url = 'http://item.taobao.com/item.htm?id=' . $num_iid; $r_a = get_taobao_item($detail_url); } $item['num_iid'] = $num_iid; $item['detail_url'] = $detail_url; if(is_array($r_a)) $item = array_merge($r_a, $item); else return false; unset($r_a); if(isset($item['shop_price']) && $item['dc_price'] == $item['shop_price']) $item['dc_price'] = 0; return $item; } function get_dv($num_iid) { $url = 'http://s.etao.com/detail/' . $num_iid . '.html'; $c = get_url_content($url); if(!$c) return array('dc_price' => 0, 'volume' => 0, 'is_tmall' => false); $c = @iconv("gbk", "utf-8//IGNORE", $c); $t = & get_instance(); $t -> load -> library('trans'); $c = $t -> trans -> t2c($c); $c = clear_space($c); $dc_price = $volume = 0; @preg_match('/<spanclass=\'real-price-num\'>(.*?)<\/span>/i', $c, $a); if(isset($a[1]) && $a[1] > 0) $dc_price = $a[1]; @preg_match('/��<\/span>(\d+)��<span/i', $c, $a); if(isset($a[1]) && $a[1] > 0) $volume = $a[1]; if(stripos($c, 'rate.tmall.com') !== FALSE) $is_tmall = TRUE; else $is_tmall = FALSE; unset($c, $a); return array('dc_price' => $dc_price, 'volume' => $volume, 'is_tmall' => $is_tmall); } function get_taodianjin() { $ci = & get_instance(); $ci -> load -> helper('file'); $path = 'js/'; if (!file_exists($path . 'djin.js')) { return FALSE; } $data = read_file($path . 'djin.js'); return $data; } function get_u_num_iids($url) { $c = get_url_content($url); if(!$c) return ''; $ci = & get_instance(); $ci -> load -> config('rule_config'); $str = ''; foreach($ci -> config -> item('rule_config') as $item) { @preg_match_all($item['reg'], $c, $a); if(isset($a[$item['offset']]) && !empty($a[$item['offset']])) { $str = implode(',', array_unique($a[$item['offset']])); break; } } return $str; } function get_shop($url) { $c = get_url_content($url); if(!$c) return FALSE; $c = @iconv("gbk", "utf-8//IGNORE", $c); $t = & get_instance(); $t -> load -> library('trans'); $c = $t -> trans -> t2c($c); $title = ''; @preg_match('/<title>(.*)<\/title>/i', $c, $a); if(isset($a[1]) && $a[1]) $title = str_replace(array('- ��èTmall.com', '-�Ա���', '��ҳ-'), array('', '', ''), $a[1]); $sid = 0; @preg_match('/userId=(\d+)/i', $c, $a); if(isset($a[1]) && $a[1] > 0) $sid = $a[1]; return array('title' => $title, 'sid' => $sid, 'shop_url' => $url); } function get_url_content($url) { if(function_exists('curl_init')) { $header = array(); $header[] = 'Accept:Mozilla/5.0 (Windows NT 5.2; rv:26.0) Gecko/20100101 Firefox/26.0'; $header[] = 'Cookie:cna=bptbC4Ku9ToCAXQWMZEIokiu; lzstat_uv=34569141933663802993|3369100; tracknick=%5Cu590F%5Cu6587%5Cu8F69; _cc_=Vq8l%2BKCLiw%3D%3D; tg=0; l=%E5%A4%8F%E6%96%87%E8%BD%A9::1394382946473::11; t=18ef722fcee2b996668e0845e78d031b; v=0; cookie2=1f36d08de108b93dbd594d443c241d92; mt=ci%3D-1_0; isg=5C99E8C7D9AC030958EB4FADC4DEB51A; _tb_token_=ed3b9e0b0ee7a'; $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_FAILONERROR, false); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_POST, false); curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_REFERER, $url); @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); @curl_setopt($ch, CURLOPT_MAXREDIRS, 10); @curl_setopt($ch, CURLOPT_HTTPHEADER, $header); $pageContent = @curl_exec($ch); curl_close($ch); return $pageContent; } $t = & get_instance(); $t -> load -> library('snoopy'); $t -> snoopy -> agent = 'Mozilla/5.0 (Windows NT 5.2; rv:26.0) Gecko/20100101 Firefox/26.0'; $t -> snoopy -> cookies['tracknick'] = '%5Cu590F%5Cu6587%5Cu8F69'; $t -> snoopy -> cookies['cookie2'] = '1f36d08de108b93dbd594d443c241d92'; $t -> snoopy -> referer = $url; $t -> snoopy -> fetch($url); if($pageContent = $t -> snoopy -> results) return $pageContent; return false; } function get_content($url) { if(function_exists('curl_init')) { $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_FAILONERROR, false); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_POST, false); curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); @curl_setopt($ch, CURLOPT_MAXREDIRS, 10); $pageContent = @curl_exec($ch); curl_close($ch); } else { $pageContent = @file_get_contents($url); } return $pageContent; } function filter_script($document) { $document = trim($document); if (strlen($document) <= 0) { return $document; } $search = array ("'<script[^>]*?>.*?</script>'si", "'<vbscript[^>]*?>.*?</vbscript>'si", "'<style[^>]*?>.*?</style>'si", "'<link[^>]*?/>'si", "'<meta[^>]*?/>'si", "'<frameset[^>]*?>.*?</frameset>'si", "'<frame[^>]*?>.*?</frame>'si", "'<form[^>]*?>.*?</form>'si", "'<applet[^>]*?>.*?</applet>'si", "'<body[^>]*?>.*?</body>'si", "'<html[^>]*?>.*?</html>'si", "'<head[^>]*?>.*?</head>'si" ); $replace = array ('', '', '', '', '', '', '', '', '', '', '', '', ''); return @preg_replace ($search, $replace, $document); } function create_query($k) { parse_str($_SERVER['QUERY_STRING'], $get); if(is_array($get)) { foreach($get as $k1 => $v1) { if($k1 == $k) unset($get[$k1]); else $get[$k1] = $k1 . '=' . urlencode($v1); } return implode('&', $get); }else return ''; } function do_upload($up_config) { if(is_array($up_config)) { foreach($up_config as $key => $value) { $$key = $value; } } else { return array('status' => FALSE, 'upload_errors' => "<li>���ò�������</li>"); } if (!file_exists($up_path)) { @mkdir($up_path); } $up_path = rtrim($up_path, '/'); $up_path .= '/' . date("Ymd", time()) . '/'; if (!file_exists($up_path)) { @mkdir($up_path); } $config['upload_path'] = $up_path; if(isset($suffix)) $config['allowed_types'] = $suffix; $config['encrypt_name'] = TRUE; if(isset($max_size)) $config['max_size'] = $max_size; $my_obj = & get_instance(); $my_obj -> load -> library('upload'); $my_obj -> upload -> initialize($config); if (!$my_obj -> upload -> do_upload($form_name)) { if($_FILES[$form_name]['name']) { $file_data = array('status' => FALSE, 'upload_errors' => $my_obj -> upload -> display_errors('<li>', '</li>')); } else { $file_data = array('status' => TRUE, 'file_path' => ''); } return $file_data; } else { $data = $my_obj -> upload -> data(); $file_data = array('status' => TRUE, 'file_path' => $up_path . $data['file_name'], 'data' => $data); return $file_data; } } function create_thumb($pic, $w = 0, $h = 0, $is_overwrite = FALSE) { $ms = @get_loaded_extensions(); if(in_array("gd", $ms)) { $config['image_library'] = 'gd2'; } else { return false; } $my_obj = & get_instance(); if($w == 0 || $h == 0) { $my_obj -> config -> load('image_config', FALSE, TRUE); $config['width'] = $my_obj -> config -> item("thumb_width"); $config['height'] = $my_obj -> config -> item("thumb_height"); } else { $config['width'] = $w; $config['height'] = $h; } if(!$is_overwrite) $config['create_thumb'] = TRUE; $config['maintain_ratio'] = TRUE; $my_obj -> load -> library('image_lib'); if(is_array($pic)) { foreach($pic as $row) { $config['source_image'] = $row -> big_pic_path; $my_obj -> image_lib -> initialize($config); $my_obj -> image_lib -> resize(); $my_obj -> image_lib -> clear(); } } else { $config['source_image'] = $pic; $my_obj -> image_lib -> initialize($config); $my_obj -> image_lib -> resize(); $my_obj -> image_lib -> clear(); } return TRUE; } function my_site_url($uri = '') { $my_obj = & get_instance(); if (is_array($uri)) { $uri = implode('/', $uri); } $index_page = $my_obj -> config -> item('index_page') ?$my_obj -> config -> item('index_page') . '/':''; if ($uri == '') { return ROOT_PATH . $index_page; } else { $suffix = ($my_obj -> config -> item('url_suffix') == FALSE) ?'': $my_obj -> config -> item('url_suffix'); return ROOT_PATH . $index_page . trim($uri, '/') . $suffix; } } function get_curren_url() { $r_uri = ''; if(isset($_SERVER["REQUEST_URI"]) && !empty($_SERVER["REQUEST_URI"])) { $r_uri = $_SERVER["REQUEST_URI"]; } else if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER["PATH_INFO"])) { $r_uri = $_SERVER['SCRIPT_NAME'] . $_SERVER["PATH_INFO"]; if($_SERVER['QUERY_STRING']) $r_uri .= '?' . $_SERVER['QUERY_STRING']; } $r_uri = 'http://' . $_SERVER['SERVER_NAME'] . $r_uri; $my_obj = & get_instance(); if(!$my_obj -> config -> item('index_page')) $r_uri = str_replace('/index.php', '', $r_uri); return urlencode($r_uri); } function getBytes($folder) { $totalSize = 0; if($handle = @opendir($folder)) { while($file = readdir($handle)) { if($file != "." && $file != "..") { if(is_dir($folder . "/" . $file)) { $totalSize = $totalSize + getBytes($folder . "/" . $file); } else { $totalSize = $totalSize + filesize($folder . "/" . $file); } } } closedir($handle); } return $totalSize; } function diem($msg = '') { global $CI; if (class_exists('CI_DB') && isset($CI -> db)) { $CI -> db -> close(); } die($msg); } function echo_msg($msg, $rdr = '', $infotype = 'no', $target = '_self') { if(empty($rdr)) { if (isset($_SERVER['HTTP_REFERER'])) $rdr = $_SERVER['HTTP_REFERER']; else $rdr = "javascript:window.history.back();"; } $tx_msg = array('infotype' => $infotype, 'infos' => $msg, 'red_url' => $rdr, 'target' => $target); $my_obj = & get_instance(); global $CI; if (class_exists('CI_DB') && isset($CI -> db)) { $CI -> db -> close(); } die($my_obj -> load -> view(TPL_FOLDER . 'msg', $tx_msg, true)); } function unescape($str){ $ret = ''; $len = strlen($str); for ($i = 0;$i < $len;$i++){ if ($str[$i] == '%' && $str[$i + 1] == 'u'){ $val = hexdec(substr($str, $i + 2, 4)); if ($val < 0x7f) $ret .= chr($val); else if($val < 0x800) $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f)); else $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f)); $i += 5; } else if ($str[$i] == '%'){ $ret .= urldecode(substr($str, $i, 3)); $i += 2; } else $ret .= $str[$i]; } return $ret; } function escape($str) { preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e", $str, $r); $str = $r[0]; $l = count($str); for($i = 0;$i < $l;$i++) { $value = ord($str[$i][0]); if($value < 223) { $str[$i] = rawurlencode(utf8_decode($str[$i])); } else { $str[$i] = "%u" . strtoupper(bin2hex(iconv("UTF-8", "UCS-2", $str[$i]))); } } return join("", $str); } function strcut($string, $length) { $strlen = strlen($string); if($strlen <= $length) return $string; $n = 0; $i = 1; while($n < $strlen) { $t = ord($string[$n]); if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)){ $n++; } elseif(194 <= $t && $t <= 223){ $n += 2; } elseif(224 <= $t && $t <= 239){ $n += 3; } elseif(240 <= $t && $t <= 247){ $n += 4; } elseif(248 <= $t && $t <= 251){ $n += 5; } elseif($t == 252 || $t == 253) { $n += 6; } else { $n++; } if($i >= $length) break; $i++; } return substr($string, 0, $n); } function format_curren($v) { if(is_numeric($v)) return '��' . number_format($v, 2); else return '��' . number_format(0, 2); } function replace_htmlAndjs($document) { $document = trim($document); if (strlen($document) <= 0) { return $document; } $search = array ("'<script[^>]*?>.*?</script>'si", "'<[\/\!]*?[^<>]*?>'si", "'([\r\n\t\s]+)'", "'&(quot|#34);'i", "'&(amp|#38);'i", "'&(lt|#60);'i", "'&(gt|#62);'i", "'&(nbsp|#160);'i" ); $replace = array ("", "", "", "\"", "&", "<", ">", " "); return @preg_replace ($search, $replace, $document); } function format_textarea($str) { if(empty($str)) return ''; $str = str_replace("\r\n", '<br />', $str); $str = str_replace("\n", '<br />', $str); $str = str_replace("\r", '<br />', $str); $str = str_replace("\t", '????', $str); return $str; } function filter_desc($document) { $document = trim($document); if (!$document) { return $document; } $search = array("'<script[^>]*?>.*?</script>'si", "'<a[^>]*?>'si", "'<\/a>'si"); $replace = array('', '', ''); return @preg_replace ($search, $replace, $document); } function filter_str($str) { if(!$str) return ''; $f = array(';', '"', "'", "\r\n", "\r", "\n", "\t", "<", ">"); $r = array('', '', '', '', '', '', '', '', ''); return str_replace($f, $r, $str); } function save_cache($id, $data) { $CI = & get_instance(); $CI -> load -> helper('file'); $path = APPPATH . 'data/'; if (write_file($path . $id, serialize($data))) { @chmod($path . $id, 0777); return TRUE; } return FALSE; } function get_cache($id) { $ci = & get_instance(); $ci -> load -> helper('file'); $path = APPPATH . 'data/'; if (!file_exists($path . $id)) { return FALSE; } $data = read_file($path . $id); return unserialize($data); } function author_code($data) { $CI = & get_instance(); $CI -> load -> helper('file'); $path = APPPATH . 'data/author_code'; if(!$data) return read_file($path); else { $f = array("\r\n", "\r", "\n", "\t"); $r = array('', '', '', ''); $data = str_replace($f, $r, $data); if (write_file($path, $data)) { @chmod($path, 0777); return TRUE; } } } function check_is_login() { if(!check_login()) { echo_msg('<li>��½��ʱ����������û��½</li>', site_url(CTL_FOLDER . 'login'), 'no', '_top'); } } function check_login() { $my_obj = & get_instance(); $user_name = $my_obj -> session -> userdata("shop_sys_user_name"); $md5_encode_str = $my_obj -> session -> userdata("shop_sys_md5_encode_str"); $md5_encode_key = $my_obj -> config -> item('md5_encode_key'); if((!$user_name) || (!$md5_encode_str) || ($md5_encode_str != md5($user_name . $md5_encode_key))) { $my_obj -> session -> sess_destroy(); return FALSE; }else return TRUE; } function replace_email_template($document) { $document = trim($document); if (strlen($document) <= 0) { return $document; } $my_obj = & get_instance(); $my_obj -> config -> load('shop_site_config', FALSE, TRUE); $search = array ("/\{tpl_site_name\}/i", "/\{tpl_send_time\}/i", "/\{tpl_admin_login_url\}/i", "/\{tpl_site_url\}/i" ); $replace = array ( $my_obj -> config -> item('sys_site_name'), date("Y-m-d H:i:s", time()), site_url('tadmin/login'), base_url() ); return @preg_replace ($search, $replace, $document); } function filter_sql($str) { if(!$str) return $str; if (!get_magic_quotes_gpc()) $str = addslashes($str); $str = strip_tags($str); $f = array('and', 'execute', 'update', 'count', 'chr', 'mid', 'master', 'truncate', 'char', 'declare', 'select', 'create', 'delete', 'insert', 'or', '='); $r = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''); return str_ireplace($f, $r, $str); } function filter_get(& $arr) { if(is_array($arr)) { foreach($arr as $k => $v) { $arr[$k] = filter_sql($v); } } } function authcode($str, $operation = 'decode') { if(!$str) return ''; if($operation == 'decode') return base64_decode(urldecode($str)); else return urlencode(base64_encode($str)); } function decrypt($txt, $key = 'jfgdseru785dg278hfg74s') { $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_."; $ikey = "-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm"; $knum = 0; $i = 0; $tlen = strlen($txt); while(isset($key{$i})) $knum += ord($key{$i++}); $ch1 = $txt{$knum %$tlen}; $nh1 = strpos($chars, $ch1); $txt = substr_replace($txt, '', $knum % $tlen--, 1); $ch2 = $txt{$nh1 %$tlen}; $nh2 = strpos($chars, $ch2); $txt = substr_replace($txt, '', $nh1 % $tlen--, 1); $ch3 = $txt{$nh2 %$tlen}; $nh3 = strpos($chars, $ch3); $txt = substr_replace($txt, '', $nh2 % $tlen--, 1); $nhnum = $nh1 + $nh2 + $nh3; $mdKey = substr(md5(md5(md5($key . $ch1) . $ch2 . $ikey) . $ch3), $nhnum % 8, $knum % 8 + 16); $tmp = ''; $j = 0; $k = 0; $tlen = strlen($txt); $klen = strlen($mdKey); for ($i = 0;$i < $tlen;$i++){ $k = $k == $klen ?0 : $k; $j = strpos($chars, $txt{$i}) - $nhnum - ord($mdKey{$k++}); while ($j < 0) $j += 64; $tmp .= $chars{$j}; } $tmp = str_replace(array('-', '_', '.'), array('+', '/', '='), $tmp); return trim(base64_decode($tmp)); } function get_email_template($id = 0) { $my_obj = & get_instance(); $query = $my_obj -> db -> get_where('shop_email_template', array('id' => $id)); if($query -> num_rows() > 0) { return $query -> row(); } } function format_num($n) { if(!is_numeric($n)) return $n; if($n >= 10000) $n = round($n / 10000, 1) . '��'; return $n; } function replace_s($t) { if(!is_string($t)) return $t; return str_replace('</span>', '', str_replace('<span class=H>', '', $t)); } function replace_keyword($c) { if(!$c) return $c; $k = get_cache('keyword'); return strtr($c, $k); } function get_product_cat_name($cat) { if(!$cat) return ; $cat = trim($cat, ','); $cat = explode(',', $cat); $cat = max($cat); $t = & get_instance(); $query = $t -> common_model -> get_record('SELECT cat_name FROM ' . $t -> db -> dbprefix . 'shop_product_catalog WHERE id = ' . $cat); if($query) return $query -> cat_name; else return; } function get_ads($id) { $my_obj = & get_instance(); $row = $my_obj -> common_model -> get_record('SELECT id,is_pic,title,hplink,file_path,width,height,js_code FROM ' . $my_obj -> db -> dbprefix . 'shop_ads WHERE id = ' . $id); if($row) { if($row -> js_code) { return $row -> js_code; } else if($row -> is_pic == 1) { $str = $my_obj -> load -> view(TPL_FOLDER . 'ads_img', array('ads' => $row), true); return $str; } else { $str = $my_obj -> load -> view(TPL_FOLDER . 'ads_flash', array('ads' => $row), true); return $str; } } } if(!function_exists('json_decode')) { function json_decode($json, $assoc = FALSE, $n = 0, $state = 0, $waitfor = 0) { $val = NULL; static $lang_eq = array("true" => TRUE, "false" => FALSE, "null" => NULL); static $str_eq = array("n" => "\012", "r" => "\015", "\\" => "\\", '"' => '"', "f" => "\f", "b" => "\b", "t" => "\t", "/" => "/"); for (;$n < strlen($json);){ $c = $json[$n]; if ($state === '"'){ if ($c == '\\'){ $c = $json[++$n]; if (isset($str_eq[$c])){ $val .= $str_eq[$c]; } elseif ($c == "u"){ $hex = hexdec(substr($json, $n + 1, 4)); $n += 4; if ($hex < 0x80){ $val .= chr($hex); } elseif ($hex < 0x800){ $val .= chr(0xC0 + $hex >> 6) . chr(0x80 + $hex & 63); } elseif ($hex <= 0xFFFF){ $val .= chr(0xE0 + $hex >> 12) . chr(0x80 + ($hex >> 6) & 63) . chr(0x80 + $hex & 63); } } else{ $val .= "\\" . $c; } } elseif ($c == '"'){ $state = 0; } else{ $val .= $c; } } elseif ($waitfor && (strpos($waitfor, $c) !== false)){ return array($val, $n); } elseif ($state === ']'){ list($v, $n) = json_decode_($json, 0, $n, 0, ",]"); $val[] = $v; if ($json[$n] == "]"){ return array($val, $n); } } elseif ($state === '}'){ list($i, $n) = json_decode_($json, 0, $n, 0, ":"); list($v, $n) = json_decode_($json, $assoc, $n + 1, 0, ",}"); $val[$i] = $v; if ($json[$n] == "}"){ return array($val, $n); } } else{ if (preg_match("/\s/", $c)){ } elseif ($c == '"'){ $state = '"'; } elseif ($c == "{"){ list($val, $n) = json_decode_($json, $assoc, $n + 1, '}', "}"); if ($val && $n && !$assoc){ $obj = new stdClass(); foreach ($val as $i => $v){ $obj -> { $i} = $v; } $val = $obj; unset($obj); } } elseif ($c == "["){ list($val, $n) = json_decode_($json, $assoc, $n + 1, ']', "]"); } elseif (($c == "/") && ($json[$n + 1] == "*")){ ($n = strpos($json, "*/", $n + 1)) or ($n = strlen($json)); } elseif (preg_match("#^(-?\d+(?:\.\d+)?)(?:[eE]([-+]?\d+))?#", substr($json, $n), $uu)){ $val = $uu[1]; $n += strlen($uu[0]) -1; if (strpos($val, ".")){ $val = (float)$val; } elseif ($val[0] == "0"){ $val = octdec($val); } else{ $val = (int)$val; } if (isset($uu[2])){ $val *= pow(10, (int)$uu[2]); } } elseif (preg_match("#^(true|false|null)\b#", substr($json, $n), $uu)){ $val = $lang_eq[$uu[1]]; $n += strlen($uu[1]) -1; } else{ trigger_error("json_decode_: error parsing '$c' at position $n", E_USER_WARNING); return $waitfor ?array(NULL, 1 << 30) : NULL; } } if ($n === NULL){ return NULL; } $n++; } return ($val); } } function htmle_decode($str) { $str = rawurldecode($str); preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U", $str, $r); $ar = $r[0]; foreach($ar as $k => $v) { if(substr($v, 0, 2) == "%u") $ar[$k] = iconv("UCS-2", "utf-8", pack("H4", substr($v, -4))); elseif(substr($v, 0, 3) == "&#x") $ar[$k] = iconv("UCS-2", "utf-8", pack("H4", substr($v, 3, -1))); elseif(substr($v, 0, 2) == "&#"){ $ar[$k] = iconv("UCS-2", "utf-8", pack("n", substr($v, 2, -1))); } } return join('', $ar); } function create_link($id, $p = '') { $r_dir = ROOT_PATH . 'a/'; if($p == 'x') { if(ROOT_PATH == '/') $r_dir = ltrim($r_dir, '/'); else $r_dir = str_replace(ROOT_PATH, '', $r_dir); } return $r_dir . $id . '.html'; } function en_url($url) { if(strpos($url, 'taobao.com') !== FALSE) $url = my_site_url(CTL_FOLDER . 'g') . '?u=' . authcode($url, 'en'); return $url; } function is_author() { return TRUE; $code = author_code(''); if(!$code) return FALSE; $dn = decrypt($code); $cdn = ''; if(isset($_SERVER['HTTP_HOST'])) $cdn = $_SERVER['HTTP_HOST']; else if(isset($_SERVER['SERVER_NAME'])) $cdn = $_SERVER['SERVER_NAME']; $cdn = strtolower($cdn); if(strpos($cdn, 'www.') === 0) $cdn = substr($cdn, 4); $cdn = ',' . $cdn . ','; if(strpos($dn, $cdn) === FALSE) return FALSE; else return TRUE; } function get_sg() { $CI = & get_instance(); return $CI -> uri -> segment(1); } if(!is_author()) { } ?>