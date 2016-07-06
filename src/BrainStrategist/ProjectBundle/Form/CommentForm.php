<?php

namespace BrainStrategist\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use BrainStrategist\ProjectBundle\Entity\Ticket_Comment;


class CommentForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contentComment',TextareaType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control tinymce',
                    ),
                    'required' => false,
                    'label' => 'Description'
                ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrainStrategistProjectBundle\Entity\Ticket_Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BrainStrategistProjectBundle_Ticket_Comment';
    }
}
