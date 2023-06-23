<?php

namespace Mattgold\Upaint\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Mattgold\Upaint\Domain\Model\Answer;

class AnswerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
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

	public function findByQuestion($question) { 
    	$query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching($query->equals('question', $question));
    	
     	return $query->execute();
    }
}