<?php

namespace AppBundle\Entity;

use AppBundle\Form\Type\ChoiceEditorItemType;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Form\Type\TextEditorType;
use AppBundle\Form\Type\ChoiceEditorType;

/**
 * @ORM\Entity
 * @ORM\Table(name="survey")
 */
class Survey
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $questions;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $closed;

    /**
     * @ORM\Column(type="date")
     */
    private $published;

	/**
	 * Const
	 */
    const TYPE_QUESTION_TEXT_SHORT_CLASS = TextType::class;
    const TYPE_QUESTION_TEXT_LONG_CLASS = TextareaType::class;
    const TYPE_EDITOR_TEXT_CLASS = TextEditorType::class;
	const TYPE_QUESTION_TEXT_SHORT_STRING = 'text';
    const TYPE_QUESTION_TEXT_LONG_STRING = 'textarea';

	const TYPE_QUESTION_SELECTABLE_CLASS = ChoiceType::class;
	const TYPE_EDITOR_SELECTABLE_CLASS = ChoiceEditorType::class;
	const TYPE_QUESTION_SELECTABLE_SINGLE_STRING = 'radio';
	const TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING = 'checkbox';
	const TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING = 'select';

	const TYPE_EDITOR_SELECTABLE_ITEM_CLASS = ChoiceEditorItemType::class;
    const TYPE_EDITOR_SELECTABLE_ITEM_STRING = 'selectable_item';


    /**
     * User constructor
     */
    public function __construct()
    {
        $this->identifier = $this->generateKey();
        $this->questions = array();
        $this->closed = 0;
        $this->published = new \DateTime();
    }

    /**
     * getters
     */

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return array
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @return boolean
     */
    public function isClosed()
    {
        return $this->closed;
    }

	/**
	 * @return \DateTime
	 */
    public function getPublished()
    {
        return $this->published;
    }

	/**
     * DON'T USE THIS METHOD
     *
	 * @return int
	 */
    public function getTimestamp()
	{
		return $this->published->getTimestamp();
	}

    /**
     * setters
     */

    /**
     * @param string $title
     * @return object $this
     */
    public function setTitle($title)
    {
        $this->title = trim($title);

        return $this;
    }

    /**
     * @param string $description
     * @return object $this
     */
    public function setDescription($description)
    {
        $this->description = trim($description);

        return $this;
    }

    /**
     * @param string $author
     * @return object $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @param string $questions
     * @return object $this
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * @param boolean $public
     * @return object $this
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @param boolean $closed
     * @return object $this
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * other
     */

	/**
	 * @param $type
	 * @return bool
	 */
    public static function isTextType($type)
	{
        $type = strtolower($type);

		return in_array($type, [
			self::TYPE_QUESTION_TEXT_SHORT_STRING,
			self::TYPE_QUESTION_TEXT_LONG_STRING
		])  || is_a($type, self::TYPE_QUESTION_TEXT_SHORT_CLASS)
            || is_a($type, self::TYPE_QUESTION_TEXT_LONG_CLASS);
	}

	/**
	 * @param $type
	 * @return bool
	 */
    public static function isSelectableType($type)
	{
	    $type = strtolower($type);

		return in_array($type, [
				self::TYPE_QUESTION_SELECTABLE_SINGLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING
			]) || is_a($type, self::TYPE_EDITOR_SELECTABLE_CLASS);
	}

	/**
	 * @param $type
	 * @return mixed
	 */
	public static function getQuestionClass($type)
	{
		return str_replace(
			[
				self::TYPE_QUESTION_TEXT_LONG_STRING,
				self::TYPE_QUESTION_TEXT_SHORT_STRING,
				self::TYPE_QUESTION_SELECTABLE_SINGLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING
			],
			[
				self::TYPE_QUESTION_TEXT_LONG_CLASS,
				self::TYPE_QUESTION_TEXT_SHORT_CLASS,
				self::TYPE_QUESTION_SELECTABLE_CLASS,
				self::TYPE_QUESTION_SELECTABLE_CLASS,
				self::TYPE_QUESTION_SELECTABLE_CLASS,
			],
			strtolower($type)
		);
	}

	/**
	 * @param $type
	 * @return mixed
	 */
	public static function getEditorClass($type)
	{
		return str_replace(
			[
				self::TYPE_QUESTION_TEXT_LONG_STRING,
				self::TYPE_QUESTION_TEXT_SHORT_STRING,
                self::TYPE_EDITOR_SELECTABLE_ITEM_STRING,
				self::TYPE_QUESTION_SELECTABLE_SINGLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING
			],
			[
				self::TYPE_EDITOR_TEXT_CLASS,
				self::TYPE_EDITOR_TEXT_CLASS,
                self::TYPE_EDITOR_SELECTABLE_ITEM_CLASS,
				self::TYPE_EDITOR_SELECTABLE_CLASS,
				self::TYPE_EDITOR_SELECTABLE_CLASS,
				self::TYPE_EDITOR_SELECTABLE_CLASS
			],
			strtolower($type)
		);
	}

	public static function getEditorTitle($type)
	{
		return str_replace(
			[
				self::TYPE_QUESTION_TEXT_LONG_STRING,
				self::TYPE_QUESTION_TEXT_SHORT_STRING,
				self::TYPE_QUESTION_SELECTABLE_SINGLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING,
				self::TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING,
//				self::TYPE_EDITOR_TEXT_SHORT_CLASS,
//				self::TYPE_EDITOR_TEXT_LONG_CLASS,
//				self::TYPE_EDITOR_SELECTABLE_CLASS
			],
			[
                'Расширенный ответ',
				'Простой ответ',
				'Один из многих',
				'Многие из многих',
				'Выпадающий список',
//				'Простой ответ',
//				'Расширенный ответ',
//				'Ответ по выбору'

			],
			strtolower($type)
		);
	}

	/**
	 * @param $type
	 * @return bool
	 */
	public static function isAllowedEditorTypeString($type)
	{
		return in_array(strtolower($type), [
		    self::TYPE_QUESTION_TEXT_LONG_STRING,
            self::TYPE_QUESTION_TEXT_SHORT_STRING,
            self::TYPE_QUESTION_SELECTABLE_SINGLE_STRING,
            self::TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING,
            self::TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING,
            self::TYPE_EDITOR_SELECTABLE_ITEM_STRING
		]);
	}

	/**
	 * @param $type
	 * @return bool
	 */
	public static function isAllowedEditorTypeClass($type)
	{
		return in_array(strtolower($type), [
            self::TYPE_EDITOR_TEXT_CLASS,
            self::TYPE_QUESTION_SELECTABLE_CLASS,
            self::TYPE_EDITOR_SELECTABLE_ITEM_CLASS
		]);
	}

	/**
	 * @param $type
	 * @return bool
	 */
	public static function isAllowedEditorType($type)
	{
        $type = strtolower($type);

		return in_array($type, [
            self::TYPE_QUESTION_TEXT_LONG_STRING,
            self::TYPE_QUESTION_TEXT_SHORT_STRING,
            self::TYPE_QUESTION_SELECTABLE_SINGLE_STRING,
            self::TYPE_QUESTION_SELECTABLE_MULTIPLE_STRING,
            self::TYPE_QUESTION_SELECTABLE_COLLAPSED_STRING,
            self::TYPE_EDITOR_SELECTABLE_ITEM_STRING
		])  || is_a($type, self::TYPE_EDITOR_TEXT_CLASS)
            || is_a($type, self::TYPE_QUESTION_SELECTABLE_CLASS)
            || is_a($type, self::TYPE_EDITOR_SELECTABLE_ITEM_CLASS);
	}

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateKey()
    {
        return hash('adler32', 'T:' . time() . 'R:' . random_bytes(array(64, 128, 512)[mt_rand(0,2)])); // make char + hash
    }

    /**
     * @return $this
     */
    public function eraseQuestions()
    {
        $this->questions = array();

        return $this;
    }

    /**
     * @param $title
     * @param $type
     * @param $required
     * @param array $params
     * @return mixed|string
     * @throws \Exception
     */
    public function addQuestion($title, $type, $required, array $params)
    {
        $key = empty($params['key']) ? $this->generateKey() : $params['key'];

        $this->questions[$key] = [
			'title' => trim($title),
			'type' => $type,
			'required' => $required
		];

		if ($this->isSelectableType($type) && isset($params['options']))
		{
			$this->addOptionsToSelectable($key, array_values($params['options']));
		}

		return $key;
    }

    /**
     * @param $key
     * @param $option
     * @return mixed
     * @throws \Exception
     */
    public function addOptionToSelectable($key, $option)
	{
		// check type
		$question = $this->getQuestion($key);
		// check is set

		if (isset($question['options']) == FALSE)
		{
			$question['options'] = array();
		}

		$question['options'][$this->generateKey()] = trim($option);

		$this->questions[$key] = $question;

		return $key;
	}

    /**
     * @param $key
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
	public function addOptionsToSelectable($key, array $options)
	{
		foreach ($options as $option)
		{
			$this->addOptionToSelectable($key, $option);
		}

		return $key;
	}

	public function updateSelectableOption($key, array $option)
	{
		$question = $this->getQuestion($key);

		if (isset($question['options']) == FALSE)
		{
			$question['options'] = array();
		}

		foreach ($option as &$value)
        {
            $value = trim($value);
        }

		$question['options'] = array_merge($question['options'], $option);

		$this->questions[$key] = $question;

		return $key;
	}

	public function updateSelectableOptions($key, array $options)
	{
		foreach ($options as $option)
		{
			$this->updateSelectableOption($key, $option);
		}

		return $key;
	}

    /**
     * @param $key
     * @return array
     */
    public function getQuestion($key)
    {
        return $this->questions[$key];
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getQuestionType($key)
    {
        $question = $this->getQuestion($key);

        return empty($question) ? null : $question['type'];
    }


    /**
     * @param $key
     * @return array|null
     */
    public function getQuestionOptions($key)
    {
        $question = $this->getQuestion($key);

        return isset($question['options']) ? $question['options'] : null;
    }

    /**
     * @param $questionKey
     * @param $optionKey
     * @return array|null
     */
    public function getQuestionOption($questionKey, $optionKey)
    {
        $options = $this->getQuestionOptions($questionKey);

        if (isset($options) === FALSE)
        {
            return null;
        }

        return isset($options[$optionKey]) ? $options[$optionKey] : null;
    }
}