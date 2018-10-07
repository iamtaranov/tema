<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyResponse;

use Endroid\QrCode\QrCode;

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
     */
    public function surveyHomepageAction()
    {
        $offset = (empty($_GET['offset']) !== true && is_int($_GET['offset'])) ? $_GET['offset'] : 0;

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
     * @Route("/qr/{key}", name="api_survey_get_qr_code")
     */
    public function getSurveyQrCode($key)
    {
        $qrCode = new QrCode('https://tema.co.ua/s/' . $key);
        $qrCode->setMargin(0);

        if (!empty($_GET['size']))
        {
            if (is_numeric($_GET['size']))
            {
                $size = intval($_GET['size']);

                if ($size >= 64 && $size <= 512)
                {
                    $qrCode->setSize($size);
                }
            }
        }

        $response = new Response($qrCode->writeString());
        $response->headers->set('Content-Type', $qrCode->getContentType());

        return $response;
    }

	/**
	 * @Route("/{key}", name="api_survey_get_by_key")
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
            if (empty($question['options']) != true)
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