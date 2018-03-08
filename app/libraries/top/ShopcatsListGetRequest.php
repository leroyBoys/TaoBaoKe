<?php
/**.
 * @link		http://stdesign.taobao.com
 */
class ShopcatsListGetRequest
{
	/** 
	 * 需要返回的字段列表，见ShopCat，默认返回：cid,parent_cid,name,is_parent
	 **/
	private $fields;
	private $apiParas = array();
	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields()
	{
		return $this->fields;
	}
	public function getApiMethodName()
	{
		return "taobao.shopcats.list.get";
	}
	public function getApiParas()
	{
		return $this->apiParas;
	}
	public function check()
	{
	}
}
