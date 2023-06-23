<?php
namespace Mattgold\Upaint\Domain\Model;

/***
 *
 * This file is part of the "upaint" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * Infoitem
 */
class Infoitem extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * longDescription
     *
     * @var string
     */
    protected $longDescription = '';

    /**
     * @var \DateTime
     */
    protected $datetime;

    /**
     * duration
     *
     * @var int
     */
    protected $duration = 0;

    /**
     * duration_unit
     *
     * @var string
     */
    protected $durationUnit = '';

    /**
     * category
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mattgold\Upaint\Domain\Model\Category>
     */
    protected $category = null;

    /**
     * imageposB
     *
     * @var string
     */
    protected $imageposB = '';

    /**
     * Fal media items
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $falMediaC = null;

    /**
     * Fal media items
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $falMediaD = null;

    /**
     * show_print_c
     *
     * @var int
     */
    protected $showPrintC;

    /**
     * show_print_d
     *
     * @var int
     */
    protected $showPrintD;

    /**
     * mainTitle
     *
     * @var string
     */
    protected $mainTitle = '';

    /**
     * button_title
     *
     * @var string
     */
    protected $buttonTitle = '';

    /**
     * button_link
     *
     * @var string
     */
    protected $buttonLink = '';

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->category = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the longDescription
     *
     * @return string $longDescription
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Sets the longDescription
     *
     * @param string $longDescription
     * @return void
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set date time
     *
     * @param \DateTime $datetime datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * Returns the duration
     *
     * @return int $duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Sets the duration
     *
     * @param int $duration
     * @return void
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Returns the durationUnit
     *
     * @return string $durationUnit
     */
    public function getDurationUnit()
    {
        return $this->durationUnit;
    }

    /**
     * Sets the durationUnit
     *
     * @param string $durationUnit
     * @return void
     */
    public function setDurationUnit($durationUnit)
    {
        $this->durationUnit = $durationUnit;
    }

    /**
     * Adds a Category
     *
     * @param \Mattgold\Upaint\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\Mattgold\Upaint\Domain\Model\Category $category)
    {
        $this->category->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \Mattgold\Upaint\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(\Mattgold\Upaint\Domain\Model\Category $categoryToRemove)
    {
        $this->category->detach($categoryToRemove);
    }

    /**
     * Returns the category
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mattgold\Upaint\Domain\Model\Category> $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mattgold\Upaint\Domain\Model\Category> $category
     * @return void
     */
    public function setCategory(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the imageposB
     *
     * @return string $imageposB
     */
    public function getImageposB()
    {
        return $this->imageposB;
    }

    /**
     * Sets the imageposB
     *
     * @param string $imageposB
     * @return void
     */
    public function setImageposB($imageposB)
    {
        $this->imageposB = $imageposB;
    }

    /**
     * Returns the falMediaC
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $falMediaC
     */
    public function getFalMediaC()
    {
        return $this->falMediaC;
    }

    /**
     * Sets the falMediaC
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $falMediaC
     * @return void
     */
    public function setFalMediaC(\TYPO3\CMS\Extbase\Domain\Model\FileReference $falMediaC)
    {
        $this->falMediaC = $falMediaC;
    }

    /**
     * Returns the falMediaD
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $falMediaD
     */
    public function getFalMediaD()
    {
        return $this->falMediaD;
    }

    /**
     * Sets the falMediaD
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $falMediaD
     * @return void
     */
    public function setFalMediaD(\TYPO3\CMS\Extbase\Domain\Model\FileReference $falMediaD)
    {
        $this->falMediaD = $falMediaD;
    }

    /**
     * @return int
     */
    public function getShowPrintC()
    {
        return $this->showPrintC;
    }

    /**
     * @param int $showPrintC
     */
    public function setShowPrintC($showPrintC)
    {
        $this->showPrintC = $showPrintC;
    }

    /**
     * @return int
     */
    public function getShowPrintD()
    {
        return $this->showPrintD;
    }

    /**
     * @param int $showPrintD
     */
    public function setShowPrintD($showPrintD)
    {
        $this->showPrintD = $showPrintD;
    }

    /**
     * Returns the mainTitle
     *
     * @return string $mainTitle
     */
    public function getMainTitle()
    {
        return $this->mainTitle;
    }

    /**
     * Sets the mainTitle
     *
     * @param string $mainTitle
     * @return void
     */
    public function setMainTitle($mainTitle)
    {
        $this->mainTitle = $mainTitle;
    }

    /**
     * Returns the buttonTitle
     *
     * @return string $buttonTitle
     */
    public function getButtonTitle()
    {
        return $this->buttonTitle;
    }

    /**
     * Sets the buttonTitle
     *
     * @param string $buttonTitle
     * @return void
     */
    public function setButtonTitle($buttonTitle)
    {
        $this->buttonTitle = $buttonTitle;
    }

    /**
     * Returns the buttonLink
     *
     * @return string $buttonLink
     */
    public function getButtonLink()
    {
        return $this->buttonLink;
    }

    /**
     * Sets the buttonLink
     *
     * @param string $buttonLink
     * @return void
     */
    public function setButtonLink($buttonLink)
    {
        $this->buttonLink = $buttonLink;
    }
}
