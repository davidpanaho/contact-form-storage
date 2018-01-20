<?php

namespace Craft;

class ContactStorageService extends BaseApplicationComponent
{
    public function checkRecaptcha($responseCode) {

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

    public function validateHoneypot() {
        $settings = craft()->plugins->getPlugin('contactform')->getSettings();
        $fieldName = $settings->honeypotField;

        if (!$fieldName) {
            return true;
        }

        $honey = craft()->request->getPost($fieldName);
		return $honey == '';
    }

    public function storeSubmission($message)
    {
        $contactStorageRecord = new ContactStorageRecord();
        $contactStorageRecord->fromName = $message->fromName;
        $contactStorageRecord->fromEmail = $message->fromEmail;
        $contactStorageRecord->subject = $message->subject;
        $contactStorageRecord->htmlMessage = $message->htmlMessage;

        $contactStorageRecord->save();
    }

    public function getSubmissions()
    {
        $contactStorageRecords = ContactStorageRecord::model()->findAll(array('order' => 'dateUpdated desc'));

        // Craft::dd($contactStorageRecords);

        $contactStorageModels = [];

        if ($contactStorageRecords) {
            foreach ($contactStorageRecords as $record) {
                $contactStorageModels[] = ContactStorageModel::populateModel($record);
            }
        }

        // TODO check if this needs to return a model instead. If not, I can probably delete the model class
        return $contactStorageModels;
    }

    public function deleteSubmission($id)
    {
        $contactStorageRecord = ContactStorageRecord::model()->findByAttributes(array('id' => $id));

        if ($contactStorageRecord) {
            $contactStorageRecord->delete();
        }
    }

    public function getSubmission($id)
    {
        $record = ContactStorageRecord::model()->findByAttributes(array('id' => $id));

        if ($record) {
            return ContactStorageModel::populateModel($record);
        }
        return false;
    }
}
