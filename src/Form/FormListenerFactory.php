<?php

namespace App\Form;

use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormLitenerfactory
{

    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();
            if (empty($data['slug'])) {
                $sluger = new AsciiSlugger();
                $data['slug'] = strtolower($sluger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event) {
            $data = $event->getData();
            $data->setUpdatedAt(new \DateTimeImmutable());
            if (!$data->getData()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}
