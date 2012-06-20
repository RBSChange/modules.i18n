<?php
/**
 * commands_i18n_FlushUserEdited
 * @package modules.i18n.command
 */
class commands_i18n_FlushUserEdited extends c_ChangescriptCommand
{
	/**
	 * @return string
	 */
	public function getUsage()
	{
		return "";
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return "Flush User edited locales to override project path";
	}

	/**
	 * @param integer $completeParamCount the parameters that are already complete in the command line
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return string[] or null
	 */
	public function getParameters($completeParamCount, $params, $options, $current)
	{
		$ls = LocaleService::getInstance();
		$packages = $ls->getUserEditedPackageNames();		
		return array_diff(array_keys($packages), $params);
	}

	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	public function _execute($params, $options)
	{
		$this->message("== Flush User edited locales ==");
		$this->loadFramework();
		$ls = LocaleService::getInstance();	
		if (count($params))
		{
			$prefixPath = $params[0];
		}
		else
		{
			$prefixPath = null;
		}
		$tm = f_persistentdocument_TransactionManager::getInstance();	
		$packages = $ls->getUserEditedPackageNames();
		foreach ($packages as $baseKey => $nbkeys) 
		{
			if ($prefixPath !== null && strpos($baseKey, $prefixPath) !== 0)
			{
				$this->log($baseKey . " ignored");
				continue;
			}
			try 
			{
				$tm->beginTransaction();
				//array[id => [lcid => ['content' => string, 'useredited' => integer, 'format' => string]]]
				$keysInfos = array();
				foreach ($ls->getPackageContent($baseKey) as $id => $info1) 
				{
					foreach ($info1 as $lcid => $data) 
					{
						if ($data['useredited'])
						{
							$keysInfos[$lcid][$id] = array($data['content'], $data['format']);
							$ls->updateKey($lcid, $id, $baseKey, $data['content'], $data['format']);
						}
					}
				}	
				if (count($keysInfos))
				{	
					$ls->updatePackage($baseKey, $keysInfos, true);
					$this->log($baseKey . " flushed");
				}
				$tm->commit();
			}
			catch (Exception $e)
			{
				$tm->rollBack($e);
			}			
		}
		$this->quitOk("Command successfully executed");
	}
}