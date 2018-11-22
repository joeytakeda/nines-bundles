<?php

namespace Nines\BlogBundle\Form;

use Nines\BlogBundle\Entity\PageCategory;
use Nines\UtilBundle\Form\TermType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PostCategoryType form.
 */
class PageCategoryType extends TermType
{

    /**
     * Define options for the form.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PageCategory::class,
        ));
    }

}
