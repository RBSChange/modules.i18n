<?php
/**
 * commands_i18n_ImportOverride
 * @package modules.i18n.command
 */
class commands_i18n_ImportOverride extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 * @example "<moduleName> <name>"
	 */
	function getUsage()
	{
		return "<moduleName>";
	}

	/**
	 * @return String
	 * @example "initialize a document"
	 */
	function getDescription()
	{
		return "Import override i18n folder in standard module";
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
//	protected function validateArgs($params, $options)
//	{
//	}

	/**
	 * @return String[]
	 */
//	function getOptions()
//	{
//	}

	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
	 */
	function getParameters($completeParamCount, $params, $options, $current)
	{
		return null;
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Import override i18n folder ==");
		$this->loadFramework();
		$ls = LocaleService::getInstance();
		$this->warnMessage("Not implemented...");		
		$this->quitOk("Command successfully executed");
	}
}