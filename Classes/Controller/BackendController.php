<?php

namespace Mattgold\Upaint\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class BackendController extends ActionController {
	/**
     * @var \Mattgold\Upaint\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

	/**
     * @param \Mattgold\Upaint\Domain\Repository\CategoryRepository $categoryRepository
     * @return void
     */
    public function injectCategoryRepository(\Mattgold\Upaint\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\QuestionRepository
     */
    protected $questionRepository;

	/**
     * @param \Mattgold\Upaint\Domain\Repository\QuestionRepository $questionRepository
     * @return void
     */
    public function injectQuestionRepository(\Mattgold\Upaint\Domain\Repository\QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\InfoitemRepository
     */
    protected $infoitemRepository;

	/**
     * @param \Mattgold\Upaint\Domain\Repository\InfoitemRepository $infoitemRepository
     * @return void
     */
    public function injectInfoitemRepository(\Mattgold\Upaint\Domain\Repository\InfoitemRepository $infoitemRepository)
    {
        $this->infoitemRepository = $infoitemRepository;
    }

    private function getQuerySettings() {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);

        return $querySettings;
    }

    private function getCategories() {
        $querySettings = $this->getQuerySettings();
        $this->categoryRepository->setDefaultQuerySettings($querySettings);
        $categories = $this->categoryRepository->findAll();

        return $categories;
    }

	/**
     * @return void
     * @throws InvalidQueryException
     */
    public function categorylistAction() {
        $categories = $this->getCategories();

     	$this->view->assign('categories', $categories);
    }

    /**
     * @param int $category
     * @return void
     * @throws InvalidQueryException
     */
    public function questionlistAction($category = 0) {
        /* load javascript */
        $pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Upaint/OverviewModule');

        $querySettings = $this->getQuerySettings();

        $this->view->assign('categoryId', $category);

		$this->questionRepository->setDefaultQuerySettings($querySettings);

        if ($category > 0) {
            $questions = $this->questionRepository->findByCategory($category);
        } else {
            $questions = $this->questionRepository->findItems(); //findAll();
        }

     	$this->view->assign('questions', $questions);

        $categories = $this->getCategories();
        $this->view->assign('categories', $categories);
    }

    /**
     * @param int $category
     * @return void
     * @throws InvalidQueryException
     */
    public function infoitemlistAction($category = 0) {
        /* load javascript */
        $pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Upaint/OverviewModule');
        
        $querySettings = $this->getQuerySettings();

        $this->view->assign('categoryId', $category);

        $this->infoitemRepository->setDefaultQuerySettings($querySettings);

        if ($category > 0) {
            $infoitems = $this->infoitemRepository->findByCategory($category);
        } else {
            $infoitems = $this->infoitemRepository->findAll();
        }

        $this->view->assign('infoitems', $infoitems);

        $categories = $this->getCategories();
        $this->view->assign('categories', $categories);
    }

    /**
     * @return void
     * @throws InvalidQueryException
     */
    public function exporterAction() {
    
        /* extract alle nodes which belong to plugin */
        $nodeHeadings = array('Node', 'Question (id)', 'Question (title)', 'Answer (id)', 'Answer (title)', 'Info items (ids)', 'Info items (titles)', 'Next node');

        $fh = fopen('php://output', 'w');
        
        ob_start();
        
        fputcsv($fh, $nodeHeadings);
        
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);

        $nodeRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\NodeRepository::class);
        $nodeRepository->setDefaultQuerySettings($querySettings);

        $answerconfigRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerconfigRepository::class);
        $answerconfigRepository->setDefaultQuerySettings($querySettings);

        $questionRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\QuestionRepository::class);
        $questionRepository->setDefaultQuerySettings($querySettings);

        $answerRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\AnswerRepository::class);
        $answerRepository->setDefaultQuerySettings($querySettings);

        $infoitemRepository = $objectManager->get(\Mattgold\Upaint\Domain\Repository\InfoitemRepository::class);
        $infoitemRepository->setDefaultQuerySettings($querySettings);

        $arguments = $this->request->getArguments();
        $uid = $arguments['uid'];

        if (isset($arguments['uid']) && intval($uid) > 0) {
            $nodes = $nodeRepository->findByPlugin($uid);

            foreach ($nodes as $node) {

                /* get all answerconfigs which belong to node */
                $answerconfigs = $answerconfigRepository->findByNode($node->getUid());

                /* get question */
                $question = $questionRepository->findByUid($node->getQuestion());

                if ($question) {
                    $questionTitle = $question->getTitle();
                } else {
                    $questionTitle = "";
                }

                $answerconfigsFound = false;

                foreach ($answerconfigs as $answerconfig) {
                    $answerconfigsFound = true;

                    /* get answer */
                    $answer = $answerRepository->findByUid($answerconfig->getAnswer());

                    if ($answer) {
                        $answerTitle = $answer->getTitle();
                    } else {
                        $answerTitle = "";
                    }

                    $infoitems = array();

                    $infoitemsArray = explode(",", $answerconfig->getInfoitems());

                    foreach ($infoitemsArray as $infoitemKey => $infoitemValue) {
                        if (intval($infoitemValue) > 0) {
                            $infoitems[] = intval($infoitemValue);
                        }
                    }

                    if (count($infoitems) > 0) {
                        foreach ($infoitems as $infoitemUid) {
                            $infoitem = $infoitemRepository->findByUid($infoitemUid);

                            if ($infoitem) {
                                $infoitemTitle = $infoitem->getTitle();
                            } else {
                                $infoitemTitle = "Info-Item existiert nicht mehr";
                            }

                            fputcsv($fh, array(
                                $node->getUid(),
                                $node->getQuestion(),
                                $questionTitle,
                                $answerconfig->getAnswer(),
                                $answerTitle,
                                $infoitemUid,
                                $infoitemTitle,
                                $answerconfig->getNextnode()
                            )); 
                        }
                    } else {
                        fputcsv($fh, array(
                            $node->getUid(),
                            $node->getQuestion(),
                            $questionTitle,
                            $answerconfig->getAnswer(),
                            $answerTitle,
                            -1,
                            "Keine Info-Items vorhanden",
                            $answerconfig->getNextnode()
                        ));
                    }
                }

                if (!$answerconfigsFound) {
                    fputcsv($fh, array(
                        $node->getUid(),
                        $node->getQuestion(),
                        $questionTitle,
                        -1,
                        "Keine Antwort definiert",
                        -1,
                        "Keine Info-Items vorhanden",
                        $answerconfig->getNextnode()
                    ));
                }
            }
        }
        
        $string = ob_get_clean();
        
        $filename = 'csv_' . date('Ymd') .'_' . date('His');
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename.csv\";" );
        header("Content-Transfer-Encoding: binary");

        exit($string);
    }

    /**
     * @return void
     * @throws InvalidQueryException
     */
    public function importerAction() {
        $arguments = $this->request->getArguments();
        $uid = $arguments['uid'];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');

        $queryBuilderInfoitem = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_upaint_domain_model_infoitem');

        $queryBuilderSysFileReference = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');

        if ($uid > 0) {
            // try to import content element

            $statement = $queryBuilder
                ->select('*')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
                )
                ->execute();

            while ($row = $statement->fetch()) {

                /* try to extraxt import uid from ext configuration */
                $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('upaint');

                $importUid = intval($extensionConfiguration['import_uid']) > 0 ? intval($extensionConfiguration['import_uid']) : 1;

                $queryBuilderInfoitem
                    ->insert('tx_upaint_domain_model_infoitem')
                    ->values([
                        'pid'                   => $importUid,
                        'title'                 => '[IMPORTIERT] ' . $row['header'],
                        'long_description'      => $row['bodytext'],
                        'category'              => 1
                    ])
                    ->execute();

                // get new uid for image reference
                $infoitemUid = $queryBuilderInfoitem->getConnection()->lastInsertId();

                // get first image 
                $statementSysFileReference = $queryBuilderSysFileReference
                    ->select('*')
                    ->from('sys_file_reference')
                    ->where(
                        $queryBuilderSysFileReference->expr()->eq('uid_foreign', $queryBuilderSysFileReference->createNamedParameter($uid, \PDO::PARAM_INT)),
                        $queryBuilderSysFileReference->expr()->eq('deleted', 0),
                        $queryBuilderSysFileReference->expr()->eq('tablenames', $queryBuilderSysFileReference->createNamedParameter('tt_content'))
                    )
                    ->execute();

                while ($rowSysFileReference = $statementSysFileReference->fetch()) {
                    $valuesC = array();

                    foreach ($rowSysFileReference as $key => $value) {
                        if ($key == 'tablenames') {
                            $valuesC[$key] = 'tx_upaint_domain_model_infoitem';
                        } elseif ($key == 'fieldname') {
                            $valuesC[$key] = 'fal_media_c';
                        } elseif ($key == 'uid_foreign') {
                            $valuesC[$key] = $infoitemUid;
                        } elseif ($key != 'uid') {
                            $valuesC[$key] = $value;
                        }
                    }

                    $valuesD = $valuesC;
                    $valuesD['fieldname'] = 'fal_media_d';

                    $queryBuilderSysFileReference
                        ->insert('sys_file_reference')
                        ->values($valuesC)
                        ->execute();

                    $queryBuilderSysFileReference
                        ->insert('sys_file_reference')
                        ->values($valuesD)
                        ->execute();
                }

                $this->view->assign('success', 1);
            }
        }
    }
}