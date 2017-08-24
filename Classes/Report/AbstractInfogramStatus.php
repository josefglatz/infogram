<?php  declare(strict_types=1);
namespace JosefGlatz\Infogram\Report;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Reports\StatusProviderInterface;

/**
 * Provides shared functionality for all infogram reports.
 */
abstract class AbstractInfogramStatus implements StatusProviderInterface
{
    /**
     * Assigns variables to the fluid StandaloneView and renders the view.
     *
     * @param string $templateFilename
     * @param array $variables
     * @return string
     */
    protected function getRenderedReport($templateFilename = '', $variables = [])
    {
        $templatePath = 'EXT:infogram/Resources/Private/Templates/Backend/Reports/' . $templateFilename;
        $standaloneView = $this->getFluidStandaloneViewWithTemplate($templatePath);
        $standaloneView->assignMultiple($variables);

        return $standaloneView->render();
    }

    /**
     * Initializes a StandaloneView with a template and returns it.
     *
     * @param string $templatePath
     * @return StandaloneView
     */
    private function getFluidStandaloneViewWithTemplate($templatePath = '')
    {
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($templatePath));

        return $standaloneView;
    }

    /**
     * Returns the status of an extension or (sub)system
     *
     * @return array An array of \TYPO3\CMS\Reports\Status objects
     */
    abstract public function getStatus();
}
