<?php

namespace Mattgold\Upaint\Controller;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Mattgold\Upaint\Domain\Model\Session;

class AjaxController extends ActionController {

    /**
     * @param \Mattgold\Upaint\Domain\Repository\InfoitemRepository $infoitemRepository
     * @return void
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
     * @param int $uid
     * @return void
     */
    public function showAction($uid = "")
    {

        if (intval($uid) > 0) {
            $infoitem = $this->infoitemRepository->findByUid(intval($uid));
            $this->view->assign('infoitem', $infoitem);
        }
    }
}