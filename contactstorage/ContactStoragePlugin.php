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
        return '1.1.1';
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
            $reCaptchaEnabled = craft()->config->get('reCaptcha', 'contactstorage');

            $formId = craft()->request->getParam('formId');
            if (!$formId) {
                // Assumes the default form has an id of 1
                $formId = 1;
            }

            if ($reCaptchaEnabled) {
                $recaptchaResponseCode = craft()->request->getPost('g-recaptcha-response');
                if (!craft()->contactStorage->checkRecaptcha($recaptchaResponseCode)) {
                    craft()->userSession->setError('There was a reCaptcha error with your submission. Please try again.');
                    $event->isValid = false;
                    return;
                }
            }

            if ($message->hasErrors()) {
                return;
            }

            if (!craft()->contactStorage->validateHoneypot()) {
                $event->fakeIt = true;
                return;
            }

            craft()->contactStorage->storeSubmission($message, $formId);
        });
    }

    public function hasCpSection()
    {
        return true;
    }

    public function registerCpRoutes()
    {
        return [
            'contactstorage/form/new' => ['action' => 'contactStorage/form/new'],
            'contactstorage/form/(?P<formId>\d+)' => ['action' => 'contactStorage/form/formSubmissions'],
            'contactstorage/submission/(?P<submissionId>\d+)'=> ['action' => 'contactStorage/submission/viewSubmission'],
        ];
    }

    public function onAfterInstall()
    {
        craft()->contactStorage->createForm('Default');
    }
}
