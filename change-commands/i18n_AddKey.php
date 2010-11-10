<?php
/**
 * commands_i18n_AddKey
 * @package modules.i18n.command
 */
class commands_i18n_AddKey extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 * @example " "
	 */
	function getUsage()
	{
		return "<keypath> <lang> <text>";
	}

	/**
	 * @return String
	 * @example "initialize a document"
	 */
	function getDescription()
	{
		return "Add new locale key";
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
		$this->message("== Add new locale locales ==");
		$this->loadFramework();
		$ls = LocaleService::getInstance();
		$this->warnMessage("Not implemented...");		

		$this->quitOk("Command successfully executed");
	}
}