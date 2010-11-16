<?php
/**
 * i18n_CheckModuleInitAction
 * @package modules.i18n.actions
 */
class i18n_CheckModuleInitAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$folder = $this->getDocumentInstanceFromRequest($request);
		$node = TreeService::getInstance()->getInstanceByDocument($folder);
		$result = array('nbpackage' => ($node) ? $node->getChildCount() : 0);
		return $this->sendJSON($result);
	}
}