<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupRa\RaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeRaLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'ra.form.ra_search_ra_candidates.label.name',
            ])
            ->add('location', 'textarea', [
                'label' => 'ra.management.form.change_ra.label.location'
            ])
            ->add('contactInformation', 'textarea', [
                'label' => 'ra.management.form.change_ra.label.contact_information'
            ])
            ->add('change_ra_location', 'submit', [
                'label' => 'ra.management.form.change_ra_location.label.change_ra_location',
                'attr' => ['class' => 'btn btn-primary pull-right change-ra-location']
            ])
            ->add(
                'cancel',
                'anchor',
                [
                    'label' => 'ra.management.form.change_ra_location.label.cancel',
                    'route' => 'ra_locations_manage',
                    'attr'  => ['class' => 'btn btn-link pull-right cancel']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Surfnet\StepupRa\RaBundle\Command\ChangeRaLocationCommand'
        ]);
    }

    public function getName()
    {
        return 'ra_change_ra_location';
    }
}
