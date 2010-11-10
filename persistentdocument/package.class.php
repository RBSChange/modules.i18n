<?php
/**
 * Class where to put your custom methods for document i18n_persistentdocument_package
 * @package modules.i18n.persistentdocument
 */
class i18n_persistentdocument_package extends i18n_persistentdocument_packagebase 
{
	private $idNodes = null;
	
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
		if (in_array('lcids', $propertiesNames))
		{
			$formProperties['lcids'] = LocaleService::getInstance()->getLCID(RequestContext::getInstance()->getLang());
			$formProperties['ids'] = $this->getIdsForBo();
		}
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
	
	private function getIdsForBo()
	{
		$result = array();
		$this->populate();
		$count = count($this->idNodes);
		for ($i = 0; $i < $count; $i++) 
		{
			$node = $this->idNodes[$i];
			$info = array('id' => $node->getId(), 'key' => $this->getLabel() . '.' . $node->getId());
			foreach ($node->getLcidArray() as $lcid) 
			{
				$info['langs'][$lcid] = array('text' => $node->getTextByLcid($lcid), 'useredited' => $node->getUserEditByLcid($lcid));
			}
			$result[] = $info;
		}
		return $result;
	}
	
	private function getDefinedLcids()
	{
		$lcids = array();
		$this->populate();
		foreach ($this->idNodes as $node) 
		{
			$lcids = array_merge($lcids, $node->getLcidArray());
		}
		return implode(',', $lcids);
	}
}