<?php
namespace Typovision\Simpleblog\ViewHelpers;

/**
 * Description of FindUser
 *
 */
class FinduserViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     *
     * @var \Typovision\Simpleblog\Controller\UserController
     */
    public $usrcont;
    
    /**
     *
     * @var \Typovision\Simpleblog\Domain\Model\User
     */
    public $userf = NULL;
    
    /*
     * @var string
     */
    protected $usern = '';


    /**
     * Jeder ViewHelper muss Methode render implementieren der den Inhalt zurückliefert
     * @return string
     */
    public function render()
    {
        //$this->renderChildren();
        $this->userf = new \Typovision\Simpleblog\Domain\Model\User();
        $this->usrcont = new \Typovision\Simpleblog\Controller\UserController();
        $this->userf = $this->usrcont->getUser($GLOBALS['TSFE']->fe_user->user['uid']);
        return $this->userf->getUserid();
    }
}
?>