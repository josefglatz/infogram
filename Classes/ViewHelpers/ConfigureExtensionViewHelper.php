<?php declare(strict_types=1);

namespace JosefGlatz\Infogram\ViewHelpers;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ConfigureExtensionViewHelper
 */
class ConfigureExtensionViewHelper extends AbstractViewHelper
{
    /**
     * We must not return encoded html
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Renders link tag to extension manager configuration
     */
    public function render()
    {
        $urlParameters = array(
            'tx_extensionmanager_tools_extensionmanagerextensionmanager[extension][key]' => 'infogram',
            'tx_extensionmanager_tools_extensionmanagerextensionmanager[action]' => 'showConfigurationForm',
            'tx_extensionmanager_tools_extensionmanagerextensionmanager[controller]' => 'Configuration',
            'returnUrl' => BackendUtility::getModuleUrl('system_ReportsTxreportsm1'),
        );
        $url = BackendUtility::getModuleUrl('tools_ExtensionmanagerExtensionmanager', $urlParameters);

        return '<a href="' . htmlspecialchars($url) . '">' . $this->renderChildren() . '</a>';
    }
}
