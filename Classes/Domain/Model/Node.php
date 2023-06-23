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
 * Node
 */
class Node extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * hint
     *
     * @var string
     */
    protected $hint = '';

    /**
     * Returns the hint
     *
     * @return string $hint
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Sets the hint
     *
     * @param string $hint
     * @return void
     */
    public function setHint($hint)
    {
        $this->hint = $hint;
    }
    
    /**
     * question
     *
     * @var int
     */
    protected $question = 0;

    /**
     * Returns the question
     *
     * @return int $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question
     *
     * @param int $question
     * @return void
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }
}
