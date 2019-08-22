<?php

namespace Nines\UtilBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class TermType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('label', TextType::class, array(
            'label' => 'Label',
            'attr' => array(
                'help_block' => 'A human-readable name.'
            ),
        ));
        $builder->add('description', TextareaType::class, array(
            'required' => false,
            'attr' => array(
                'class' => 'tinymce',
            )
        ));
    }

}
