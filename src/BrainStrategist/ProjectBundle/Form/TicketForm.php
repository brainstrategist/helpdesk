<?php

namespace BrainStrategist\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use BrainStrategist\ProjectBundle\Form\PictureType;

use BrainStrategist\ProjectBundle\Entity\Ticket;
use BrainStrategist\ProjectBundle\Repository\SeverityRepository;

class TicketForm extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->option_id = $options['attr']['project_id'];

        $builder
            ->add('summary',TextType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Summary'
                ))
            ->add('description',TextareaType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control tinymce',
                    ),
                    'required' => false,
                    'label' => 'Description'
                ))
            ->add('browser', ChoiceType::class, array(
                'choices'  => array(
                    'IE' => '1',
                    'Firefox' => '2',
                    'Chrome' => '3',
                    'Safari' => '4',
                    'Opera' => '5',
                    'Other' => '6',
                ),
                'label' => 'Browser',
                'attr' => array(
                    'class' => 'form-control ',
                ),
            ))->add('priority', EntityType::class, array(
                'class' => 'BrainStrategistProjectBundle:Ticket_Priority',
                'choice_label' => function ($priority) {
                    return $priority->getName();
                },
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->findAllByProjectId($this->option_id);
                },
                'attr' => array(
                    'class' => 'form-control',
                ),
                'label' => 'Priority'
            ))
            ->add('severity', EntityType::class, array(
                'class' => 'BrainStrategistProjectBundle:Severity',
                'choice_label' => function ($severity) {
                    return $severity->getLevel().' - '.$severity->getName();
                },
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->findAllByProjectId($this->option_id);
                },
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('category', EntityType::class, array(
                'class' => 'BrainStrategistProjectBundle:Ticket_Category',
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('assigned_users', EntityType::class, array(
                'class' => 'BrainStrategistKernelBundle:User',
                'multiple'=>true,
                'expanded'=>true,
                'choice_label' => function ($user) {
                    return $user;
                },
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->getUsersByProjects(array('projectID' => $this->option_id, 'limit' =>  100));
                }
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
            'data_class' => 'BrainStrategist\ProjectBundle\Entity\Ticket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BrainStrategistProjectBundle_Ticket';
    }
}
