<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Claus Due <claus@wildside.dk>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

require_once t3lib_extMgm::extPath('flux', 'Tests/Fixtures/Data/Xml.php');
require_once t3lib_extMgm::extPath('flux', 'Tests/Fixtures/Data/Records.php');

/**
 * @author Claus Due <claus@wildside.dk>
 * @package Flux
 */
abstract class Tx_Flux_Tests_Provider_AbstractConfigurationProviderTest extends Tx_Flux_Tests_AbstractFunctionalTest {

	/**
	 * @var string
	 */
	protected $configurationProviderClassName = 'Tx_Flux_Provider_Configuration_ContentObjectConfigurationProvider';

	/**
	 * @return Tx_Flux_Provider_ConfigurationProviderInterface
	 */
	protected function getConfigurationProviderInstance() {
		$potentialClassName = substr(get_class($this), 0, -4);
		/** @var Tx_Flux_Provider_ConfigurationProviderInterface $instance */
		if (TRUE === class_exists($potentialClassName)) {
			$instance = $this->objectManager->get($potentialClassName);
		} else {
			$instance = $this->objectManager->get($this->configurationProviderClassName);
		}
		return $instance;
	}

	/**
	 * @return array
	 */
	protected function getBasicRecord() {
		$record = Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordWithoutParentAndWithoutChildren;
		$record['pi_flexform'] = Tx_Flux_Tests_Fixtures_Data_Xml::SIMPLE_FLEXFORM_SOURCE_DEFAULT_SHEET_ONE_FIELD;
		return $record;
	}

	/**
	 * @test
	 */
	public function canGetForm() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$form = $provider->getForm($record);
		$this->assertInstanceOf('Tx_Flux_Form', $form);
	}

	/**
	 * @test
	 */
	public function canGetGrid() {
		$templatePathAndFilename = $this->getAbsoluteFixtureTemplatePathAndFilename(self::FIXTURE_TEMPLATE_BASICGRID);
		$provider = $this->getConfigurationProviderInstance();
		Tx_Extbase_Reflection_ObjectAccess::setProperty($provider, 'templatePathAndFilename', $templatePathAndFilename, TRUE);
		$record = $this->getBasicRecord();
		$form = $provider->getGrid($record);
		$this->assertInstanceOf('Tx_Flux_Form_Container_Grid', $form);
	}

	/**
	 * @test
	 */
	public function canGetTemplatePaths() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$paths = $provider->getTemplatePaths($record);
		$this->assertIsArray($paths);
	}

	/**
	 * @test
	 */
	public function canGetForcedTemplateVariables() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$variables = $provider->getTemplateVariables($record);
		$this->assertIsArray($variables);
	}

	/**
	 * @test
	 */
	public function canGetFlexformValues() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$values = $provider->getFlexformValues($record);
		$this->assertIsArray($values);
	}

	/**
	 * @test
	 */
	public function canGetConfigurationSection() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$section = $provider->getConfigurationSectionName($record);
		$this->assertIsString($section);
	}

	/**
	 * @test
	 */
	public function canGetExtensionKey() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$extensionKey = $provider->getExtensionKey($record);
		$this->assertNotEmpty($extensionKey);
	}

	/**
	 * @test
	 */
	public function canGetTableName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$tableName = $provider->getTableName($record);
		$this->assertNotEmpty($tableName);
	}

	/**
	 * @test
	 */
	public function canGetControllerExtensionKey() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->getControllerExtensionKeyFromRecord($record);
	}

	/**
	 * @test
	 */
	public function canGetControllerActionName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->getControllerActionFromRecord($record);
	}

	/**
	 * @test
	 */
	public function canGetControllerActionReferenceName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->getControllerActionReferenceFromRecord($record);
	}

	/**
	 * @test
	 */
	public function canGetPriority() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$priority = $provider->getPriority($record);
		$this->assertIsInteger($priority);
	}

	/**
	 * @test
	 */
	public function canGetFieldName() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->getFieldName($record);
	}

	/**
	 * @test
	 */
	public function canGetTemplateFilePathAndFilename() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$provider->getTemplatePathAndFilename($record);
	}

	/**
	 * @test
	 */
	public function canPostProcessDataStructure() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$dataStructure = array();
		$config = array();
		$provider->postProcessDataStructure($record, $dataStructure, $config);
	}

	/**
	 * @test
	 */
	public function canPostProcessRecord() {
		$provider = $this->getConfigurationProviderInstance();
		$record = $this->getBasicRecord();
		$parentInstance = t3lib_div::makeInstance('t3lib_TCEmain');
		$record['test'] = 'test';
		$id = $record['uid'];
		$table = $provider->getTableName($record);
		$parentInstance->datamap[$table][$id] = $record;
		$provider->postProcessRecord('void', $id, $record, $parentInstance);
	}

}