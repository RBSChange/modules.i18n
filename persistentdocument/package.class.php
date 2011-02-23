<?php
/**
 * Class where to put your custom methods for document i18n_persistentdocument_package
 * @package modules.i18n.persistentdocument
 */
class i18n_persistentdocument_package extends i18n_persistentdocument_packagebase 
{
	/**
	 * @return indexer_IndexedDocument
	 */
	public function getBackofficeIndexedDocument()
	{
		$indexedDoc = parent::getBackofficeIndexedDocument();
		
		$textArray = array($this->getLabel());
		
		$ls = LocaleService::getInstance();
		$this->populate();
		$lcid = $ls->getLCID(RequestContext::getInstance()->getLang());
		foreach ($this->idNodes as $node) 
		{
			$textArray[] = $node->getTextByLcid($lcid);
		}
		
		$indexedDoc->setText(implode(' ', $textArray));
		
		return $indexedDoc;
	}
	
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
	
	/**
	 * @var array
	 */
	public function getIdNodes()
	{
		$this->populate();
		return $this->idNodes;
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