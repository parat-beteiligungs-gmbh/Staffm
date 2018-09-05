<?php

namespace Pmwebdesign\Staffm\Domain\Model;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2018 Markus Puffer <m.puffer@pm-webdesign.eu>, PM-Webdesign
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * user from fe_users table of typo3 (frontend user)
 * extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
 */
class Mitarbeiter extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    /**
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @cascade remove
     */
    protected $image = null;
    
    /**
     * personalnummer
     * 
     * @var string
     */
    protected $personalnummer = '';
    
    /**
     * dateOfBirthShow
     * 
     * @var integer
     */
    protected $dateOfBirthShow = 0;

    /**
     * dateOfBirth
     * 
     * @var integer
     */
    protected $dateOfBirth = 0;

    /**
     * lastName
     * 
     * @var string
     */
    protected $lastName = '';

    /**
     * firstName
     * 
     * @var string
     */
    protected $firstName = '';

    /**
     * status
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Position
     * @lazy
     */
    protected $position = NULL;

    /**
     * handy
     * 
     * @var string
     */
    protected $handy = '';

    /**
     * kostenstelle
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Kostenstelle
     * @lazy
     */
    protected $kostenstelle = NULL;

    /**
     * firma
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Firma
     * @lazy
     */
    protected $firma = NULL;

    /**
     * standort
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Standort
     * @lazy
     */
    protected $standort = NULL;

    /**
     * objectManager
     * Wird für den Bild/DateiUpload benötigt
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation>	
     * @lazy
     * @cascade remove 
     */
    protected $qualifikationen = NULL;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * @return void 
     */
    protected function initStorageObjects()
    {
        $this->qualifikationen = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Position $position
     */
    function getPosition()
    {
        return $this->position;
    }

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     */
    function setPosition(\Pmwebdesign\Staffm\Domain\Model\Position $position = NULL)
    {
        $this->position = $position;
    }

    /**
     * Returns the file
     * 
     * @return string $image
     */
    public function getImager()
    {
        return $this->image;
    }
    
    /**
     * Return the images
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the images
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $image)
    {
        $this->image = $image;
    }

    /**
     * Adds a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image The FileReference to be removed
     * @return void
     */
    public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image->detach($image);
    }

    /**
     * Sets the picture
     * 
     * @param \array $image
     * @return void
     */
    public function setImager(array $image)
    {
        // TODO: Image setzen funktioniert nicht mehr
        //$stringimage = implode(',', $image);
//                echo "<script> alert('imageanzahl: ".count($image)."'); </script>";                    
//                echo "<script> alert('imagename: ".$image['name']."'); </script>";               
        //if(count($image > 0)) {
        if (!empty($image['name'])) {
            //echo "<script> alert('imagename: ".$image['tmp_name']."'); </script>";    
            //foreach ($image as $img) {
            //echo "<script> alert('Namedavor: ".$img."'); </script>";    
            // Name des Bildes
            $imageName = $image['name']; // -> ergibt bei "Dummy.jpg" nur "D"
            // $imageName = $img;
            // echo "<script> alert('imageName: ".$imageName."'); </script>"; 
            // Temporärer Name (inkl. Pfad) im Upload-Verzeichnis
            $imageTempName = $image['tmp_name'];
            // echo "<script> alert('imageTempName: ".$imgageTempName."'); </script>"; 
            //$basicPictureUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');                            
            $basicFileUtility = $this->objectManager->get('TYPO3\CMS\Core\Utility\File\BasicFileUtility');

            // Ermitteln eines einzigartigen Namens (inkl. Pfad) in
            // uploads/tx_simpleblog/ und umkopieren der Datei
            $imageNameNew = $basicFileUtility->getUniqueName($image['name'], \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('uploads/tx_staffm/profilpic/'));
            if (\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($image['tmp_name'], $imageNameNew)) {
                $fileInfo = pathinfo($imageNameNew);
//                                echo "<script> alert('Nameneu: ".$imageNameNew."'); </script>";
            } else {
//                                echo "<script> alert('Nicht kopiert'); </script>";
            }

            // Setzen des Namens (ohne Pfad)
            //$this->image += basename($imageNameNew).",";			
            $this->image = basename($imageNameNew);
            //$this->image = $imageNameNew;
            //print_r($imageNameNew);                           
            //}
            //$this->image = substr($this->image, 0, -1);
            //echo "<script> alert('Gesamtname: ".$this->image."'); </script>";
        } else {
            if ($image[0] == 'kein') {
                $this->image = '';
            }
        }
    }

    /**
     * deletes the picture
     * 
     * @param \array $image
     * @return void
     */
    public function setImages(array $image)
    {
        /* if (count($image) > 0) {
          foreach ($image as $img) {
          $strImage = $strImage.$img.",";
          }
          $this->image = substr($strImage, 0, -1);
          } else {
          if ($image[0] == 'kein') {
          $this->image = '';
          }
          } */
        $this->image = '';
    }

    /**
     * Returns the personalnummer
     * 
     * @return string $personalnummer
     */
    public function getPersonalnummer()
    {
        return $this->personalnummer;
    }

    /**
     * Sets the personalnummer
     * 
     * @param string $personalnummer
     * @return void
     */
    public function setPersonalnummer($personalnummer)
    {
        $this->personalnummer = $personalnummer;
    }
    
    /**
     * Returns the marked DateOfBirth
     * 
     * @return integer $dateOfBirthShow
     */
    function getDateOfBirthShow()
    {
        return $this->dateOfBirthShow;
    }

    /**
     * 
     * @param integer $dateOfBirthShow
     */
    function setDateOfBirthShow($dateOfBirthShow)
    {
        $this->dateOfBirthShow = $dateOfBirthShow;
    }

    
    /**
     * Returns the dateOfBirth
     * 
     * @return integer $dateOfBirth
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets the dateOfBirth
     * 
     * @param integer $dateOfBirth
     * @return void
     */
    public function setDateOfBirth($dateOfBirth)
    {
        /* $dat = (string) $dateOfBirth;		
          $arrDat = explode("-", $dat);
          $arrDat[2] = "20" + $arrDat[2];
          $this->dateOfBirth = (integer) mktime(0, 0, 0, $arrDat[0], $arrDat[1], $arrDat[2]); */
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Returns the handy
     * 
     * @return string $handy
     */
    public function getHandy()
    {
        return $this->handy;
    }

    /**
     * Sets the handy
     * 
     * @param string $handy
     * @return void
     */
    public function setHandy($handy)
    {
        $this->handy = $handy;
    }

    /**
     * Returns the kostenstelle
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     */
    public function getKostenstelle()
    {
        return $this->kostenstelle;
    }

    /**
     * Sets the kostenstelle
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function setKostenstelle(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL)
    {
        /* $kostenstelle = $this->objectManager->get(
          'Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->findOneByUid(1); */
        $this->kostenstelle = $kostenstelle;
    }

    /**
     * Returns the firma
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Sets the firma
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return void
     */
    public function setFirma(\Pmwebdesign\Staffm\Domain\Model\Firma $firma = NULL)
    {
        $this->firma = $firma;
    }

    /**
     * Returns the standort
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     */
    public function getStandort()
    {
        return $this->standort;
    }

    /**
     * Sets the standort
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     * @return void
     */
    public function setStandort(\Pmwebdesign\Staffm\Domain\Model\Standort $standort = NULL)
    {
        $this->standort = $standort;
    }

    /**
     * Adds a MitarbeiterQualifikation
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation $mitarbeiterQualifikation
     * @return void
     */
    public function addMitarbeiterQualifikationen(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation $mitarbeiterQualifikation)
    {


        $quali = $this->request->getArgument('qualifikationcb');
        $mitarbeiterQualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();
        $mitarbeiterQualifikation->setMitarbeiter($mitarbeiter);
        $mitarbeiterQualifikation->setQualifikation($this->objectManager->get(
                        'Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository'
                )->findOneByUid(1));
        /* $arrMitarbeiterQualifikation = array();
          $arrMitarbeiterQualifikation =& new $mitarbeiterQualifikation; */
        $arrMitarbeiterQualifikation = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $arrMitarbeiterQualifikation->attach($mitarbeiterQualifikation);
        $qualis = $arrMitarbeiterQualifikation;
        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();
        /* for ($i = 0; $i < count($arrMitarbeiterQualifikation); $i++) {
          $mQ = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();
          $mQ = $arrMitarbeiterQualifikation[$i];
          $this->mitarbeiterqualifikationRepository->add($mQ);
          } */
        $this->mitarbeiterqualifikationRepository->add($mitarbeiterQualifikation);
        $mitarbeiter->setMitarbeiterQualifikationen($arrMitarbeiterQualifikation);
        $this->forward('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'qualis' => $qualis));

        $this->mitarbeiterQualifikationen->attach($mitarbeiterQualifikation);
    }

    /** Auskommentiert da andere Methode funktioniert
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\MitarbeiterQualifikation> $mitarbeiterQualifiaktionen
     */
    public function getMitarbeiterQualifikationenAuskommentiert()
    {
        // Sonst Fehler
        if ($this->mitarbeiterQualifikationen == null) {
            $this->mitarbeiterQualifikationen = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }

        foreach ($this->mitarbeiterqualifikationRepository->findSearchForm($this->getUid()) as $quali) {
            //for ($i = 0; i < count($mitarbeiterQualifikationRep); $i++) {
            if ($quali->getMitarbeiter()->getUsername() == $this->username) {
                $this->mitarbeiterQualifikationen->attach($quali);
            }
        }
        /* $mitarbeiterQualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();
          $mitarbeiterQualifikation->setMitarbeiter($this); */

        /* $this->mitarbeiterQualifikationen->attach($this->objectManager->get(
          'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterqualifikationRepository'
          )->findOneByUid(1)); */
        //$this->mitarbeiterQualifikationen->attach($mitarbeiterQualifikation);
        //print_r($this->mitarbeiterQualifikationen[0]->getMitarbeiter()->getPersonalnummer());
        //$this->mitarbeiterQualifikationen = $mitarbeiterQualifikationRep->findSearchForm($this->getUid());
        return $this->mitarbeiterQualifikationen;
        //$this->view->assign('mitarbeiterQualifikationen', $mitarbeiterQualifikationen);
    }

    /**
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\MitarbeiterQualifikation> $mitarbeiterQualifiaktionen
     */
    public function getMitarbeiterQualifikationen()
    {
        return $this->mitarbeiterQualifikationen;
    }

    /**
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\MitarbeiterQualifikation> $mitarbeiterQualifikationen
     * @return void
     */
    public function setMitarbeiterQualifikationen($mitarbeiterQualifikationen)
    {
        $this->mitarbeiterQualifikationen = $mitarbeiterQualifikationen;
    }

    /**
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation>
     */
    public function getQualifikationen()
    {
        return $this->qualifikationen;
    }

    /**
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation> $qualifikationen
     */
    public function setQualifikationen(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $qualifikationen)
    {
        $this->qualifikationen = $qualifikationen;
    }

    /**
     * deleteQualifikationen
     * 
     * @return void 
     */
    public function deleteQualifikationen()
    {
        $this->qualifikationen = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * 
     * @return int
     */
    /* function getDeleted() {
      return $this->deleted;
      }

      /**
     * 
     * @param int $deleted
     *
      function setDeleted($deleted) {
      $this->deleted = $deleted;
      } */
}
