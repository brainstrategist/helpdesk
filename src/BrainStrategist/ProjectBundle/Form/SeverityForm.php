<?php

namespace BrainStrategist\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use BrainStrategist\ProjectBundle\Entity\Severity;


class SeverityForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $datetime = new \DateTime();
        $newDate = $datetime->createFromFormat('d/m/Y h:i:s', '20/04/1981 00:00:00');

        $builder
            ->add('name',TextType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Name of your severity'
                ))
            ->add('level',ChoiceType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices'  => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                    ),
                    'label' => 'Level of severity'
                ))
            ->add('responseTimeDays',ChoiceType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices'  => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                    ),
                    'label' => 'Number of day to response'
                ))
            ->add('responseTime',TimeType::class,
                array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'data' => $newDate,
                    'label' => 'Number of hours to response'
                ))
            ->add('resolutionTimeDays',ChoiceType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'choices'  => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                    ),
                    'label' => 'Number of day to resolve'
                ))
            ->add('resolutionTime',TimeType::class,
                array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'data' => $newDate,
                    'label' => 'Number of hours to resolve'
                ))
            ->add('description',TextareaType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Description of your severity'
                ))
            ->add('isActive', CheckboxType::class,
                array(
                    'required' => false,
                    'label' => '',
                    'label_attr' => array(
                        'for' => 'cmn-toggle-1'
                    ),
                    'attr' => array(
                        'class' => 'cmn-toggle cmn-toggle-round',
                        'id' => "cmn-toggle-1"
                    )
                ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrainStrategist\ProjectBundle\Entity\Severity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BrainStrategistProjectBundle_Severity';
    }
}
