<?php declare(strict_types=1);

namespace JosefGlatz\Infogram\Controller;


use JosefGlatz\Infogram\Domain\Model\Dto\ExtensionConfiguration;
use JosefGlatz\Infogram\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class InfogramController extends ActionController
{
    /**
     * @var ApiService $infogramApi
     */
    protected $infogramApi;

    /**
     * @var ExtensionConfiguration $extensionConfiguration
     */
    protected $extensionConfiguration;

    public function initializeAction()
    {
        $this->infogramApi = GeneralUtility::makeInstance(ApiService::class);
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    protected function initializeView(ViewInterface $view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
        $view->assign('extensionConfiguration', $this->extensionConfiguration);
        parent::initializeView($view);
    }

    public function showAction() {
        if (!empty($this->settings['infographicId'])) {
            $this->view->assign(
                'infographic',
                $this->infogramApi->getInfographic($this->settings['infographicId'])
            );
        }
    }
}
