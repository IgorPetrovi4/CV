<?php

namespace App\Form;

use App\Entity\DigitalFormatList;
use App\Entity\DigitalLamination;
use App\Entity\DigitalPaperList;
use App\Entity\DigitalPaperProduct;
use App\Entity\DigitalPrintColor;
use App\Entity\DigitalProduct;
use App\Entity\DigitalServiceCrease;
use App\Entity\DigitalServiceFolding;
use App\Entity\DigitalServiceHole;
use App\Entity\DigitalServices;
use App\Entity\OrderDigitalPaperKalc;
use App\Repository\DigitalPaperListRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class OrderDigitalPaperKalcType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('digitalFormat', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalFormatList $digitalFormatList) use ($options) {
                    return $digitalFormatList ? $digitalFormatList->getId() : $options['id_choice'];
                },
                'attr' => ['class' => 'my-digital-format row form-check-paper form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalFormatList::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('df')
                        ->leftJoin('df.digitalProducts', 'dp')
                        ->where('dp.product_name = :format')
                        ->setParameter('format', $options['format']);
                },
            ])
            ->add('width', IntegerType::class, [
                'label'=>false,
                'data' => $options['format']  == 'Свій розмір' ? 297: null,
                //'constraints' => [new Range(['min' => 40, 'max'=> 450])],
                'attr' => ['class' => 'form-control-sm'],
                'row_attr'=>['class' => 'p-0 m-0'],

            ])
            ->add('height', IntegerType::class, [
                'label'=>false,
                'data' => $options['format']  == 'Свій розмір' ? 420: null,
                //'constraints' => [new Range(['min' => 40, 'max'=> 450])],
                'attr' => ['class' => 'form-control-sm'],
                'row_attr'=>['class' => 'p-0 m-0'],


            ])
            ->add('digital_paper_product', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalPaperProduct $digitalPaperProduct) {
                    return $digitalPaperProduct ? $digitalPaperProduct->getId() : '1';
                },
                'attr' => ['class' => 'my-digital-product row form-check-paper form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalPaperProduct::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    if ($options['format'] == 'Флаєр' || $options['format'] == 'Візитка' || $options['format'] == 'Буклет' ||  $options['format'] == 'Календарик') {
                        return $er->createQueryBuilder('dp')
                            ->where('dp.id IN (:id)')
                            ->setParameter('id', [1, 2, 3]);
                    }

                },
            ])
            ->add('digital_paper', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'my-digital-paper row form-check-paper form-check-inline '],
                'label_attr' => ['class' => 'mt-2', 'product'],
                'class' => DigitalPaperList::class,

            ])
            ->add('print_color', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalPrintColor $digitalPrintColor) {
                    return $digitalPrintColor ? $digitalPrintColor->getId() : '1';
                },
                'attr' => ['class' => 'my-print-digital-color row form-check-custom form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalPrintColor::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('dpc')
                        ->orderBy('dpc.id', 'ASC');
                },
            ])
            ->add('lamination', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'my-lamination-digital-paper row form-check-paper-lamin form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalLamination::class,
            ])

            ->add('services', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalServices $digitalServices) {
                    return $digitalServices ? $digitalServices->getId() : '1';
                },
                'attr' => ['class' => 'my-services-rounding row form-check-paper-lamin form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalServices::class,

            ])

            ->add('serviceHole', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalServiceHole $digitalServiceHole) {
                    return $digitalServiceHole ? $digitalServiceHole->getId() : '1';
                },
                'attr' => ['class' => 'my-services-hole row form-check-paper-lamin form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalServiceHole::class,

            ])

            ->add('serviceFolding', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalServiceFolding $digitalServiceFolding) {
                    return $digitalServiceFolding ? $digitalServiceFolding->getId() : '1';
                },
                'attr' => ['class' => 'my-services-folding row form-check-paper-lamin form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalServiceFolding::class,

            ])

            ->add('serviceCrease', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_value' => function (?DigitalServiceCrease $digitalServiceCrease) {
                    return $digitalServiceCrease ? $digitalServiceCrease->getId() : '1';
                },
                'attr' => ['class' => 'my-services-crease row form-check-paper-lamin form-check-inline'],
                'label_attr' => ['class' => 'mt-2'],
                'class' => DigitalServiceCrease::class,

            ])

            ->add('sum', TextType::class, [
                'label' => false,
                'data' => '1',
                'row_attr' => ['class' => 'col-md-3'],
                'attr' => ['class' => 'form-control-sm', 'style' => 'width:90px', 'placeholder' => 'шт.']

            ])
            ->add('sumKit', TextType::class, [
                'label' => false,
                'data' => '1',
                'row_attr' => ['class' => 'col-md-3'],
                'attr' => ['class' => 'form-control-sm', 'style' => 'width:90px', 'placeholder' => 'шт.']

            ])
            ->add('ordersAll', OrdersAllCalcType::class, [
                'label' => false,
                'disabled' => $this->security->isGranted("ROLE_PARTNER"),


            ])
            ->add('submit', SubmitType::class, [
                'label' => 'ЗАКАЗАТЬ',
                'row_attr' => ['class' => 'col-md-3'],
                'attr' => ['class' => 'btn-danger btn-sm'],

            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class' => OrderDigitalPaperKalc::class,
        ])
            ->setRequired('format')
            ->setRequired('id_choice');
    }
}

