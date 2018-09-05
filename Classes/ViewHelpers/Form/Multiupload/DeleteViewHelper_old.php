<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pmwebdesign\Staffm\ViewHelpers\Form\Multiupload;
//use Pmwebdesign\Simpleblog\Property\TypeConverter\FileReferenceObjectStorageConverter;
use Pmwebdesign\Staffm\Property\TypeConverter\ObjectStorageConverter;

/**
 * Description of DeleteViewHelper
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class DeleteViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\CheckboxViewHelper 
{
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
		$checked = TRUE;
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
