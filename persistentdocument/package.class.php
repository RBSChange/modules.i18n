<?php
/**
 * Class where to put your custom methods for document i18n_persistentdocument_package
 * @package modules.i18n.persistentdocument
 */
class i18n_persistentdocument_package extends i18n_persistentdocument_packagebase 
{
	
	/**
	 * @var array
	 */
	private $idNodes = null;
		
	/**
	 * @var integer
	 */
	private $pageSize = 20;		
	
	
	/**
	 * @var integer
	 */
	private $pageIndex = 0;		
	
	
	/**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
//	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
//	{
//	}
	
	/**
	 * @param string $actionType
	 * @param array $formProperties
	 */
	public function addFormProperties($propertiesNames, &$formProperties)
	{
		if (in_array('loadpaginedkeys', $propertiesNames))
		{
			$ls = LocaleService::getInstance();
			$this->populate();
			$count = count($this->idNodes);
			$formProperties['nbids'] = $count;
			$pageSize = $this->getPageSize();
			$index = $this->getPageindex() * $pageSize;
			if ($index >= $count)
			{
				$index = 0;
				$this->setPageIndex(0);
			}
			$formProperties['pagesize'] = $pageSize;
			$formProperties['pageindex'] = $this->getPageindex();
			
			$formProperties['ids'] = array();
			for ($i = 0; $i < $pageSize; $i++) 
			{
				if (!isset($this->idNodes[$index + $i]))
				{
					break;
				}
				$node = $this->idNodes[$index + $i];
				$formProperties['ids'][] = $node->toBoArray();
			}
		}
		
		if (in_array('lcids', $propertiesNames))
		{
			$formProperties['lcids'] = i18n_ModuleService::getInstance()->getLcidLabels();
			$formProperties['tolang'] = $ls->getLCID(RequestContext::getInstance()->getLang());
		}
	}	
	
	/**
	 * @return integer the $pageSize
	 */
	public function getPageSize()
	{
		return $this->pageSize;
	}

	/**
	 * @return integer the $pageIndex
	 */
	public function getPageindex()
	{
		return $this->pageIndex;
	}

	/**
	 * @param integer $pageIndex
	 */
	public function setPageindex($pageIndex)
	{
		$this->pageIndex = $pageIndex;
	}
	
	private function populate()
	{
		if ($this->idNodes === null)
		{
			$this->idNodes = array();
			$data = LocaleService::getInstance()->getPackageContent($this->getLabel());
			foreach ($data as $id => $infos) 
			{
				$node = new i18n_PakageIdNode($id, $infos);
				$this->idNodes[] = $node;
			}
		}
	}
	
	private $modifiedKeysArray = null;
	
	public function setUpdatedkeys($jsonString)
	{
		$data = JsonService::getInstance()->decode($jsonString);
		if (count($data))
		{
			$this->setModificationdate(null);
			$this->modifiedKeysArray = $data;
		}
	}
	
	/**
	 * @return array<array<id => string, content => string, lcid = string, format => string>>
	 */
	public function getModifiedKeysArray()
	{
		return $this->modifiedKeysArray;
	}
}