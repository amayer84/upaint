<?php

namespace Mattgold\Upaint\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Mattgold\Upaint\Domain\Model\Answer;

class AnswerconfigRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    public function initializeObject() {
        $querySettings = new Typo3QuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findByUid($uid) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching($query->equals('uid', $uid));
        
        $result = $query->execute();

        return isset($result[0]) ? $result[0] : null;
    }

	public function findByNode($node_id) { 
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching($query->equals('node', $node_id));

        $query->setOrderings(
            [
                'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
            ]
        );
        
        return $query->execute();
    }

    public function findByNextnode($nextnode) { 
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('nextnode', $nextnode),
                    $query->equals('hidden', 0),
                    $query->equals('deleted', 0)
                ]
            )
        );
        
        return $query->execute();
    }
}