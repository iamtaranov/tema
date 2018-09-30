<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class SurveyController
 * @package AppBundle\Controller
 */
class SurveyController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(Request $request)
    {
        $surveys = $this->getDoctrine()->getRepository(Survey::class)
            ->findBy(
                ['closed' => false],
                ['id' => 'DESC'],
                5
            );

        return $this->render('survey/homepage.html.twig', [
            'surveys' => $surveys
        ]);
    }

    /**
     * @Route("/survey/{key}", name="survey_page", requirements={"key"="\d+"})
     * @Route("/s/{key}", name="survey_page_shortener")
     */
    public function getSurveyByKeyAction(Request $request, $key)
    {
        $keyField = is_numeric($key) ? 'id' : 'identifier';

        $survey = $this->getDoctrine()->getRepository(Survey::class)->findOneBy([$keyField => $key]);

		$form = $this->createFormBuilder();

		$questions = $survey->getQuestions();

        foreach ($questions as $key => $question)
        {
            $question['class'] = $survey->getQuestionClass($question['type']);

            $params = [
                'label' => $question['title'],
                'required' => $question['required'],
            ];

            if ($survey->isSelectableType($question['type']))
            {
                $params = array_merge($params, [
                    'expanded' => true,
                    'multiple' => ($question['type'] == 'checkbox'),
                    'placeholder' => false,
                    'choices' => array_flip($question['options'])
                ]);
            }

			$form->add($key, $question['class'], $params);
        }

        $form = $form->add('submit', SubmitType::class, [
            'label' => 'Отправить',
        ])->getForm();

        //  handling request

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$task = $form->getData();

			$response = new SurveyResponse();

			$response->setSurveyID($survey->getId())
				->setAnswers($task);

			$em = $this->getDoctrine()->getManager();
			$em->persist($response);
			$em->flush();

			return $this->redirectToRoute('survey_success');
		}

        return $this->render('survey/page.html.twig', [
            'survey' => array(
                'title' => $survey->getTitle(),
                'description' => $survey->getDescription(),
                'form' => $form->createView()
            )
        ]);
    }

    /**
     * @Route("/survey/success", name="survey_success")
     */
    public function surveySuccessAction(Request $request)
    {
        return $this->render('other/message.html.twig', [
            'emoji' => array('1f918', '1f44d', '1f44c')[mt_rand(0,2)],
            'title' => 'Отлично!',
            'description' => 'Ваш ответ был записан',
            'showHomepageLink' => true
        ]);
    }

	/**
	 * @Route("/test", name="test")
	 */
    public function test(Request $request)
	{
		$foo = array(
		    'bar' => array()
        );

		$bar = &$foo['bar'][0];

        if (isset($bar) === FALSE)
        {
            $bar = 'val';
        }
        else
        {
            $bar = 'var';
        }

		var_dump($foo);

		exit;
	}
}