<?php
/**
 * @package modules.i18n
 * @method i18n_ListLcidsService getInstance()
 */
class i18n_ListLcidsService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @return list_Item[]
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