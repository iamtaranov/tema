<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="surveyResponse")
 */
class SurveyResponse
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $surveyID;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $answers;

    /**
     * @ORM\Column(type="date")
     */
    private $published;

    /**
     * User constructor
     */
    public function __construct()
    {
        $this->published = new \DateTime();
    }

    /**
     * getters
     */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSurveyID()
    {
        return $this->surveyID;
    }

    /**
     * @return array
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * setters
     */

    /**
     * @param $surveyID
     * @return $this
     */
    public function setSurveyID($surveyID)
    {
        $this->surveyID = $surveyID;

        return $this;
    }

    /**
     * @param $answers
     * @return $this
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }
}