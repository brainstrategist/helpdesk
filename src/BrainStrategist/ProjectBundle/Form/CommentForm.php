<?php

namespace BrainStrategist\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use BrainStrategist\ProjectBundle\Form\PictureType;

use BrainStrategist\ProjectBundle\Entity\Ticket_Comment;
use BrainStrategist\ProjectBundle\Repository\Ticket_StatusRepository;

class CommentForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ticket_status', EntityType::class, array(
                'class' => 'BrainStrategistProjectBundle:Ticket_status',
                'choice_label' => function ($status) {
                    return $status->getName();
                },
                'query_builder' => function (EntityRepository $er)  use ($options)
                {
                    return $er->findAllByProjectId($options['attr']['project_id']);
                },
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('contentComment',TextareaType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control tinymce',
                    ),
                    'required' => false,
                    'label' => 'Description'
                ))
            ->add('pictures', CollectionType::class , array(
                'entry_type' => PictureType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'label' => 'Add sceenshoots'

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
