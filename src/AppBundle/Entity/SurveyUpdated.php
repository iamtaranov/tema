<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="surveyUpdated")
 */
class SurveyUpdated
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
	private $erased;

	/**
	 * @ORM\Column(type="date")
	 */
	private $edited;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $completed;

	/**
	 * User constructor
	 */
	public function __construct()
	{
		$this->edited = new \DateTime();
		$this->completed = false;
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
	public function getErased()
	{
		return $this->erased;
	}

	/**
	 * @return mixed
	 */
	public function getEdited()
	{
		return $this->edited;
	}

	/**
	 * @return bool
	 */
	public function isCompleted()
	{
		return $this->completed;
	}

	/**
	 * setters
	 */

	/**
	 * @param $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

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
	 * @param $erased
	 * @return $this
	 */
	public function setErased($erased)
	{
		$this->erased = $erased;

		return $this;
	}

	/**
	 * @param bool $completed
	 * @return $this
	 */
	public function setCompleted($completed)
	{
		$this->completed = $completed;

		return $this;
	}
}