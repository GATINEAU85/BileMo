<?php

namespace App\Links;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LinksGenerator
{
    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    public function addLinks($datas)
    {
        if (is_array($datas)) {
            foreach ($datas as $object) {
                $this->createLinks($object);
            }

            return;
        }
        $this->createLinks($datas);

        return;
    }
}