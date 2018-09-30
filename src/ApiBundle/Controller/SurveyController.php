<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyResponse;

/**
 * Class MainController
 * @package ApiBundle\Controller
 *
 * @Route("/survey")
 */
class SurveyController extends Controller
{
    /**
     * @Route("/", name="api_survey_homepage")
	 *
	 * @return JsonResponse
     */
    public function surveyHomepageAction()
    {
        $offset = (empty($_GET['offset']) !== TRUE && is_int($_GET['offset'])) ? $_GET['offset'] : 0;

        $surveys = $this->getDoctrine()->getRepository(Survey::class)
            ->findBy(
                ['closed' => false],
                ['id' => 'DESC'],
                5,
                $offset
            );

        $response = array();

        foreach ($surveys as $survey)
        {
            $response[] = $this->getSurveyDataArrayByObject($survey);
        }

        return new JsonResponse([
            'surveys' => $response
        ]);
    }

	/**
	 * @Route("/{key}", name="api_survey_get_by_key")
	 *
	 * @param $key
	 * @return JsonResponse
	 */
    public function getSurveyByKeyAction($key)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->findOneBy(['identifier' => $key]);

        $response = array_merge($this->getSurveyDataArrayByObject($survey), [
            'questions' => array()
        ]);

        $questions = $survey->getQuestions();

        foreach ($questions as $id => $question)
        {
            if (empty($question['options']) != TRUE)
            {
                $options = array();

                foreach ($question['options'] as $key => &$value)
                {
                    $options[] = [
                        'identifier' => $key,
                        'value' => $value
                    ];
                }

                $question['options'] = $options;
            }

            $response['questions'][] = array_merge([
                'identifier' => $id
            ], $question);
        }

        return new JsonResponse([
            'survey' => $response
        ]);
    }

	/**
	 * @Route("/response/{key}", name="api_survey_post_new_response")
	 *
	 * @param $key
	 * @return JsonResponse
	 */
    public function postNewSurveyResponseAction($key)
	{
	    $data = $_POST['content'];

	    if (empty($data))
        {
            return new JsonResponse([
                'error' => [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'POST data is incorrect'
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        $id = $this->getDoctrine()->getRepository(Survey::class)->findOneBy(['identifier' => $key])->getId();

		$response = new SurveyResponse();

	    $response
            ->setSurveyID($id)
            ->setAnswers($data);

        $em = $this->getDoctrine()->getManager();
        $em->persist($response);
        $em->flush();

		return new JsonResponse([
		    'response' => [
		        'id' => $response->getId(),
                'time' => $response->getPublished()->getTimestamp()
            ]
        ], Response::HTTP_OK);
	}

    /**
     * @param $survey
     * @return array
     */
    private function getSurveyDataArrayByObject($survey)
    {
        return array(
            'identifier' => $survey->getIdentifier(),
            'title' => $survey->getTitle(),
            'description' => $survey->getDescription(),
            'published' => $survey->getPublished()->getTimestamp()
        );
    }
}