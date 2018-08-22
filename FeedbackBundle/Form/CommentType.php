<?php

namespace Nines\FeedbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CommentType form.
 */
class CommentType extends AbstractType
{
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder->add('fullname', null, array(
            'label' => 'Fullname',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('email', null, array(
            'label' => 'Email',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('followUp', ChoiceType::class, array(
            'label' => 'Follow Up',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
                ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),

        ));
                $builder->add('entity', null, array(
            'label' => 'Entity',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('content', null, array(
            'label' => 'Content',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                        $builder->add('status');
        
    }
    
    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Nines\FeedbackBundle\Entity\Comment'
        ));
    }

}
