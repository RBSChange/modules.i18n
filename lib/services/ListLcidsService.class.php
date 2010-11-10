<?php
class i18n_ListLcidsService extends BaseService implements list_ListItemsService
{
	/**
	 * @var i18n_ListLcidsService
	 */
	private static $instance;
	

	/**
	 * @return i18n_ListLcidsService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	

	/**
	 * @return array
	 */
	public function getItems()
	{
		$items = array();
		foreach (i18n_ModuleService::getInstance()->getLcidLabels() as $lcid => $lcid) 
		{
			$items[] = new list_Item($lcid, $lcid);
		}
		return $items;
	}

}