<?php

namespace Mattgold\Upaint\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Mattgold\Upaint\Domain\Model\Node;

class NodeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
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

	public function findByPlugin($plugin) { 
    	$query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('parentid', $plugin),
                    $query->equals('hidden', 0),
                    $query->equals('deleted', 0)
                ]
            )
        );

    	$query->setOrderings(
            [
                'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
            ]
        );

     	return $query->execute();
    }
}