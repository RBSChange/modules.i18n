<?php
class i18n_ListLcidsService extends change_BaseService implements list_ListItemsService
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
			self::$instance = new self();
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