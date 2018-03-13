<?php

namespace Craft;

class ContactStorage_FormController extends BaseController
{
    public function actionNew()
    {
        return $this->renderTemplate('contactstorage/forms/_new');
    }

    public function actionCreate()
    {
        $this->requirePostRequest();

        $name = craft()->request->getPost('formName');
        // TODO: make sure name exists before moving on

        if (!$name) {
            return;
        }


        $form = craft()->contactStorage->createForm($name);

        if (!$form) {
            return;
        }

        craft()->userSession->setNotice('Form created');
        $this->redirect('contactstorage');
    }

    public function actionFormSubmissions(array $variables = [])
    {
        $formId = $variables['formId'];

        if (!$formId) {
            throw new HttpException(404, 'It looks like this form doesn\'t exist.');
        }

        $form = craft()->contactStorage->getForm($formId);

        if (!$form) {
            throw new HttpException(404, 'It looks like this form doesn\'t exist.');
        }

        $submissions = craft()->contactStorage->getSubmissions($formId);

        $variables['form'] = $form;
        $variables['submissions'] = $submissions;

        return $this->renderTemplate('contactstorage/forms/_form', $variables);
    }
}
