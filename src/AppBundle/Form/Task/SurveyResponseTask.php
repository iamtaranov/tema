<?php

namespace AppBundle\Form\Task;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyResponse;

class SurveyResponseTask extends AbstractType
{
    /**
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Логин'
            ]);

//        $survey = new Survey();
//
//        $questions = $options['surveyQuestions'];
//
//        foreach ($questions as $question)
//        {
//            $question['class'] = $survey->getTypeClassByTypeString($question['type']);
//
//            $questionParams = [
//                'label' => $question['title'],
//                'required' => $question['required']
//            ];
//
//            switch ($question['class'])
//            {
//                case ChoiceType::class:
//
//                    $questionParams = array_merge($questionParams, [
//                        'expanded' => true,
//                        'multiple' => ($question['type'] == 'checkbox'),
//                        'choices' => $question['options']
//                    ]);
//
//                    break;
//            }
//
//            $builder->add($question['name'], $question['class'], $questionParams);
//        }
//
//        $builder->add('submit', SubmitType::class, [
//            'label' => 'Отправить',
//        ]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyResponse::class
        ]);

        $resolver->setRequired('surveyQuestions');
    }
}