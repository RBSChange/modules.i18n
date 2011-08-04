<?php
/**
 * i18n_CheckModuleInitAction
 * @package modules.i18n.actions
 */
class i18n_CheckModuleInitAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$folder = $this->getDocumentInstanceFromRequest($request);
		$node = TreeService::getInstance()->getInstanceByDocument($folder);
		$result = array('nbpackage' => ($node) ? $node->getChildCount() : 0);
		return $this->sendJSON($result);
	}
}