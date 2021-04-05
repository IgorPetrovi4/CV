<?php

namespace App\Form;

use App\Entity\AddressesUser;
use App\Entity\OrdersAll;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;

class OrdersAllType extends AbstractType
{

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('upload', FileType::class, [
                'label' => 'Лицевая сторона или макет одним файлом',
                'mapped' => false,
                'required' => false,
                'data_class'=>null,
               // 'row_attr' => ['class' => 'col-md-8'],
                'attr'=>['class' => 'custom-file-input form-control-sm'],

                'constraints' => [

                    new File([
                        'maxSize' => '75M',
                        //1024 x 512 px.
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/tiff',
                            'image/tif',
                            'image/pdf',
                            'image/eps',
                            'application/pdf',
                            'application/x-pdf',
                            'image/x-eps',
                            'application/postscript',
                            'application/cdr',
                            'application/coreldraw',
                            'application/plt',
                            'image/plt',
                            'image/x-plt'
                        ],
                    ])
                ],
            ])
            ->add('uploadNext', FileType::class, [
                'label' => 'Оборот',
                'mapped' => false,
                'required' => false,
                'data_class'=>null,
                //'row_attr' => ['class' => 'col-md-8'],
                'attr'=>['class' => 'custom-file-input form-control-sm'],

                'constraints' => [

                    new File([
                        'maxSize' => '75M',
                        //1024 x 512 px.
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/tiff',
                            'image/tif',
                            'image/pdf',
                            'application/pdf',
                            'application/x-pdf',
                            'image/x-eps',
                            'application/postscript',
                            'application/cdr',
                            'application/coreldraw',
                            'application/plt',
                            'image/plt',
                            'image/x-plt'
                        ],
                    ])
                ],
            ])
            ->add('urlupload', TextType::class, [
                'label' => false,
                //'row_attr' => ['class' => 'col-md-6'],
                'attr' => ['class' => 'form-control-sm','placeholder' => 'Ссылка на файлообменник',
                ]
            ])
            ->add('feedback', TextareaType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control-sm col-md-12','rows' => 2,
                ]
            ])
            ->add('description', TextType::class, [
                'label' => false,
                //'row_attr' => ['class' => 'col-md-6'],
                'attr' => ['class' => 'form-control-sm','placeholder' => 'Короткое название',
                ]
            ])

            ->add('delivery', EntityType::class, [
                'label' => false,
                'multiple' => false,
                'placeholder' => 'Самовывоз',
                'choice_value'=> function(?AddressesUser $addressesUser) {
                    return $addressesUser ? $addressesUser->getId() :'1';},
                'class' => AddressesUser::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.user = ?1')
                        ->orderBy('a.id', 'ASC')
                        ->setParameter(1, $this->security->getUser());

                },


            ])

            ->add('submit', SubmitType::class, [
                'label' => 'ЗАКАЗАТЬ',
                'attr' => ['class' => 'btn btn-outline-dark rounded-0'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrdersAll::class,
        ]);
    }
}
