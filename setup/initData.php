<?php
/**
 * @package modules.i18n.setup
 */
class i18n_Setup extends object_InitDataSetup
{
	public function install()
	{
		$this->executeModuleScript('init.xml');
	}
}