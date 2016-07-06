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

use BrainStrategist\KernelBundle\Entity\Project;


class ProjectForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Name of your Project'
                ))            ->add('picture', FileType::class,
                array(
                    'label' => 'Cover (Jpeg file)',
                    'data_class' => null,
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
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
            'data_class' => 'BrainStrategistProjectBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BrainStrategistProjectBundle_Project';
    }
}
