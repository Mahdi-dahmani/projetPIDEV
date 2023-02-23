<?php

namespace App\Form;

use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class DonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_ben',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('titre',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('qte',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('type',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('date',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('id_local',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('id_cat',NULL,array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
           
            ->add('imge', FileType::class, [
                'label' => 'votre image (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload an image ',
                    ])
                ],
            ])
            ->add('save', SubmitType::class,);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
