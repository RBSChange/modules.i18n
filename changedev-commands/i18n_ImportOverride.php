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
		$components = array();
		if (is_dir("override/framework/i18n"))
		{
			$components[] = "framework";
		}
		foreach (glob("override/modules/*/i18n", GLOB_ONLYDIR) as $path)
		{
			$module = dirname($path);
			$components[] = "modules/" . basename($module);
		}
		foreach (glob("override/themes/*/i18n", GLOB_ONLYDIR) as $path)
		{
			$module = dirname($path);
			$components[] = "themes/" . basename($module);
		}		
		
		return array_diff($components, $params);
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
		if (count($params) === 0)
		{
			$ls->importOverride();
		}
		else
		{
			foreach ($params as $value) 
			{
				if ($value === 'framework' || strpos($value, 'themes/') === 0 || strpos($value, 'modules/') === 0)
				{
					$ls->importOverride($value);
				}
			}
		}
		$this->quitOk("Command successfully executed");
	}
}