<?php
/**
 * @package modules.i18n
 * @method i18n_ModuleService getInstance()
 */
class i18n_ModuleService extends ModuleBaseService
{
	/**
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
		
		return array('nbPackages' => $nbPackages, 'nbFolders' => $nbFolder);
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
	
	/**
	 * @return string[]
	 */
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
	
	/**
	 * @param generic_persistentdocument_folder $folder
	 * @return string
	 */
	public function getPackageNameByFolder($folder)
	{
		if ($folder instanceof generic_persistentdocument_folder && !($folder instanceof generic_persistentdocument_rootfolder)) 
		{
			$ancestors = generic_FolderService::getInstance()->getAncestorsOf($folder, $folder->getDocumentModelName());
			$ancestors[] = $folder;
			$parts = array();
			foreach ($ancestors as $folder) 
			{
				$parts[] = $folder->getLabel();
			}
			return implode('.', $parts);	
		}
		return '';
	}
	
	/**
	 * @param c_Package $pk
	 * @param string $LCID
	 * @param string $refLCID
	 * @param boolean $delete
	 * @return true
	 */
	public function addMissingKeys($pk, $LCID, $refLCID, $delete)
	{
		$basePath = f_util_FileUtils::buildPath($pk->getPath(), 'i18n');
		if (is_dir($basePath))
		{
			if ($pk->isFramework())
			{
				$baseKey = 'f';
			}
			elseif ($pk->isModule())
			{
				$baseKey = 'm.' . $pk->getName();
			}
			elseif ($pk->isTheme())
			{
				$baseKey = 't.' . $pk->getName();
			}
			else
			{
				return 'Invalid package: ' . $pk->getKey();
			}
			
			$this->addMissingKeysForDir($basePath, $baseKey, $LCID, $refLCID, $delete);
		}
		return true;
	}
	
	private function addMissingKeysForDir($basePath, $baseKey, $LCID, $refLCID, $delete)
	{
		$fixed = false;
		$toDelete = false;
		foreach (scandir($basePath) as $entry)
		{
			if ($entry[0] === '.') {continue;}
			$path = f_util_FileUtils::buildPath($basePath, $entry);
			
			if (is_dir($path))
			{
				$this->addMissingKeysForDir($path, $baseKey . '.' . $entry, $LCID, $refLCID, $delete);
			}
			
			if ($entry === $refLCID . '.xml')
			{
				$this->addMissingKeysForFile($path, $baseKey, $LCID, $delete);
				$fixed = true;
				$toDelete = false;
			}
			elseif ($delete && !$fixed && $entry === $LCID . '.xml')
			{
				$toDelete = $path;
			}
		}
		
		if ($toDelete)
		{
			f_util_FileUtils::unlink($toDelete);
		}
	}
	
	private function addMissingKeysForFile($path, $baseKey, $LCID, $delete)
	{
		$src = f_util_DOMUtils::fromPath($path);
		$destPath = f_util_FileUtils::buildPath(dirname($path), $LCID . '.xml');
		if (!is_readable($destPath))
		{
			$dest = f_util_DOMUtils::fromString('<?xml version="1.0" encoding="utf-8"?><i18n />');
		}
		else
		{
			$dest = f_util_DOMUtils::fromPath($destPath);
		}
		
		$destKeys = $dest->documentElement;		
		$destKeys->setAttribute('lcid', $LCID);
		$destKeys->setAttribute('baseKey', $baseKey);
		
		if ($delete)
		{
			$dn = array();
			foreach ($dest->getElementsByTagName('key') as $key)
			{
				/* @var $key DOMElement */
				$id = $key->getAttribute('id');
				if ($src->findUnique("//*[@id='$id']") === null)
				{
					$dn[] = $key;
				}
			}
			foreach ($dn as $key) {$key->parentNode->removeChild($key);}
		}
		
		
		$dn = array();
		foreach ($dest->getElementsByTagName('include') as $include)
		{
			$dn[] = $include;
		}
		foreach ($dn as $include) {$include->parentNode->removeChild($include);}		
		
		foreach ($src->getElementsByTagName('include') as $include)
		{
			/* @var $include DOMElement */
			if ($destKeys->hasChildNodes())
			{
				$destKeys->insertBefore($dest->importNode($include, false), $destKeys->firstChild);
			}
			else
			{
				$destKeys->appendChild($dest->importNode($include, false));
			}
		}
		
		foreach ($src->getElementsByTagName('key') as $key)
		{
			/* @var $key DOMElement */
			$id = $key->getAttribute('id');
			if ($dest->findUnique("//*[@id='$id']") === null)
			{
				$destKeys->appendChild($dest->importNode($key, false)); 
			}
		}
		
		echo $destPath, PHP_EOL;
		$dest->save($destPath);
	}
}