<?php
/**
 * i18n_PackageScriptDocumentElement
 * @package modules.i18n.persistentdocument.import
 */
class i18n_PackageScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return i18n_persistentdocument_package
	 */
	protected function initPersistentDocument()
	{
		return i18n_PackageService::getInstance()->getNewDocumentInstance();
	}
	
	/**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_i18n/package');
	}
}