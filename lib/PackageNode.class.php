<?php
class i18n_PackageNode 
{
	/**
	 * @var array
	 */
	private $children = array();
	
	/**
	 * @var integer
	 */
	private $folderId = null;
	
	/**
	 * @var integer
	 */
	private $nbKeys = 0;
	
	/**
	 * @var string
	 */
	private $path = null;
	
	/**
	 * @param string $name
	 * @return i18n_PackageNode
	 */
	public function addFolderName($name)
	{
		if (!isset($this->children[$name]))
		{
			$this->children[$name] = new i18n_PackageNode();
		}
		return $this->children[$name];
	}
	
	public function isFolder()
	{
		return count($this->children) > 0;
	}
	
	public function setPackageInfo($path, $nbKeys)
	{		
		$this->path = $path;
		$this->nbKeys = $nbKeys;
	}
	
	public function isPackage()
	{
		return $this->nbKeys > 0;
	}
	
	/**
	 * @param generic_persistentdocument_folder $currentFolder
	 * @return integer
	 */
	public function buildFolders($currentFolder)
	{
		$this->folderId = $currentFolder->getId();
		$result = 1;
		foreach ($this->children as $name => $node) 
		{
			if (!$node->isFolder()) 
			{
				$node->folderId = $this->folderId;
				continue;
			}		
			$folderDoc = i18n_ModuleService::getInstance()->getChildFolderDocumentByLabel($this->folderId, $name);
			$folderDoc->save($this->folderId);
			$result += $node->buildFolders($folderDoc);
		}
		return $result;
	}
	
	public function buildPackages()
	{
		if ($this->nbKeys > 0)
		{
			$pakage = i18n_ModuleService::getInstance()->getPackageByLabel($this->path);
			$pakage->setNbkeys($this->nbKeys);
			if ($pakage->isNew())
			{
				Framework::info(__METHOD__ . " Add " . $this->path . " : " . $this->nbKeys);
			}
			$pakage->save($this->folderId);
			if ($pakage->getTreeId() == null)
			{
				TreeService::getInstance()->newLastChild($this->folderId, $pakage->getId());
			}
		}
		foreach ($this->children as $name => $node) 
		{
			$node->buildPackages();
		}
	}
}