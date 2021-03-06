<?php

namespace Craft;

class ContactStorageVariable
{
    public function getSubmissions()
    {
        return craft()->contactStorage->getSubmissions();
    }

    public function reCaptchaEnabled()
    {
        return craft()->config->get('reCaptcha', 'contactstorage');
    }
}
