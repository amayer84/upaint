<?php

namespace Mattgold\Upaint\Controller;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Mattgold\Upaint\Domain\Model\Session;

class FrontendController extends ActionController {

	/**
     * @var \Mattgold\Upaint\Domain\Repository\InfoitemRepository
     */
    protected $infoitemRepository;

    /**
     * @param \Mattgold\Upaint\Domain\Repository\InfoitemRepository $infoitemRepository
     */
    public function injectInfoitemRepository(\Mattgold\Upaint\Domain\Repository\InfoitemRepository $infoitemRepository)
    {
        $this->infoitemRepository = $infoitemRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\NodeRepository
     */
    protected $nodeRepository;

    /**
     * @param \Mattgold\Upaint\Domain\Repository\NodeRepository $nodeRepository
     */
    public function injectNodeRepository(\Mattgold\Upaint\Domain\Repository\NodeRepository $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\QuestionRepository
     */
    protected $questionRepository;

    /**
     * @param \Mattgold\Upaint\Domain\Repository\QuestionRepository $questionRepository
     */
    public function injectQuestionRepository(\Mattgold\Upaint\Domain\Repository\QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\AnswerconfigRepository
     */
    protected $answerconfigRepository;

    /**
     * @param \Mattgold\Upaint\Domain\Repository\AnswerconfigRepository $answerconfigRepository
     */
    public function injectAnswerconfigRepository(\Mattgold\Upaint\Domain\Repository\AnswerconfigRepository $answerconfigRepository)
    {
        $this->answerconfigRepository = $answerconfigRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\AnswerRepository
     */
    protected $answerRepository;

    /**
     * @param \Mattgold\Upaint\Domain\Repository\AnswerRepository $answerRepository
     */
    public function injectAnswerRepository(\Mattgold\Upaint\Domain\Repository\AnswerRepository $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * @var \Mattgold\Upaint\Domain\Repository\SessionRepository
     */
    protected $sessionRepository;

    /**
     * @param \Mattgold\Upaint\Domain\Repository\SessionRepository $sessionRepository
     */
    public function injectSessionRepository(\Mattgold\Upaint\Domain\Repository\SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

	/**
     * @param string $token
     * @return void
     */
    public function listAction($token = "")
    {
        /* determine current plugin id which is needed in view */
        $pluginUid = $this->configurationManager->getContentObject()->data['uid'];
        $this->view->assign('pluginUid', $pluginUid);

    	/* single mode: get single infoitem */
        if ($this->settings['mode'] != 'advanced' && isset($this->settings['infoitem'])) {
            $infoitems = explode(",", $this->settings['infoitem']);

            $infoitemsArray = array();

            foreach ($infoitems as $infoitemUid) {
                $infoitemArray[] = $this->infoitemRepository->findByUid($infoitemUid);
            }

            $this->view->assign('infoitems', $infoitemArray);
        }

        if ($this->settings['mode'] == 'advanced') {
            // get current plugin uid 
            $contentObj = $this->configurationManager->getContentObject();
            $plugin_uid = $contentObj->data['uid'];

            // get all nodes, answerconfigs questions and answers which belong to plugin instance
            $nodeArray = array();
            $nodes = $this->nodeRepository->findByPlugin((int)$plugin_uid);

            $startNodes = array();
            $questionArray = array();
            $answerconfigArray = array();
            $answerArray = array();

            foreach ($nodes as $node) {
                $nodeArray[$node->getUid()] = $node;

                $startNodes[] = $node->getUid();

                if (!in_array($node->getQuestion(), $questionArray)) {
                    $questionArray[$node->getQuestion()] = $this->questionRepository->findByUid($node->getQuestion());
                }

                // find answerconfigs which belong to node
                $answerconfigs = $this->answerconfigRepository->findByNode($node->getUid());

                foreach ($answerconfigs as $answerconfig) {
                    if (!in_array($answerconfig->getAnswer(), $answerArray)) {
                        $answerArray[$answerconfig->getAnswer()] = $this->answerRepository->findByUid($answerconfig->getAnswer());
                    }

                    $answerconfigArray[$answerconfig->getUid()] = $answerconfig;
                }
            }

            foreach ($answerconfigArray as $answerconfig) {
                if ($answerconfig->getNextnode() > 0) {
                    // remove node as start node
                    $startNodes = array_diff($startNodes, array($answerconfig->getNextnode()));
                }
            }

            /* get all infoitem from pool */
            $infoitems = explode(",", $this->settings['infoitempool']);

            $infoitemArray = array();

            foreach ($infoitems as $infoitem) {
                $infoitemObject = $this->infoitemRepository->findByUid(intval($infoitem));

                if ($infoitemObject) {
                    $infoitemArray[] = $infoitemObject;
                }
            }

            $this->view->assign('nodes', $nodeArray);
            $this->view->assign('questions', $questionArray);
            $this->view->assign('answerconfigs', $answerconfigArray);
            $this->view->assign('answers', $answerArray);
            $this->view->assign('startNodes', $startNodes);
            $this->view->assign('infoitems', $infoitemArray);
            $this->view->assign('token', sha1(microtime(true).mt_rand(10000,90000)));
        }

        if ($this->settings['mode'] == 'mixed') {
            $infoitems = explode(",", $this->settings['infoitempool']);

            $infoitemArray = array();

            foreach ($infoitems as $infoitem) {
                $infoitemArray[] = $this->infoitemRepository->findByUid($infoitem);
            }

            $stored_infoitems = explode(",", $_COOKIE['upaint_infoitem_list']);

            if (!empty($_COOKIE['upaint_infoitem_list'])) {

                $entries = $this->sessionRepository->findByToken($token);

                if ($entries->count() == 0) {
                    $session_entry = new Session();

                    $session_entry->setToken($token);
                    $session_entry->setInfoitems($_COOKIE['upaint_infoitem_list']);

                    $this->sessionRepository->add($session_entry);
                }

                // remove duplicate entries from infoitem list 
                $infoitemList = array_unique(explode(",", $_COOKIE['upaint_infoitem_list']));

                $this->view->assign('infoitems', $infoitemList);
            } else {
                $entries = $this->sessionRepository->findByToken($token);

                foreach ($entries as $entry) {
                    $this->view->assign('infoitems', explode(",", $entry->getInfoitems()));
                }
            }

            $this->view->assign('infoitem_pool', $infoitemArray);
        }

        /* generate summary table */
        if ($this->settings['mode'] == 'table') {
            /* create a button in order to change the given answers */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');

            $pageId = -1;
            $piFlexform = "";

            $statement = $queryBuilder
                ->select('*')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($this->settings['plugin'], \PDO::PARAM_INT))
                )
                ->execute();

            while ($row = $statement->fetch()) {
                $pageId = $row['pid'];
            }

            // $_COOKIE['upaint_plugin_' . $this->settings['plugin']]

            $this->view->assign('pageId', $pageId);
        }
    }
}