<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Survey;
use AppBundle\Form\Type\ChoiceEditorItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TemplateController
 * @package ApiBundle\Controller
 *
 * @Route("/template")
 */
class TemplateController extends Controller
{
    /**
     * @Route("/editor/{type}", name="api_template_get")
     */
    public function getTemplateAction($type)
    {
        if (Survey::isAllowedEditorTypeString($type) === FALSE)
        {
            return new JsonResponse([
                'messages' => 'Template type "'. $type .'" search not found'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $form = $this->createFormBuilder(null, [
            'csrf_protection' => false
        ]);

        $params = array(
            'type' => $type
        );

        $class = Survey::getEditorClass($params['type']);

        switch ($class)
        {
            case Survey::TYPE_EDITOR_SELECTABLE_ITEM_CLASS:

                 $params = array_merge($params, [
                    'label' => false,
                     'data' => ''
                 ]);

                 unset($params['type']);

                break;

            default:

                $params = array_merge($params, [
                    'label' => Survey::getEditorTitle($type)
                ]);

                break;
        }

        $identifier = empty($_GET['identifier']) ? Survey::generateKey() : $_GET['identifier'];

        $form->add($identifier, $class, $params);

        return new JsonResponse([
            'template' => $this->render('api/template/editor.html.twig', [
                'form' => $form->getForm()->createView()
            ])->getContent()
        ]);
    }
}