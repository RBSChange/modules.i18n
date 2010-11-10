<?php
/**
 * commands_i18n_FlushUserEdited
 * @package modules.i18n.command
 */
class commands_i18n_FlushUserEdited extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 * @example "<moduleName> <name>"
	 */
	function getUsage()
	{
		return "";
	}

	/**
	 * @return String
	 * @example "initialize a document"
	 */
	function getDescription()
	{
		return "Flush User edited locales to override project path";
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
		$this->message("== Flush User edited locales ==");
		$this->loadFramework();
		$ls = LocaleService::getInstance();		
		$this->warnMessage("Not implemented...");
		$this->quitOk("Command successfully executed");
	}
}