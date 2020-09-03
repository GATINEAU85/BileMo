<?php

namespace App\Links;

class LinksProductsGenerator extends LinksGenerator
{
    protected function createLinks(object $object)
    {
        $object->setLinks(['self' => $this->urlGenerator->generate('getProduct', ['id' => $object->getId()], 0),
                           'list' => $this->urlGenerator->generate('getProducts', [], 0),
        ]);
    }
}