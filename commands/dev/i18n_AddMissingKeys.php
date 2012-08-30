<?php
/**
 * @package modules.i18n
 */
class commands_i18n_AddMissingKeys extends c_ChangescriptCommand
{
	/**
	 * @return string
	 */
	public function getUsage()
	{
		return "<packageName> <lcid> [<reflcid>] [--delete]";
	}
	
	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	public function _execute($params, $options)
	{
		$this->message("== Add Missing Keys ==");
		$this->loadFramework();
		
		$delete = isset($options['delete']);		
		$pk = $this->getPackageByName($params[0]);
		$refLCID = 	count($params) === 3 ? $params[2] : 'fr_FR';
		$LCID = $params[1];
		$result = i18n_ModuleService::getInstance()->addMissingKeys($pk, $LCID, $refLCID, $delete);
		if ($result !== true)
		{
			return $this->quitError($result);
		}
		$this->quitOk("Command successfully executed");
	}

	/**
	 * @return string
	 * For exemple "initialize a document"
	 */
	public function getDescription()
	{
		return "Add Missing Keys";
	}
	
	/**
	 * @return string[]
	 */
	public function getOptions()
	{
		return array('delete');
	}
	
	/**
	 * This method is used to handle auto-completion for this command.
	 * @param integer $completeParamCount the parameters that are already complete in the command line
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return string[] or null
	 */
	public function getParameters($completeParamCount, $params, $options, $current)
	{
		if ($completeParamCount === 0)
		{
			$components = array();
			foreach ($this->getBootStrap()->getProjectDependencies() as $p)
			{
				/* @var $p c_Package */
				if (is_dir(f_util_FileUtils::buildPath($p->getPath(), 'i18n')))
				{
					$components[] = $p->getKey();
				}
			}
			return $components;
		}
		elseif ($completeParamCount === 1 || $completeParamCount === 2)
		{
			return array('fr_FR', 'en_GB', 'de_DE');
		}
		return null;
	}
	
	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return boolean
	 */
	protected function validateArgs($params, $options)
	{
		if (count($params) < 2 || count($params) > 3) 
		{
			$this->warnMessage('Invalid parameters count');
			return false;
		}
		$pk = $this->getPackageByName($params[0]);
		if ($pk->isValid() && $pk->isInProject())
		{
			$refLCID = 	count($params) === 3 ? $params[2] : 'fr_FR';
			$LCID = $params[1];
			if ($refLCID === $LCID)
			{
				$this->warnMessage('lcid must be different than reflcid: ' . $LCID);
			}
			elseif (!preg_match('/^[a-z]{2}_[A-Z]{2}$/', $refLCID))
			{
				$this->warnMessage('Invalid reflcid format: ' . $refLCID);
			}
			elseif (!preg_match('/^[a-z]{2}_[A-Z]{2}$/', $LCID))
			{
				$this->warnMessage('Invalid lcid format: ' . $LCID);
			}
			else
			{
				return true;
			}
		}
		else
		{
			$this->warnMessage('Invalid packageName: ' . $params[0]);
		}
		return false;
	}
}