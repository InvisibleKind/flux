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

/**
 * @author Claus Due <claus@wildside.dk>
 * @package Flux
 */
class Tx_Flux_Backend_AreaListItemsProcessorTest extends Tx_Flux_Tests_AbstractFunctionalTest {

	/**
	 * @test
	 */
	public function canGetContentAreasDefinedInUid() {
		$instance = new Tx_Flux_Backend_AreaListItemsProcessor();
		$columns = $instance->getContentAreasDefinedInContentElement(0);
		$this->assertIsArray($columns);
	}

	/**
	 * @test
	 */
	public function canProcessListItems() {
		$instance = new Tx_Flux_Backend_AreaListItemsProcessor();
		$parameters = array(
			'row' => Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordWithoutParentAndWithoutChildren
		);
		$instance->itemsProcFunc($parameters);
	}

	/**
	 * @test
	 */
	public function canGetGridFromProviderAndRecord() {
		$instance = new Tx_Flux_Backend_AreaListItemsProcessor();
		$record = Tx_Flux_Tests_Fixtures_Data_Records::$contentRecordWithoutParentAndWithoutChildren;
		/** @var Tx_Flux_Provider_ProviderInterface $provider */
		$provider = $this->objectManager->get('Tx_Flux_Provider_Provider');
		$provider->setTemplatePathAndFilename($this->getAbsoluteFixtureTemplatePathAndFilename(self::FIXTURE_TEMPLATE_BASICGRID));
		$grid = $this->callInaccessibleMethod($instance, 'getGridFromConfigurationProviderAndRecord', $provider, $record);
		$this->assertIsArray($grid);
		$this->assertNotEmpty($grid);

	}

	/**
	 * @test
	 */
	public function callsGetContentAreasDefinedInElementIfUrlContainsParent() {
		$class = substr(get_class($this), 0, -4);
		$item1 = array('removable', 'removable');
		$item2 = array('test', 'test');
		$instance = $this->getMock($class, array('getContentAreasDefinedInContentElement', 'getUrlRequestedArea', 'getUrlRequestedParent'));
		$instance->expects($this->once())->method('getUrlRequestedArea')->will($this->returnValue('test'));
		$instance->expects($this->once())->method('getUrlRequestedParent')->will($this->returnValue(1));
		$instance->expects($this->once())->method('getContentAreasDefinedInContentElement')->will($this->returnValue(array($item1, $item2)));
		$parameters = array('items' => array());
		$instance->itemsProcFunc($parameters);
		$this->assertEquals($item2, reset($parameters['items']));
		$this->assertEquals(1, count($parameters['items']));
	}

	/**
	 * @test
	 */
	public function getContentAreasDefinedInElementReturnsEmptyArrayWhenNoProviderIsFound() {
		$class = substr(get_class($this), 0, -4);
		$instance = $this->getMock($class, array('getContentRecordByUid'));
		$instance->expects($this->once())->method('getContentRecordByUid')->with(1)->will($this->returnValue(array()));
		$service = $this->getMock('Tx_Flux_Service_FluxService', array('resolvePrimaryConfigurationProvider'));
		$service->expects($this->once())->method('resolvePrimaryConfigurationProvider')->will($this->returnValue(NULL));
		Tx_Extbase_Reflection_ObjectAccess::setProperty($instance, 'fluxService', $service, TRUE);
		$instance->getContentAreasDefinedInContentElement(1);
	}

}
