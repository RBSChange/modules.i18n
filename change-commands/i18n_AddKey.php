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
		$this->message("== Add new locale ==");
		$this->loadFramework();
		$ls = LocaleService::getInstance();
		$key = strtolower($params[0]);
		list($baseKey, $id) = $ls->explodeKey( $params[0]);	
		if ($baseKey === false)
		{
			return $this->quitError('Invalid key ' . $key);
		}
		$pathPart = explode('.', $baseKey);
		if ($pathPart[0] === 'm')
		{
			if (!ModuleService::getInstance()->moduleExists($pathPart[1]))
			{
				return $this->quitError('Invalid module ' . $pathPart[1]);
			}
		}
		else if ($pathPart[0] === 't')
		{
			$path = f_util_FileUtils::buildWebeditPath('themes', $pathPart[1]);
			if (!is_dir($path))
			{
				return $this->quitError('Invalid theme ' . $pathPart[1]);
			}
		}
		$lcid =  $ls->getLCID(RequestContext::getInstance()->getLang());
		$text = "[TO_TRANSLATE]". $params[0];	
		if (count($params) > 1)
		{		
			$lang = $params[1];
			if (strlen($lang) == 2)
			{
				if (in_array($lang, RequestContext::getInstance()->getSupportedLanguages()))
				{
					$lcid = $ls->getLCID($lang);
					$text = implode(" ", array_slice($params, 2)); 
				}
				else
				{
					$text = implode(" ", array_slice($params, 1)); 
				}
			}
			else if (strlen($lang) == 5 && $lang[2] == "_")
			{
				$lcid = $lang;
				$text = implode(" ", array_slice($params, 2)); 
			}
			else
			{
				$text = implode(" ", array_slice($params, 1)); 
			}
		}	
		
		//[lcid => [id => [text, format]]
		$keysInfos = array($lcid =>array($id => $text));
		
		$this->log("Add $baseKey, $id, $lcid, $text");
		$ls->updatePackage($baseKey, $keysInfos, false, true, '');
		$ls->updateKey($lcid, $id, $baseKey, $text, 'TEXT');
		$this->quitOk("Command successfully executed");
	}
}