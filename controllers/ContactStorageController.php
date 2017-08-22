<?php

namespace Craft;

class ContactStorageController extends BaseController
{
    public function actionDelete()
    {
        $id = craft()->request->getRequiredParam('id');

        craft()->contactStorage->deleteSubmission($id);

        craft()->userSession->setNotice('Submission deleted');

        $this->redirect('contactstorage');
    }
}
