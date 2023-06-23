<?php
declare(strict_types = 1);

namespace Mattgold\Upaint\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class Path extends AbstractFormElement {
    public function render() {
        // Custom TCA properties and other data can be found in $this->data, for example the above
        // parameters are available in $this->data['parameterArray']['fieldConf']['config']['parameters']

        $result = $this->initializeResultArray();
        $result['html'] = $this->loadPaths($this->data);
        return $result;
    }

    private function getQuerySettings() {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);

        return $querySettings;
    }

    public function loadPaths($data) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $standaloneView = $objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:upaint/Resources/Private/Templates/Backend/Paths.html');

        $standaloneView->setFormat('html');
        $standaloneView->setTemplatePathAndFilename($templatePath);

        // get all info-items which are assigned to the answers
        $answerconfigRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerconfigRepository::class);
        $querySettings = $this->getQuerySettings();
        $answerconfigRepository->setDefaultQuerySettings($querySettings);

        $infoitemRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\InfoitemRepository::class);
        $infoitemRepository->setDefaultQuerySettings($querySettings);

        $nodeRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\NodeRepository::class);
        $nodeRepository->setDefaultQuerySettings($querySettings);

        $questionRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\QuestionRepository::class);
        $questionRepository->setDefaultQuerySettings($querySettings);

        $answerRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerRepository::class);
        $answerRepository->setDefaultQuerySettings($querySettings);

        $nodes = explode(",", $data['flexFormRowData']['settings.questionlist']['vDEF']);

        // helper array to check if info-item has been added to the $infoitems array
        $infoitemsControlArray = array();

        // those arrays hold the objects which are needed in the view
        $infoitems          = array();
        $unusedInfoitems    = array();
        $questionArray      = array();
        $answerArray        = array();

        // this array holds the relation between info-item and question/answer combination
        $qaArray = array();

        if (is_array($nodes)) {
            foreach ($nodes as $node_id) {
                // add question to question array 
                $currentNode = $nodeRepository->findByUid($node_id);
                
                if ($currentNode) {
                    $question = $questionRepository->findByUid($currentNode->getQuestion());

                    if ($question) {
                        $questionArray[$question->getUid()] = $question;

                        $answerconfigs = $answerconfigRepository->findByNode($node_id);

                        foreach ($answerconfigs as $answerconfig) {
                            $answerconfigList = explode(",", $answerconfig->getInfoitems());

                            $answer = $answerRepository->findByUid($answerconfig->getAnswer());

                            if ($answer) {
                                $answerArray[$answer->getUid()] = $answer;

                                foreach ($answerconfigList as $answerconfig_item) {
                                    // get infoitem by uid 
                                    $infoitem = $infoitemRepository->findByUid($answerconfig_item);

                                    if ($infoitem) {
                                        if (!in_array($answerconfig_item, $infoitemsControlArray)) {
                                            // add it to the array where all used infoitems are stored
                                            $infoitems[] = $infoitem;

                                            $infoitemsControlArray[] = $answerconfig_item;
                                        }

                                        $qaArray[$infoitem->getUid()][] = array(
                                            'q' => $question->getUid(),
                                            'a' => $answer->getUid()
                                        );
                                    }
                                }
                            }
                        }
                    } 
                }
            }
        }

        $infoitempool = $data['flexFormRowData']['settings.infoitempool']['vDEF'];

        foreach ($infoitempool as $infoitempool_entry) {
            if (!in_array($infoitempool_entry, $infoitemsControlArray)) {
                $unusedInfoitems[] = $infoitemRepository->findByUid($infoitempool_entry);
            }
        }

        $standaloneView->assignMultiple([
            'infoitems'         => $infoitems,
            'unusedInfoitems'   => $unusedInfoitems,
            'questions'         => $questionArray,
            'answers'           => $answerArray,
            'qa'                => $qaArray,
            'deadlock'          => !($this->checkDeadlock($data['vanillaUid'])),
            'pluginUid'         => $data['vanillaUid']
        ]);

        $editor = $standaloneView->render();

        return $editor;
    }

    private function checkDeadlock($plugin_uid) {
        // get start nodes
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        $querySettings = $this->getQuerySettings();

        $nodeRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\NodeRepository::class);
        $nodeRepository->setDefaultQuerySettings($querySettings);

        $nodes = $nodeRepository->findByPlugin($plugin_uid);

        $noDeadlock = true;

        foreach ($nodes as $node) {
            $noDeadlock = $this->checkDeadlockForNode($node, array());
        }

        return $noDeadlock;
    }

    private function checkDeadlockForNode($node, $path_array) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        $answerconfigRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerconfigRepository::class);
        $querySettings = $this->getQuerySettings();
        $answerconfigRepository->setDefaultQuerySettings($querySettings);

        $nodeRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\NodeRepository::class);
        $nodeRepository->setDefaultQuerySettings($querySettings);

        $returnResult = true;

        if ($node) {
            if (!in_array($node->getQuestion(), $path_array)) {
                $path_array[] = $node->getQuestion();

                // get all answerconfig which belong to the node 
                $answerconfigs = $answerconfigRepository->findByNode($node->getUid());

                foreach ($answerconfigs as $answerconfig) {
                    if ($answerconfig->getNextnode() > 0) {
                        $next_node = $nodeRepository->findByUid($answerconfig->getNextnode());

                        if (!$this->checkDeadlockForNode($next_node, $path_array)) {
                            $returnResult = false;
                        }
                    }
                }
            } else {
                $returnResult = false;
            }
        } 

        return $returnResult;
    }
}