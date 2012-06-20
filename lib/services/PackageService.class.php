<?php
/**
 * @package modules.i18n
 * @method i18n_PackageService getInstance()
 */
class i18n_PackageService extends f_persistentdocument_DocumentService
{
	/**
	 * @return i18n_persistentdocument_package
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_i18n/package');
	}

	/**
	 * Create a query based on 'modules_i18n/package' model.
	 * Return document that are instance of modules_i18n/package,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_i18n/package');
	}
	
	/**
	 * Create a query based on 'modules_i18n/package' model.
	 * Only documents that are strictly instance of modules_i18n/package
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_i18n/package', false);
	}
		
	/**
	 * @param i18n_persistentdocument_package $document
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		$datas = $document->getModifiedKeysArray();
		if (count($datas) > 0)
		{
			$baseKey = 	$document->getLabel();	
			$ls = LocaleService::getInstance();
			foreach ($datas as $keyInfo) 
			{
				$ls->updateUserEditedKey($keyInfo['lcid'], $keyInfo['id'], $baseKey, $keyInfo['content'], $keyInfo['format']);	
			}
		}
	}
	
	/**
	 * @param i18n_persistentdocument_package $document
	 * @param string $actionType
	 * @param array $formProperties
	 */
	public function addFormProperties($document, $propertiesNames, &$formProperties)
	{
		if (in_array('loadpaginedkeys', $propertiesNames))
		{
			$ls = LocaleService::getInstance();
			$idNodes = $document->getIdNodes();
			$count = count($idNodes);
			$formProperties['nbids'] = $count;
			$pageSize = $document->getPageSize();
			$index = $document->getPageindex() * $pageSize;
			if ($index >= $count)
			{
				$index = 0;
				$document->setPageIndex(0);
			}
			$formProperties['pagesize'] = $pageSize;
			$formProperties['pageindex'] = $document->getPageindex();
			
			$formProperties['ids'] = array();
			for ($i = 0; $i < $pageSize; $i++) 
			{
				if (!isset($idNodes[$index + $i]))
				{
					break;
				}
				$node = $idNodes[$index + $i];
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
	 * @param indexer_IndexedDocument $indexedDocument
	 * @param i18n_persistentdocument_package $document
	 * @param indexer_IndexService $indexService
	 */
	protected function updateIndexDocument($indexedDocument, $document, $indexService)
	{
		$ls = LocaleService::getInstance();
		$lcid = $ls->getLCID(RequestContext::getInstance()->getLang());
		foreach ($document->getIdNodes() as $node) 
		{
			$val = $node->getTextByLcid($lcid);
			$indexedDocument->addAggregateText($val);
		}
	}
}