<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_homepage")
     */
    public function userHomepageAction(Request $request)
    {
        return new Response('User homepage');
    }

    /**
     * @Route("/survey", name="user_survey")
     */
    public function userSurveyMyAction(Request $request)
    {
		$surveys = $this->getDoctrine()->getRepository(Survey::class)
			->findBy(
				['author' => $this->getUser()->getId()],
				['id' => 'DESC'],
				5
			);

		return $this->render('user/survey/index.html.twig', [
			'surveys' => $surveys
		]);
    }

    /**
	 * @Route("/survey/statistic/{key}", name="user_survey_statistic", requirements={"key"="\d+"})
	 */
    public function userSurveyStatAction($key)
	{
		$doctrine = $this->getDoctrine();
		$survey = $doctrine->getRepository(Survey::class)->findOneBy(['id' => $key]);
		$responses = $doctrine->getRepository(SurveyResponse::class)->findBy(['surveyID' => $key]);

		$statistics = [
		    'general' => [
                'answers' => count($responses),
                'times' => array()
            ],
            'detailed' => array()
        ];

		foreach ($responses as $response)
        {
            $answers = $response->getAnswers();

            $time = $response->getPublished()->format('d.m.y');

            if (isset($statistics['general']['times'][$time]) == FALSE)
            {
                $statistics['general']['times'][$time] = 0;
            }

            $statistics['general']['times'][$time]++;

            foreach ($answers as $id => $answer)
            {
                $question = $survey->getQuestion($id);

                if (empty($question)) continue;

                $statistic = &$statistics['detailed'][$id];

                if (isset($statistic) === FALSE)
                {
                    $statistic = [
                        'title' => $question['title'],
                        'data' => $survey->isSelectableType($survey->getQuestionType($id)) ? array() : 0
                    ];
                }

                if (empty($answer)) continue;

                if (is_array($statistic['data']))
                {
                    if (is_array($answer))
                    {
                        foreach ($answer as $item)
                        {
                            if (count($statistic['data']) == 0)
                            {
                                $options = $survey->getQuestionOptions($id);

                                if (empty($options)) break;

                                foreach ($options as $optionKey => $optionValue)
                                {
                                    $statistic['data'][$optionKey] = [
                                        'title' => $optionValue,
                                        'data' => 0
                                    ];
                                }
                            }

                            $option = $survey->getQuestionOption($id, $item);

                            if (isset($statistic['data'][$item]) && isset($option))
                            {
                                $statistic['data'][$item]['title'] = $option;
                                $statistic['data'][$item]['data']++;
                            }
                        }
                    }
                    else
                    {
                        if (count($statistic['data']) == 0)
                        {
                            $options = $survey->getQuestionOptions($id);

                            if (empty($options)) continue;

                            foreach ($options as $optionKey => $optionValue)
                            {
                                $statistic['data'][$optionKey] = [
                                    'title' => $optionValue,
                                    'data' => 0
                                ];
                            }
                        }

                        $option = $survey->getQuestionOption($id, $answer);

                        if (isset($statistic['data'][$answer]) && isset($option))
                        {
                            $statistic['data'][$answer]['title'] = $option;
                            $statistic['data'][$answer]['data']++;
                        }
                    }
                }
                else
                {
                    $statistic['data']++;
                }
            }
        }

		return $this->render('user/survey/statistic.html.twig', [
			'survey' => [
				'title' => $survey->getTitle(),
				'description' => $survey->getDescription(),
				'statistics' => $statistics
			]
		]);
	}

	/**
	 * @Route("/survey/answers/{key}", name="user_survey_answers", requirements={"key"="\d+"})
	 */
	public function userSurveyAnswersAction($key)
	{
        $doctrine = $this->getDoctrine();
        $survey = $doctrine->getRepository(Survey::class)->findOneBy(['id' => $key]);
        $responses = $doctrine->getRepository(SurveyResponse::class)->findBy(['surveyID' => $key], ['id' => 'DESC']);

        $surveyAnswers = array(
            'general' => array(),
            'items' => array()
        );

        foreach ($responses as $response)
		{
            $answers = $response->getAnswers();

            $surveyAnswer = &$surveyAnswers['items'][$response->getId()];

            foreach ($answers as $id => $answer)
            {
                if (empty($answer)) continue;

                $question = $survey->getQuestion($id);

                if (empty($question)) continue;

                $surveyAnswer[$id] = [
                    'title' => $question['title'],
                    'data' => is_array($answer) ? array() : 'Unkown error'
                ];

                if (is_array($surveyAnswer[$id]['data']))
                {
                    foreach ($answer as $item)
                    {
                        if (empty($item)) continue;

                        $option = $survey->getQuestionOption($id, $item);

                        if (empty($option)) continue;

                        $surveyAnswer[$id]['data'][] = $option;
                    }
                }
                else
                {
                    $option = $survey->getQuestionOption($id, $answer);

                    $surveyAnswer[$id]['data'] = empty($option) ? $answer : $option;
                }
            }
		}

        return $this->render('user/survey/answers.html.twig', [
            'survey' => [
                'title' => $survey->getTitle(),
                'description' => $survey->getDescription(),
                'answers' => $surveyAnswers
            ]
        ]);
	}

    /**
     * @Route("/survey/close/{key}", name="user_survey_close", requirements={"key"="\d+"})
     */
    public function userSurveyCloseAction($key)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->findOneBy(['id' => $key]);

        $survey->setClosed(!$survey->isClosed());

        $em = $this->getDoctrine()->getManager();
        $em->persist($survey);
        $em->flush();

        return $this->redirectToRoute('user_survey');
    }

    /**
     * @Route("/survey/delete/{key}", name="user_survey_delete", requirements={"key"="\d+"})
     */
    public function userSurveyDeleteAction($key) { }
}