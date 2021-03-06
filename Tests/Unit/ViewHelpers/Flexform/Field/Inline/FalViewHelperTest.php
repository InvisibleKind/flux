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
class Tx_Flux_ViewHelpers_Flexform_Field_Inline_FalViewHelperTest extends Tx_Flux_ViewHelpers_AbstractViewHelperTest {

	/**
	 * @test
	 */
	public function createsExpectedComponent() {
		$arguments = array(
			'name' => 'test'
		);
		$instance = $this->buildViewHelperInstance($arguments, array());
		$component = $instance->getComponent();
		$this->assertInstanceOf('Tx_Flux_Form_Field_Inline_Fal', $component);
	}

	/**
	 * @test
	 */
	public function supportsHeaderThumbnail() {
		$arguments = array(
			'name' => 'test',
			'headerThumbnail' => array('test' => 'test')
		);
		$instance = $this->buildViewHelperInstance($arguments, array());
		$component = $instance->getComponent();
		$this->assertEquals($arguments['headerThumbnail'], $component->getHeaderThumbnail());
	}

	/**
	 * @test
	 */
	public function supportsForeignMatchFields() {
		$arguments = array(
			'name' => 'test',
			'foreignMatchFields' => array('test' => 'test')
		);
		$instance = $this->buildViewHelperInstance($arguments, array());
		$component = $instance->getComponent();
		$this->assertEquals($arguments['foreignMatchFields'], $component->getForeignMatchFields());
	}

}
