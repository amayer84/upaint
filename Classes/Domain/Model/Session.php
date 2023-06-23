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
 * Session
 */
class Session extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * token
     *
     * @var string
     */
    protected $token = 0;

    /**
     * Returns the token
     *
     * @return string $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the token
     *
     * @param string $token
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * infoitems
     *
     * @var string
     */
    protected $infoitems = 0;

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
}