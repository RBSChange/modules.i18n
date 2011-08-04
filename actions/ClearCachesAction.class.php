<?php
/**
 * i18n_ClearCachesAction
 * @package modules.i18n.actions
 */
class i18n_ClearCachesAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$cs = CacheService::getInstance();
		$cs->clearLocalizedCache();
		$cs->boShouldBeReloaded();		
		
		return $this->sendJSON(array('cacheCleared' => true));
	}
}