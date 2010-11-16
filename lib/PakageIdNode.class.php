<?php
class i18n_PakageIdNode 
{
	/**
	 * @var string
	 */
	private $id = null;
	
	/**
	 * @var array
	 */
	private $lcidDatas = null;
	
	public function __construct($id , $data)
	{
		$this->id = $id;
		$this->lcidDatas = $data;
	}
		
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * @return integer
	 */
	public function getLcidCount()
	{
		return (is_array($this->lcidDatas)) ? count($this->lcidDatas) : 0;
	}
	
	/**
	 * @return string[]
	 */
	public function getLcidArray()
	{
		return (is_array($this->lcidDatas)) ? array_keys($this->lcidDatas) : array();
	}	

	/**
	 * @return string
	 */
	public function getTextByLcid($lcid)
	{
		return (is_array($this->lcidDatas) && isset($this->lcidDatas[$lcid])) ? $this->lcidDatas[$lcid]['content'] : null;
	}
	
	/**
	 * @return boolean
	 */
	public function getUserEditByLcid($lcid)
	{
		return (is_array($this->lcidDatas) && isset($this->lcidDatas[$lcid])) ? $this->lcidDatas[$lcid]['useredited'] : false;
	}
	
	/**
	 * 
	 * @param string [TEXT] | HTML
	 */
	public function getFormatByLcid($lcid)
	{
		return (is_array($this->lcidDatas) && isset($this->lcidDatas[$lcid])) ? $this->lcidDatas[$lcid]['format'] : 'TEXT';
	}
	
	/**
	 * @return array<$lcid => array<text => string, useredited => boolean, format => string>>
	 */
	public function toBoArray()
	{
		$info = array('id' => $this->getId(), 'langs' => array());
		if (is_array($this->lcidDatas))
		{
			foreach ($this->lcidDatas as $lcid => $data) 
			{
				$info['langs'][$lcid] = array('text' => $data['content'], 'useredited' => $data['useredited'], 'format' => $data['format']);
			}
		}
		return $info;
	}
}