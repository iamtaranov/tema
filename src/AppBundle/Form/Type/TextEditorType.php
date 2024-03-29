<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TextEditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'text'
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		parent::buildView($view, $form, $options); // TODO: Change the autogenerated stub

		$items = array(
			'type'
		);

		foreach ($items as $key)
		{
			$property = null;

			if (isset($options[$key]))
			{
				$property = $options[$key];
			}

			$view->vars = array_merge($view->vars, [
				$key => $property
			]);
		}
	}
}