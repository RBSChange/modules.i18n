<?php
/**
 * i18n_ClearCachesAction
 * @package modules.i18n.actions
 */
class i18n_ClearCachesAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$cs = CacheService::getInstance();
		$cs->clearLocalizedCache();
		$cs->boShouldBeReloaded();		
		
		return $this->sendJSON(array('cacheCleared' => true));
	}
}