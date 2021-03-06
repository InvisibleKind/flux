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
class Tx_Flux_ViewHelpers_Flexform_Field_CustomViewHelperTest extends Tx_Flux_ViewHelpers_AbstractViewHelperTest {

	/**
	 * @test
	 */
	public function canGenerateAndExecuteClosureWithoutArgumentCollision() {
		$this->executeViewHelperClosure();
	}

	/**
	 * @test
	 */
	public function canGenerateAndExecuteClosureWithArgumentCollisionAndBackups() {
		$arguments = array(
			'parameters' => 'Fake parameter'
		);
		$container = $this->executeViewHelperClosure($arguments);
		$this->assertSame($container->get('parameters'), $arguments['parameters']);
	}

	/**
	 * @param array $templateVariableContainerArguments
	 * @return Tx_Fluid_Core_ViewHelper_TemplateVariableContainer
	 */
	protected function executeViewHelperClosure($templateVariableContainerArguments = array()) {
		$instance = $this->objectManager->get('Tx_Flux_ViewHelpers_Flexform_Field_CustomViewHelper');
		$container = $this->objectManager->get('Tx_Fluid_Core_ViewHelper_TemplateVariableContainer');
		$arguments = array(
			'name' => 'custom'
		);
		foreach ($templateVariableContainerArguments as $name => $value) {
			$container->add($name, $value);
		}
		$node = new Tx_Fluid_Core_Parser_SyntaxTree_ViewHelperNode($instance, $arguments);
		$childNode = new Tx_Fluid_Core_Parser_SyntaxTree_TextNode('Hello world!');
		$renderingContext = $this->getAccessibleMock('Tx_Fluid_Core_Rendering_RenderingContext');
		Tx_Extbase_Reflection_ObjectAccess::setProperty($renderingContext, 'templateVariableContainer', $container);
		$node->addChildNode($childNode);
		Tx_Extbase_Reflection_ObjectAccess::setProperty($instance, 'templateVariableContainer', $container, TRUE);
		Tx_Extbase_Reflection_ObjectAccess::setProperty($instance, 'renderingContext', $renderingContext, TRUE);
		$instance->setViewHelperNode($node);
		/** @var Closure $closure */
		$closure = $this->callInaccessibleMethod($instance, 'buildClosure');
		$parameters = array(
			'itemFormElName' => 'test',
			'itemFormElLabel' => 'Test label',
		);
		$output = $closure($parameters);
		$this->assertNotEmpty($output);
		$this->assertSame('Hello world!', $output);
		return $instance->getTemplateVariableContainer();
	}

}
