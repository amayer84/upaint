<?php
declare(strict_types = 1);

namespace Mattgold\Upaint\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class Button extends AbstractFormElement {
    public function render() {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $standaloneView = $objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:upaint/Resources/Private/Templates/Backend/Button.html');

        $standaloneView->setFormat('html');
        $standaloneView->setTemplatePathAndFilename($templatePath);

        $standaloneView->assignMultiple([
            'ce_uid' => $this->data['vanillaUid']
        ]);

        $editor = $standaloneView->render();

        $result = $this->initializeResultArray();
        $result['html'] = $editor;
        return $result;
    }
}