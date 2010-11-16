<?php
/**
 * commands_i18n_Convert
 * @package modules.i18n.command
 */
class commands_i18n_Convert extends commands_AbstractChangeCommand
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
		return "Convert old locale folder format";
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
//	protected function validateArgs($params, $options)
//	{
//	}

	function getOptions()
	{
		return array("--rlf", "-cif");
	}

	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
	 */
	function getParameters($completeParamCount, $params, $options, $current)
	{
		$components = array();
		if (is_dir("framework/locale"))
		{
			$components[] = "framework";
		}
		foreach (glob("modules/*/locale", GLOB_ONLYDIR) as $path)
		{
			$module = dirname($path);
			$components[] = "modules/" . basename($module);
		}
		foreach (glob("themes/*/locale", GLOB_ONLYDIR) as $path)
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
		$this->message("== Convert old locale folder ==");
		$this->loadFramework();
		$ls = LocaleService::getInstance();
		$removeLocalFolder = isset($options['rlf']);
		$clearI18nFolder = isset($options['cif']);
		if (count($params) === 0)
		{
			$ls->convertLocales(null, $removeLocalFolder, $clearI18nFolder);
		}
		else
		{
			foreach ($params as $value) 
			{
				if ($value === 'framework' || strpos($value, 'themes/') === 0 || strpos($value, 'modules/') === 0)
				{
					$ls->convertLocales($value, $removeLocalFolder, $clearI18nFolder);
				}
			}
		}
	
		$this->quitOk("Command successfully executed");
	}
}