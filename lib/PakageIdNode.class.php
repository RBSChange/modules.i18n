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

	public function getLcidCount()
	{
		return (is_array($this->lcidDatas)) ? count($this->lcidDatas) : 0;
	}
	
	public function getLcidArray()
	{
		return (is_array($this->lcidDatas)) ? array_keys($this->lcidDatas): array();
	}	
	
	public function getTextByLcid($lcid)
	{
		return (is_array($this->lcidDatas) && isset($this->lcidDatas[$lcid])) ? $this->lcidDatas[$lcid]['content'] : null;
	}
	
	public function getUserEditByLcid($lcid)
	{
		return (is_array($this->lcidDatas) && isset($this->lcidDatas[$lcid])) ? $this->lcidDatas[$lcid]['useredited'] : false;
	}
	
	
}