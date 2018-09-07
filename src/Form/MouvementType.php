<?php

namespace App\Form;

use App\Entity\Mouvement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MouvementType
 * @package App\Form
 */
class MouvementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('traite', CheckboxType::class, [
                'required' => false
            ])
            ->add('libelle', TextType::class, [
                'attr' => ['placeholder' => 'Libellé', 'style' => 'width:350px'],

            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                //'attr' => ['style' => 'width:350px']
            ])
            ->add('credit', NumberType::class, [
                'required' => false,
                //'attr' => ['style' => 'width:100px']
            ])
            ->add('debit', NumberType::class, [
                'required' => false,
                //'attr' => ['style' => 'width:100px']
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mouvement::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_compte';
    }
}
