<?php
/**
 * i18n_RefreshpackageAction
 * @package modules.i18n.actions
 */
class i18n_RefreshpackageAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$folder = $this->getDocumentInstanceFromRequest($request);
		$result = i18n_ModuleService::getInstance()->refreshPackage($folder);
		return $this->sendJSON($result);
	}
}