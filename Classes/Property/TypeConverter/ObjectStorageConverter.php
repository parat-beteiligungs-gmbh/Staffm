<?php

/*
 * Copyright (c) 2018 Markus Puffer <m.puffer@pm-webdesign.eu>.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Markus Puffer <m.puffer@pm-webdesign.eu> - initial API and implementation and/or initial documentation
 */

namespace Pmwebdesign\Staffm\Property\TypeConverter;

/**
 * Description of ObjectStorageConverter
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class ObjectStorageConverter extends \TYPO3\CMS\Extbase\Property\TypeConverter\ObjectStorageConverter
{
    /**
     * @var string[]
     */
    protected $sourceTypes = ['array'];
    /**
     * Take precedence over the available ObjectStorageConverter
     *
     * @var int
     */
    protected $priority = 20;
    /**
     * Return the source, if it is an array, otherwise an empty array.
     * Filter out empty uploads
     *
     * @param mixed $source
     * @return array
     * @api
     */
    public function getSourceChildPropertiesToBeConverted($source)
    {
        $propertiesToConvert = [];
        // TODO: Find a nicer way to throw away empty uploads
        foreach ($source as $propertyName => $propertyValue) {
            if ($this->isUploadType($propertyValue)) {
                if ($propertyValue['error'] !== \UPLOAD_ERR_NO_FILE || isset($propertyValue['submittedFile']['resourcePointer'])) {
                    $propertiesToConvert[$propertyName] = $propertyValue;
                }
            } else {
                $propertiesToConvert[$propertyName] = $propertyValue;
            }
        }
        return $propertiesToConvert;
    }
    /**
     * Check if this is an upload type
     *
     * @param mixed $propertyValue
     * @return bool
     */
    protected function isUploadType($propertyValue)
    {
        return is_array($propertyValue) && isset($propertyValue['tmp_name']) && isset($propertyValue['error']);
    }
}
