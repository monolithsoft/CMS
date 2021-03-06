<?php

declare(strict_types=1);

namespace Monolith\Bundle\CMSBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    /** @var EntityManager */
    protected $em;

    /** @var string */
    protected $user_class;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $user_class
     */
    public function __construct(EntityManager $em, $user_class)
    {
        $this->em         = $em;
        $this->user_class = $user_class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', null,  ['required' => false])
            ->add('username', null, [
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => ['autofocus' => 'autofocus'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
            ])
            ->add('plainPassword', RepeatedType::class, [ // 'repeated'
                'type'            => PasswordType::class,
                'required'        => false,
                'options'         => ['translation_domain' => 'FOSUserBundle'],
                'first_options'   => ['label' => 'form.new_password'],
                'second_options'  => ['label' => 'form.new_password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
            ])->add('groups', null, [
                'required'        => false,
                'expanded'        => true,
                'multiple'        => true,
                'choice_translation_domain' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->user_class, // 'extends of Monolith\Bundle\CMSBundle\Model\UserModel',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'monolith_cms_user';
    }
}
