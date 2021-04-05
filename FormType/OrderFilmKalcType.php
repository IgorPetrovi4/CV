<?php

namespace App\Form;

use App\Entity\FilmLamination;
use App\Entity\FilmList;
use App\Entity\FilmResolution;
use App\Entity\FilmUfColorLayer;
use App\Entity\OrderFilmKalc;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class OrderFilmKalcType extends AbstractType
{

    private $security;
    public function __construct( Security $security)
    {
        $this->security = $security;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('film', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value'=> function(?FilmList $filmList) {
                    return $filmList ? $filmList->getId() : '1';},
                'attr' => ['class' => 'my-film row form-check-film form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => FilmList::class,

    ])
            ->add('width', IntegerType::class, [
                'label' => false,
                'data'=>'1000',
                'row_attr' => ['class' => ' col-md-3 pb-0 mb-0'],
                'attr' => ['class'=>'form-control-sm','placeholder' => 'Ширина'],

            ])

            ->add('height', IntegerType::class, [
                'label' => false,
                'data'=>'1000',
                'row_attr' => ['class' => 'col-md-3 pb-0 mb-0'],
                'attr' => ['class'=>'form-control-sm','placeholder' => 'Высота']
            ])




            ->add('cutting', CheckboxType::class, [
                'label' => 'Порізка по зображенню',

            ])
            ->add('lamination', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'my-lamination row form-check-film-lamin form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => FilmLamination::class,
            ])

            ->add('ordersAll', OrdersAllCalcType::class, [
                'label'=>false,
                'disabled'=>$this->security->isGranted("ROLE_PARTNER"),


            ])

            ->add('sum', TextType::class, [
                'label' => false,
                'data'=>'1',
                'row_attr' => ['class' => 'col-md-3'],
                'attr' => ['class' => 'form-control-sm', 'style' => 'width:90px', 'placeholder' => 'шт.']

            ])

            ->add('sumKit', TextType::class, [
                'label' => false,
                'data'=>'1',
                'row_attr' => ['class' => 'col-md-3'],
                'attr' => ['class' => 'form-control-sm', 'style' => 'width:90px', 'placeholder' => 'шт.']

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'ЗАКАЗАТЬ',
                'row_attr' => ['class' => 'col-md-3'],
                'attr' => ['class' => 'btn-danger btn-sm'],


            ])
        ;
        if($options['resolution'] == true){
            $builder->add('resolution', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value'=> function(?FilmResolution $filmResolution) {
                    return $filmResolution ? $filmResolution->getId() : '1';},
                'attr' => ['class' => 'my-resolution-film row form-check-custom form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => FilmResolution::class,

            ]);
        }
        if ($options['ufColorLayer'] == true){
            $builder->add('ufColorLayer', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value'=> function(?FilmUfColorLayer $filmUfColorLayer) {
                    return $filmUfColorLayer ? $filmUfColorLayer->getId() : '1';},
                'attr' => ['class' => 'my-uf_color_layer-film row form-check-custom form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => FilmUfColorLayer::class,

            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderFilmKalc::class,
        ])
            ->setRequired('resolution')
            ->setRequired('ufColorLayer')
        ;
    }
}
