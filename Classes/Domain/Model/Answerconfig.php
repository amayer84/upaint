<?php
namespace Mattgold\Upaint\Domain\Model;

/***
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * Answer
 */
class Answerconfig extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * infoitems
     *
     * @var string
     */
    protected $infoitems = '';

    /**
     * answer
     *
     * @var int
     */
    protected $answer = 0;

    /**
     * nextnode
     *
     * @var int
     */
    protected $nextnode = 0;

    /**
     * node
     *
     * @var int
     */
    protected $node = 0;

    /**
     * Returns the infoitems
     *
     * @return string $infoitems
     */
    public function getInfoitems()
    {
        return $this->infoitems;
    }

    /**
     * Sets the infoitems
     *
     * @param string $infoitems
     * @return void
     */
    public function setInfoitems($infoitems)
    {
        $this->infoitems = $infoitems;
    }

    /**
     * Returns the answer
     *
     * @return int $answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Sets the answer
     *
     * @param int $answer
     * @return void
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Returns the nextnode
     *
     * @return int $nextnode
     */
    public function getNextnode()
    {
        return $this->nextnode;
    }

    /**
     * Sets the nextnode
     *
     * @param int $nextnode
     * @return void
     */
    public function setNextnode($nextnode)
    {
        $this->nextnode = $nextnode;
    }

    /**
     * Returns the node
     *
     * @return int $node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Sets the node
     *
     * @param int $node
     * @return void
     */
    public function setNode($node)
    {
        $this->node = $node;
    }
}
