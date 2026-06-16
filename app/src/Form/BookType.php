<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\BookCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                'trim' => true,
            ])
            ->add('isbn')
            ->add('category', EntityType::class, [
                'class' => BookCategory::class,
                'choice_label' => 'title',
                'placeholder' => 'Choose a category',
                'required' => false,
            ])
        ;

        //hook for description cutting by substr

//        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
//            $data = $event->getData();
//
//            if (isset($data['description'])) {
//                $data['description'] = substr($data['description'], 0, 80);
//            }
//
//            // Update the event with the modified data
//            $event->setData($data);
//        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
