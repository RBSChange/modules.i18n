<?php
/**
 * i18n_patch_0350
 * @package modules.i18n
 */
class i18n_patch_0350 extends patch_BasePatch
{
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->execChangeCommand("update-autoload", array('modules/i18n'));
		$this->execChangeCommand("compile-locales", array('i18n'));
	}

	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'i18n';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0350';
	}
}