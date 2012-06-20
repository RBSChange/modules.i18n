<?php
class i18n_LoadJSONAction  extends generic_LoadJSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$document = $this->getDocumentInstanceFromRequest($request);
		if ($document instanceof i18n_persistentdocument_package)
		{
			$document->setPageindex($request->getParameter('pageindex', 0));
		}
		return parent::_execute($context, $request);
	}
}