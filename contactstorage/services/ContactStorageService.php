<?php

namespace Craft;

class ContactStorageService extends BaseApplicationComponent
{
    public function checkRecaptcha($responseCode)
    {

        $ipAddress = craft()->request->getIpAddress();
        $reCaptchaSecret = craft()->config->get('reCaptchaSecret', 'contactstorage');

        $post_data = http_build_query(
            [
                'secret' => $reCaptchaSecret,
                'response' => $responseCode,
                'remoteip' => $ipAddress,
            ]
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);


        if (isset($result->success) && $result->success === true) {
            return true;
        }

        return false;
    }

    public function validateHoneypot()
    {
        $settings = craft()->plugins->getPlugin('contactform')->getSettings();
        $fieldName = $settings->honeypotField;

        if (!$fieldName) {
            return true;
        }

        $honey = craft()->request->getPost($fieldName);
        return $honey == '';
    }

    public function storeSubmission($message, $formId)
    {
        $record = new ContactStorage_SubmissionRecord();
        $record->fromName = $message->fromName;
        $record->fromEmail = $message->fromEmail;
        $record->subject = $message->subject;
        $record->htmlMessage = $message->htmlMessage;
        $record->formId = $formId;

        $record->save();
    }

    public function getSubmissions($id)
    {
        $attributes = ['formId' => $id];
        $criteria = ['order' => 'dateCreated DESC'];

        $records = ContactStorage_SubmissionRecord::model()->findAllByAttributes($attributes, $criteria);

        $models = [];

        if ($records) {
            foreach ($records as $record) {
                $models[] = ContactStorage_SubmissionModel::populateModel($record);
            }
        }

        // TODO check if this needs to return a model instead. If not, I can probably delete the model class
        return $models;
    }

    public function deleteSubmission($id)
    {
        $record = ContactStorage_SubmissionRecord::model()->findByAttributes(array('id' => $id));

        if ($record) {
            $record->delete();
        }
    }

    public function getSubmission($id)
    {
        $record = ContactStorage_SubmissionRecord::model()->findByAttributes(array('id' => $id));

        if ($record) {
            return ContactStorage_SubmissionModel::populateModel($record);
        }
        return false;
    }

    public function getForm($id)
    {
        $record = ContactStorage_FormRecord::model()->findByAttributes(array('id' => $id));
        if (!$record) {
            return false;
        }
        $model = ContactStorage_FormModel::populateModel($record);

        return $model;
    }

    public function getForms()
    {
        $records = ContactStorage_FormRecord::model()->findAll(['order' => 'id']);
        $models = [];

        if ($records) {
            foreach ($records as $record) {
                $models[] = ContactStorage_FormModel::populateModel($record);
            }

            return $models;
        }

        return false;
    }

    public function createForm($name)
    {
        $record = new ContactStorage_FormRecord();
        $record->name = $name;

        if (!$record->validate()) {
            return false;
        }

        $record->save();
        return true;
    }

    public function deleteForm($id)
    {
        $record = ContactStorage_FormRecord::model()->findByAttributes(array('id' => $id));

        if ($record) {
            $record->delete();
        }
    }
}
