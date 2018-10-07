<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyUpdated;
use AppBundle\Form\Type\TextEditorType;
use AppBundle\Form\Type\ChoiceEditorType;

/**
 * Class SurveyEditorController
 * @package AppBundle\Controller
 *
 * @Route("/user/survey")
 */
class SurveyEditorController extends Controller
{
    /**
     * @Route("/create", name="user_survey_create")
     */
    public function userSurveyCreateAction(Request $request)
    {
        $task = $request->request->get('form');

        if (empty($task) !== TRUE)
        {
        	$survey = new Survey();

			$survey->setAuthor($this->getUser()->getId())
				->setTitle($task['title'])
				->setDescription($task['description'])
				->setPublic($task['public'])
				->eraseQuestions();

        	$questions = $task['items'];

        	foreach ($questions as $question)
			{
				$params = array();

				if ($survey->isSelectableType($question['type']))
				{
					$params = array_merge($params, [
						'options' => $question['options']
					]);
				}

				$survey->addQuestion(
					$question['title'],
					$question['type'],
					isset($question['required']),
					$params
				);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($survey);
			$em->flush();
        }

		$form = $this->createFormHeader();

		return $this->render('user/survey/editor.html.twig', [
			'form' => array(
				'main' => $form->createView()
			)
		]);
    }

	/**
	 * @Route("/edit/{key}", name="user_survey_edit")
	 */
	public function userSurveyEditAction(Request $request, $key)
	{
		$survey = $this->getDoctrine()->getRepository(Survey::class)->findOneBy(['id' => $key]);

		$questions = $survey->getQuestions();

		$task = $request->request->get('form');

		if (empty($task) === false)
		{
			$survey->setTitle($task['title'])
				->setDescription($task['description'])
				->setPublic($task['public'])
				->eraseQuestions();

			$existedQuestions = $questions;

			$questions = $task['items'];

			foreach ($questions as $key => $question)
			{
				$data = array();

				if ($existed = isset($existedQuestions[$key]))
				{
					if ($question['type'] !== $existedQuestions[$key]['type'])
					{
						continue;
					}

					$data['key'] = $key;
				}

				$key = $survey->addQuestion(
					$question['title'],
					$question['type'],
					isset($question['required']),
					$data
				);

				if ($survey->isSelectableType($question['type']))
				{
					foreach ($question['options'] as $identifier => $option)
					{
						if (isset($existedQuestions[$key]['options'][$identifier]))
						{
							$survey->updateSelectableOption($key, [$identifier => $option]);

							unset($existedQuestions[$key]['options'][$identifier]);
						}
						else
						{
							$survey->addOptionToSelectable($key, $option);
						}
					}
				}

				if ($existed && empty($existedQuestions[$key]['options']))
				{
					unset($existedQuestions[$key]);
				}
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($survey);

			if (count($existedQuestions) > 0)
			{
				$em->persist((new SurveyUpdated())
					->setSurveyID($survey->getId())
					->setErased($existedQuestions));
			}

			$em->flush();

			return $this->redirectToRoute('survey_page_shortener', [
				'key' => $survey->getIdentifier()
			]);
		}

		$itemsForm = $this->createFormBuilder(null, [
			'csrf_protection' => false
		]);

		foreach ($questions as $key => $question)
		{
			$params = [
				'label' => $survey->getEditorTitle($question['type']),
				'data' => $question['title'],
				'type' => $question['type'],
				'required' => $question['required']
			];

			if ($survey->isSelectableType($question['type']))
			{
				$params = array_merge($params, [
					'expanded' => true,
					'choices' => $question['options'],
					'placeholder' => false
				]);
			}

			$itemsForm->add($key, $survey->getEditorClass($question['type']), $params);
		}

		return $this->render('user/survey/editor.html.twig', [
			'form' => array(
				'main' => $this->createFormHeader([
					'title' => $survey->getTitle(),
					'description' => $survey->getDescription(),
					'public' => $survey->isPublic()
				])->createView(),
				'items' => $itemsForm->getForm()->createView()
			)
		]);
	}

	/**
	 * @param array $data
	 * @return \Symfony\Component\Form\FormInterface
	 */
	private function createFormHeader($data = array(
		'title' => '',
		'description' => '',
		'public' => true
	))
	{
		return $this->createFormBuilder()
			->add('title', TextType::class, [
				'label' => 'Заголовок',
				'data' => $data['title']
			])
			->add('description', TextareaType::class, [
				'label' => 'Краткое описание',
				'data' => $data['description']
			])
			->add('public', ChoiceType::class, [
				'label' => 'Тип опроса',
				'expanded' => true,
				'choices' => array(
					'Открытый (будет доступен на главной и по номеру опроса)' => true,
					'Закрытый (будет доступен только по уникальной ссылке)' => false
				),
				'data' => $data['public']
			])
			->getForm();
	}
}