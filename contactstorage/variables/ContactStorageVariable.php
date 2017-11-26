<?php

namespace Craft;

class ContactStorageVariable
{
    public function getSubmissions()
    {
        return craft()->contactStorage->getSubmissions();
    }
}
