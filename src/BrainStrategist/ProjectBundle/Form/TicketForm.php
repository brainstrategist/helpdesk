<?php

namespace BrainStrategist\ProjectBundle\Form;

use BrainStrategist\ProjectBundle\Repository\SeverityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
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
                        'class' => 'form-control tinymce'
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
            ->add('priority', ChoiceType::class, array(
                'choices'  => array(
                    'TODO' => '1',
                    'This week' => '2',
                    'ASAP' => '3',
                    'Now' => '4'
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
                },
            ))
            ->add('category', EntityType::class, array(
                'class' => 'BrainStrategistProjectBundle:Ticket_Category',
                'choice_label' => function ($category) {
                    return $category->getName();
                }
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
