<?php

namespace BrainStrategist\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use BrainStrategist\KernelBundle\Entity\Ticket;


class TicketForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                        'class' => 'form-control'
                    ),
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
                'label' => 'Browser'
            ))
            ->add('severity', ChoiceType::class, array(
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
                    '10' => '10'
                ),
                'label' => 'Severity'
            ))
            ->add('priority', ChoiceType::class, array(
                'choices'  => array(
                    'Low' => '1',
                    'Medium' => '2',
                    'Urgent' => '3',
                    'Critical' => '4'
                ),
                'label' => 'Priority'
            ))
            ->add('picture', FileType::class,
                array(
                    'label' => 'Screeshoot (Jpeg file)',
                    'data_class' => null,
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BrainStrategistProjectBundle\Entity\Ticket'
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
