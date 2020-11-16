<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
  

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre","Taper un super titre pour votre annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web","Taper une adresse web ", ['required'=> false]))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction","Taper une introduction pour votre annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description","Taper une super description pour votre annonce"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("Url de le votre image","Donnez l'adresse de l'image qui donne vraiment envie"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit","Taper un prix"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Le nombre de chambre","Donnez le nombre de chambre "))
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
