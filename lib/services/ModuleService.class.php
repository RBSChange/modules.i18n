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
		$root = new i18n_folder();
		
		$paths = LocaleService::getInstance()->getAllPathKey();
		foreach ($paths as $path => $nbKey) 
		{
			$pnode = $root;
			foreach (explode('.', $path) as $folderName) 
			{
				$pnode = $pnode->addFolderName($folderName);
			}
		}
		$folders =  $root->getPaths();
		return array('count' => count($folders), 'packages' => count($paths));
	}
	
	private function addFolder(&$folderArray, $name)
	{
		if (!isset($folderArray[$name]))
		{
			$folderArray[$name] = array();
		}
	}
}

class i18n_folder 
{
	private $children = array();
	
	public function addFolderName($name)
	{
		if (!isset($this->children[$name]))
		{
			$this->children[$name] = new i18n_folder();
		}
		return $this->children[$name];
	}
	
	public function getPaths()
	{
		if (count($this->children) > 0)
		{
			$result = array();
			foreach ($this->children as $name => $child) 
			{
				$res = $child->getPaths();
				if (is_array($res))
				{
					$result[] = $name;
					foreach ($res as $n) 
					{
						$result[] = $name . '.' . $n;
					}
				}
			}
			return $result;
		}
		return null;
	}
}