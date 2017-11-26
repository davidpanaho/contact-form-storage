<?php

/*

TODO
It looks like the plugin will save a submission regardless of its validity. This
needs to be checked.

*/

namespace Craft;

class ContactStoragePlugin extends BasePlugin
{
    public function getName()
    {
        return 'Contact Form Storage';
    }

    public function getVersion()
    {
        return '1.0.2';
    }

    public function getDeveloper()
    {
        return 'David Panaho';
    }

    public function getDeveloperUrl()
    {
        return 'https://www.davidpanaho.com';
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/davidpanaho/contact-form-storage';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/davidpanaho/contact-form-storage/master/releases.json';
    }

    public function init()
    {
        craft()->on('contactForm.beforeSend', function(ContactFormEvent $event) {

            $message = $event->params['message'];
            craft()->contactStorage->storeSubmission($message);
        });
    }

    public function hasCpSection()
    {
        return true;
    }
}
