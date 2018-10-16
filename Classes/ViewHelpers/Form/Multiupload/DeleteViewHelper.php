<?php

/*
 * Copyright (C) 2018 pm-webdesign.eu 
 * Markus Puffer <m.puffer@pm-webdesign.eu>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 */

namespace Pmwebdesign\Staffm\ViewHelpers\Form\Multiupload;

//use Pmwebdesign\Staffm\Property\TypeConverter\FileReferenceObjectStorageConverter;
use Pmwebdesign\Staffm\Property\TypeConverter\ObjectStorageConverter;

/**
 * This class renders a checkbox that will remove the FileReference from the
 * associated ObjectStorage when unchecked. It may only be used inside of a
 * MultiUploadViewhelper.
 *
 * TODO: Find a better name for this class!
 */
class DeleteViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\CheckboxViewHelper {
	/**
	 * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
	 * @inject
	 */
	protected $hashService;
	/**
	 * Renders the checkbox.
	 *
	 * @param boolean $checked Specifies that the input element should be preselected
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 * @return string
	 * @api
	 */
	public function render() {
                $checked = TRUE; // No content is displayed if this is in parameter of render!
		if (!$this->arguments['value'] instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
			var_dump($this->arguments['value']);
			throw new \InvalidArgumentException('The value assigned to Form.Multiupload.DeleteViewhelper must be of type FileReference', 1421848917);
		}
		$resourcePointerValue = $this->arguments['value']->getUid();
		if ($resourcePointerValue === NULL) {
			// Newly created file reference which is not persisted yet.
			// Use the file UID instead, but prefix it with "file:" to communicate this to the type converter
			$resourcePointerValue = 'file:' . $this->arguments['value']->getOriginalResource()->getOriginalFile()->getUid();
		}
		$index = $this->viewHelperVariableContainer->get('Pmwebdesign\\Staffm\\ViewHelpers\\Form\\MultiuploadViewHelper', 'fileReferenceIndex');
		$this->viewHelperVariableContainer->addOrUpdate('Pmwebdesign\\Staffm\\ViewHelpers\\Form\\MultiuploadViewHelper', 'fileReferenceIndex', ++$index);
		// TODO: Fluid automatically adds the __identity key if the argument to the
		//		 viewhelper is a persisted model, but stripping the key on our own
		//		 is ugly here. Generate the name on ourselves?
		$name = $this->getName();
		$name = (strpos($name, '[__identity]') === FALSE ? $name : substr($name, 0, -strlen('[__identity]'))) . '[' . $index . ']';
		$this->registerFieldNameForFormTokenGeneration($name);
		$this->tag->addAttribute('name', $name . '[submittedFile][resourcePointer]');
		$this->tag->addAttribute('type', 'checkbox');
		$this->tag->addAttribute('value', htmlspecialchars($this->hashService->appendHmac((string) $resourcePointerValue)));
		if ($checked) {
			$this->tag->addAttribute('checked', 'checked');
		}
		return $this->tag->render();
	}
}