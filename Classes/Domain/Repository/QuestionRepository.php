<?php

namespace Mattgold\Upaint\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Mattgold\Upaint\Domain\Model\Question;

class QuestionRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	public function initializeObject() {
        $querySettings = new Typo3QuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findByUid($uid) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->getQuerySettings()->setRespectSysLanguage(FALSE);
        
        $query->matching($query->equals('uid', $uid));
        
        $result = $query->execute();

        return isset($result[0]) ? $result[0] : null;
    }

    public function findByCategory($category) { 
    	/* get all questions which belong to category */
    	$query = $this->createQuery();
        $rows = $query->statement(
    		'SELECT uid_local FROM tx_upaint_question_category_mm WHERE uid_foreign = ' . (int)$category
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

    public function findItems($lang = null) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->getQuerySettings()->setRespectSysLanguage(FALSE);

        if (!is_null($lang) && is_int($lang)) {
            $query->matching($query->equals('sys_language_uid', $lang));
        }
        
        return $query->execute();
    }
}