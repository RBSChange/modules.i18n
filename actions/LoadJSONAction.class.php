<?php
class i18n_LoadJSONAction  extends generic_LoadJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$document = $this->getDocumentInstanceFromRequest($request);
		$document->setPageindex($request->getParameter('pageindex', 0));
		return parent::_execute($context, $request);
	}
}