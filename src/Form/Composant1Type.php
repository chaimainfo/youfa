<?php

namespace App\Form;

use App\Entity\Composant;
use App\Entity\DroitAccee;
use App\Entity\Tableau;
use App\Entity\TypeComposant;
use App\Entity\TypeTab;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Composant1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            // ->add('contenu')
            // ->add('enabled')
            ->add('typesTab', EntityType::class, [
                'class' => TypeTab::class,
                'choice_label' => 'type',
                'multiple' => true,
                'expanded' => true,
                'label' => 'tableau:',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Composant::class,
        ]);
    }
}
