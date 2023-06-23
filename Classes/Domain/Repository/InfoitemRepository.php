<?php

namespace Mattgold\Upaint\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Mattgold\Upaint\Domain\Model\Infoitem;

class InfoitemRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
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

	public function findByCategory($category) { 
    	/* get all questions which belong to category */
    	$query = $this->createQuery();
        $rows = $query->statement(
    		'SELECT uid_local FROM tx_upaint_infoitem_category_mm WHERE uid_foreign = ' . (int)$category
		)->execute(true);

		$idList = array(-1);

		foreach ($rows as $key => $value) {
			$idList[] = $value['uid_local'];
		}

    	$query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
    	$query->matching($query->in('uid', $idList));
    	
     	return $query->execute();
    }

    public function findByCategoryList($categoryList) { 
        /* get all questions which belong to category */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        if ($categoryList) {
            $rows = $query->statement('SELECT i.* FROM tx_upaint_domain_model_infoitem AS i, tx_upaint_infoitem_category_mm AS m WHERE m.uid_foreign IN (' . $categoryList . ') AND i.uid = m.uid_local'
            )->execute(true);
        } else {
            $rows = $query->statement('SELECT * FROM tx_upaint_domain_model_infoitem WHERE deleted = 0 AND hidden = 0'
            )->execute(true);
        }

        $idList = array();

        foreach ($rows as $key => $value) {
            $idList[] = $value['uid'];
        }
        
        return $idList;
    }
}