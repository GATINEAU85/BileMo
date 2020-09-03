<?php

namespace App\Links;

class LinksUsersGenerator extends LinksGenerator
{
    protected function createLinks(object $object)
    {
        $object->setLinks(['list' => $this->urlGenerator->generate('getUsers', [], 0),
                           'listByCustomer' => $this->urlGenerator->generate('getUsersByCustomer', ['id' => $object->getCustomer()->getId()], 0),
                           'selfByCustomer' => $this->urlGenerator->generate('getUserByCustomer', ['customerId' => $object->getCustomer()->getId(), 'id' => $object->getId()], 0),
                           'add' => $this->urlGenerator->generate('addUser', ['id' => $object->getCustomer()->getId()], 0),
                           'update' => $this->urlGenerator->generate('updateUser', ['customerId' => $object->getCustomer()->getId(), 'id' => $object->getId()], 0),
                           'delete' => $this->urlGenerator->generate('deleteUser',['customerId' => $object->getCustomer()->getId(), 'id' => $object->getId()], 0),
        ]);
    }
}