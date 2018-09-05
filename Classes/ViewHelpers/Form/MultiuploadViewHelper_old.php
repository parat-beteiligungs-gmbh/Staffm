<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pmwebdesign\Staffm\ViewHelpers\Form;

//use Pmwebdesign\Staffm\Property\TypeConverter\FileReferenceObjectStorageConverter;
use Pmwebdesign\Staffm\Property\TypeConverter\ObjectStorageConverter;

/**
 * Description of MultiuploadViewHelper
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class MultiuploadViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\UploadViewHelper
{
	/**
	 * @var \TYPO3\CMS\Extbase\Property\PropertyMapper
	 * @inject
	 */
	protected $propertyMapper;
	/**
	 * @param string|null $resources
	 * @return string
	 */
	public function render($resources = NULL) {                
		$name = $this->getName();
		foreach (array('type', 'tmp_name', 'error', 'size') as $fieldName) {
			$this->registerFieldNameForFormTokenGeneration(sprintf('%s[*][%s]', $name, $fieldName));
		}
		$this->tag->addAttribute('type', 'file');
		$this->tag->addAttribute('name', $name . '[]');
                $this->tag->addAttribute('class', 'btn btn-outline-secondary form-control-file');
                $this->tag->addAttribute('id', 'formControlFile01');
		$this->tag->addAttribute('multiple', '1');
		$this->setErrorClassAttribute();
                //$output = $this->tag->render();
		$output = "<div class='form-group'><label for='formControlFile01'>Dateien hochladen</label>" . $this->tag->render() . "</div>";
		// File uploads and already persisted uploads are merged into the same
		// array in the property mapper. The uploaded files start at index 0,
		// so we avoid collisions by assigning the indexes for persisted
		// file references by ourselves, starting at a high number.
		$this->viewHelperVariableContainer->add('Pmwebdesign\\Staffm\\ViewHelpers\\Form\\MultiuploadViewHelper', 'fileReferenceIndex', 10000);
		if (!$resources === NULL) {
			$output .= $this->renderChildren();
		} else {
			$this->templateVariableContainer->add($resources, $this->getUploadedResources());
			$output .= $this->renderChildren();
			$this->templateVariableContainer->remove($resources);
		}
		
		$this->viewHelperVariableContainer->remove('Pmwebdesign\\Staffm\\ViewHelpers\\Form\\MultiuploadViewHelper', 'fileReferenceIndex');
		return $output;
	}
	/**
	 * Return all resources that are already stored in the file system. This includes
	 * file references that are already persisted and files that were uploaded, but
	 * are not persisted yet because a mapping/validation error occured.
	 *
	 * @return array<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected function getUploadedResources() {
		// TODO: If a mapping error occurs and Extbase redirects to the edit action,
		// the properties of the form object still contain the mapped values, not
		// the persisted ones! So if you unceck an image in the Multiupload.Delete
		// viewhelper, it disappears from the property and we lose it here as well.
		// This means: we need another way to remember which file references were
		// rendered the first time somewhere ...
		$resources = $this->getPropertyValue();
		if ($resources instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage || $resources instanceof \TYPO3\CMS\Extbase\Persistence\QueryResultInterface) {
			$resources = $resources->toArray();
		} else if (!is_array($resources)) {
			$resources = array();
		}
		if ($this->configurationManager->isFeatureEnabled('rewrittenPropertyMapper') && $this->hasMappingErrorOccurred()) {
			foreach ($this->getLastSubmittedFormData() as $lastSubmittedRecord) {
				$convertedResource = $this->propertyMapper->convert($lastSubmittedRecord, 'TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
				if ($convertedResource !== NULL && !in_array($convertedResource, $resources)) {
					$resources[] = $convertedResource;
				}
			}
		}
		return $resources;
	}
}
