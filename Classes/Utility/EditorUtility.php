<?php
declare(strict_types=1);
namespace Mattgold\Upaint\Utility;

/**
 * Class EditorUtility
 */
class EditorUtility extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    private function getQuerySettings() {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);

        return $querySettings;
    }

    public function questionTitle(&$parameters, $parentObject) {

        if (is_array($parameters['row']['question'])) {
            $questionId = intval($parameters['row']['question'][0]);
        } else {
            $questionId = intval($parameters['row']['question']);
        }

        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_upaint_domain_model_question', $questionId);

        $prefix = "";

        $node = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_upaint_domain_model_node', $parameters['row']['uid']);

        if (isset($node['hint']) && !empty($node['hint'])) {
            $hint = ", " . strip_tags($node['hint']);
        } else {
            $hint = "";
        }

        //mail("a.mayer@mattgold-media.de", "Test", print_r($parameters['row'], true));

        if (intval($parameters['row']['parentid']) > 0) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

            $nodeRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\NodeRepository::class);
            $querySettings = $this->getQuerySettings();
            $nodeRepository->setDefaultQuerySettings($querySettings);

            $answerconfigRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerconfigRepository::class);
            $answerconfigRepository->setDefaultQuerySettings($querySettings);

            $plugin_id = $parameters['row']['parentid'];
            
            // we need to get the id of the current node
            $current_node = $parameters['row']['uid'];

            // check if one of the nodes points to the current node as nextnode
            $prefix = "(#" . $parameters['row']['uid'] . $hint . ") Startfrage: ";

            $nodes = $answerconfigRepository->findByNextnode($current_node);

            if (count($nodes) > 0) {
                $prefix = "(#" . $parameters['row']['uid'] . $hint . ") Folgefrage: ";
            }            
        }

        /* determine question title */
        if (isset($record)) {
            $title = $record['title'];
        } else {
            $title = "Frage gelöscht (ID: " . $questionId . ")";
        }

        $parameters['title'] = $prefix . $title . " (" . $parameters['row']['uid'] . $hint . ")";
    }

    public function answerTitle(&$parameters, $parentObject) {
        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_upaint_domain_model_answer', $parameters['row']['answer'][0]);
        
        $prefix = "Bitte überprüfen: ";

        if ((isset($parameters['row']['nextnode'][0]) && $parameters['row']['nextnode'][0] > 0) || !empty($parameters['row']['infoitems'])) {
            $prefix = "";
        }

        $parameters['title'] = $prefix . $record['title'] . " (" . $record['uid'] . ")";
    }

    public function loadInfoitems(array &$configuration) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        // extract all questions
        $infoitemRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\InfoitemRepository::class);
        $querySettings = $this->getQuerySettings();
        $infoitemRepository->setDefaultQuerySettings($querySettings);
        $infoitems = $infoitemRepository->findAll();

        /* get all infoitems from pool */
        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_upaint_domain_model_node', $configuration['row']['node']);

        $allowedInfoitems = array();
        $selectedUidsPlugin = array();
        $selectedUidsRecord = explode(",", $configuration['row']['infoitems']);

        if (isset($record['parentid']) && intval($record['parentid']) > 0) {
            $record_plugin = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', $record['parentid']);

            if (isset($record_plugin['pi_flexform'])) {
                $ffs = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\FlexFormService::class);
                $flex = $ffs->convertFlexFormContentToArray($record_plugin['pi_flexform']);

                if (!empty($flex['settings']['categories'])) {
                    $allowedInfoitems = $infoitemRepository->findByCategoryList($flex['settings']['categories']);
                }

                $selectedUidsPlugin = explode(',', $flex['settings']['infoitempool']);
            }
        }

        foreach ($infoitems as $infoitem_key => $infoitem_value) {
            // check if info-item has one of the selected categories
            if (in_array($infoitem_value->getUid(), $allowedInfoitems) || in_array($infoitem_value->getUid(), $selectedUidsPlugin) || in_array($infoitem_value->getUid(), $selectedUidsRecord)) {
                $configuration['items'][] = [$infoitem_value->getTitle(), $infoitem_value->getUid()];  
            }
        }
    }

    public function loadQuestion(array &$configuration) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        $questionRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\QuestionRepository::class);
        $querySettings = $this->getQuerySettings();
        $questionRepository->setDefaultQuerySettings($querySettings);

        /* let's extract the categories from the tt_content plugin so we can display questions which are assigned to the given categories */
        $pluginUid = $configuration['inlineParentUid'];

        $questionList = array();

        if (intval($pluginUid) > 0) {
            $record_plugin = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', $pluginUid);

            if (isset($record_plugin['pi_flexform'])) {
                $ffs = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\FlexFormService::class);
                $flex = $ffs->convertFlexFormContentToArray($record_plugin['pi_flexform']);

                if (isset($flex['settings']['categories'])) {
                    $allowedCategories = explode(",", $flex['settings']['categories']);

                    foreach ($allowedCategories as $categoryId) {
                        $questions = $questionRepository->findByCategory($categoryId);

                        foreach ($questions as $question) {
                            if (!isset($questionList[$question->getUid()])) {
                                $questionList[$question->getUid()] = $question;
                            }
                        }
                    }
                }
            }
        }

        if (empty($questionList)) {
            $questions = $questionRepository->findAll();

            foreach ($questions as $question) {
                if (!isset($questionList[$question->getUid()])) {
                    $configuration['items'][] = [$question->getTitle(), $question->getUid()];
                }
            }
        } else {
            foreach ($questionList as $key => $value) {
                $configuration['items'][] = [$value->getTitle(), $key];
            }

            if (!isset($questionList[$configuration['row']['question']])) {
                $question = $questionRepository->findByUid(intval($configuration['row']['question']));

                if (!is_null($question)) {
                    $configuration['items'][] = [$question->getTitle(), $question->getUid()];
                }
            }
        }
    }

    public function loadAnswers(array &$configuration) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        // get current node of answer
        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_upaint_domain_model_node', $configuration['row']['node']);

        $answerRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerRepository::class);
        $querySettings = $this->getQuerySettings();
        $answerRepository->setDefaultQuerySettings($querySettings);
        $answers = $answerRepository->findByQuestion($record['question']);

        foreach ($answers as $answer) {
            $configuration['items'][] = [$answer->getTitle(), $answer->getUid()];
        }
    }

    public function loadInfoitemPool(array &$configuration) {
        /* load tt_conent entry by id */
        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tt_content', $configuration['row']['uid']);

        /* load and parse flexform */
        $ffs = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\FlexFormService::class);
        $flex = $ffs->convertFlexFormContentToArray($record['pi_flexform']);

        /* load infoitem repository */
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        $infoitemRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\InfoitemRepository::class);
        $querySettings = $this->getQuerySettings();
        $infoitemRepository->setDefaultQuerySettings($querySettings);
        $infoitems = $infoitemRepository->findAll();


        if (isset($flex['settings']['infoitempool'])) {
            $selectedUids = explode(',', $flex['settings']['infoitempool']);
        } else {
            $selectedUids = array();
        }

        /* add allowed and selected infoitems */
        if (!empty($flex['settings']['categories'])) {
            $allowedInfoitems = $infoitemRepository->findByCategoryList($flex['settings']['categories']);
        } else {
            $allowedInfoitems = array();
        }

        foreach ($infoitems as $infoitem_key => $infoitem_value) {
            // check if info-item has one of the selected categories
            if (in_array($infoitem_value->getUid(), $allowedInfoitems) || in_array($infoitem_value->getUid(), $selectedUids)) {
                $configuration['items'][] = [$infoitem_value->getTitle(), $infoitem_value->getUid()];        
            }
        }
    }

    public function getNextQuestion(array &$configuration) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        /* get all nodes which belong to current plugin */
        $querySettings = $this->getQuerySettings();

        $questionRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\QuestionRepository::class);
        $questionRepository->setDefaultQuerySettings($querySettings);

        $nodeRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\NodeRepository::class);
        $nodeRepository->setDefaultQuerySettings($querySettings);

        $record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_upaint_domain_model_node', $configuration['row']['node']);
    
        $nodes = $nodeRepository->findByPlugin($record['parentid']);

        $questions = array();

        $configuration['items'][] = ['Keine weitere Frage anzeigen', 0];

        foreach ($nodes as $node) {
            $question = $questionRepository->findByUid($node->getQuestion());

            $configuration['items'][] = [$question->getTitle(), $node->getUid()];
        }
    }
}