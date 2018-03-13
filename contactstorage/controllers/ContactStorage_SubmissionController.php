<?php

namespace Craft;

class ContactStorage_SubmissionController extends BaseController
{
    public function actionDelete()
    {
        $id = craft()->request->getRequiredParam('id');

        craft()->contactStorage->deleteSubmission($id);

        craft()->userSession->setNotice('Submission deleted');

        $this->redirect('contactstorage');
    }

    public function actionViewSubmission(array $variables = [])
    {
        $id = $variables['submissionId'];
        $submission = craft()->contactStorage->getSubmission($id);

        if (!$submission) {
            throw new HttpException(404, 'It looks like this submission doesn\'t exist.');
        }

        // TODO: Change crumbs url to a better value
        // $variables['crumbs'] = [
        //     ['url' => '/admin/contactstorage', 'label' => 'Contact Form Storage']
        // ];
        $variables['submission'] = $submission;

        return $this->renderTemplate('contactstorage/submissions/_entry', $variables);
    }
}
