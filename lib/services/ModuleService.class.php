<?php
/**
 * @package modules.i18n.lib.services
 */
class i18n_ModuleService extends ModuleBaseService
{
	/**
	 * Singleton
	 * @var i18n_ModuleService
	 */
	private static $instance = null;

	/**
	 * @return i18n_ModuleService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @param Integer $documentId
	 * @return f_persistentdocument_PersistentTreeNode
	 */
//	public function getParentNodeForPermissions($documentId)
//	{
//		// Define this method to handle permissions on a virtual tree node. Example available in list module.
//	}

	/**
	 * 
	 * @param generic_persistentdocument_folder $folder
	 */
	public function refreshPackage($folder)
	{
		$root = new i18n_PackageNode();
		$paths = LocaleService::getInstance()->getPackageNames();
		foreach ($paths as $path => $nbKeys) 
		{
			$pnode = $root;
			foreach (explode('.', $path) as $folderName) 
			{
				$pnode = $pnode->addFolderName($folderName);
			}
			$pnode->setPackageInfo($path, $nbKeys);
		}
		$nbFolder = $root->buildFolders($folder);
		
		$nbPackages = $root->buildPackages($folder);
		
		return array('count' => $nbFolder);
	}

	/**
	 * @param integer $parentId
	 * @param string $label
	 * @return generic_persistentdocument_folder
	 */
	public function getChildFolderDocumentByLabel($parentId, $label)
	{
		$folder = generic_FolderService::getInstance()->createQuery()
			->add(Restrictions::childOf($parentId))
			->add(Restrictions::eq('label', $label))
			->findUnique();
		if ($folder === null)
		{
			$folder = generic_FolderService::getInstance()->getNewDocumentInstance();
			$folder->setLabel($label);
		}
		return $folder;
	}
	
	/**
	 * @param string $packageName
	 * @return i18n_persistentdocument_package
	 */
	public function getPackageByLabel($packageName)
	{
		$package = i18n_PackageService::getInstance()->createQuery()
			->add(Restrictions::eq('label', $packageName))
			->findUnique();
		if ($package === null)
		{
			$package = i18n_PackageService::getInstance()->getNewDocumentInstance();
			$package->setLabel($packageName);
		}
		return $package;	
	}
	
	public function getLcidLabels()
	{
		$items = array();
		$rc = RequestContext::getInstance();
		$ls = LocaleService::getInstance();
		$uiLang = $rc->getUILang();
		foreach ($rc->getSupportedLanguages() as $lang) 
		{
			$lcid = $ls->getLCID($lang);
			$lk = 'm.i18n.bo.langs.' . strtolower($lcid); 
			$label = $ls->getFullKeyContent($uiLang, $lk);
			$items[$lcid] = $label === null ? $lk : $label;
		}
		return $items;
	}
}