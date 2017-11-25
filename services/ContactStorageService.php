<?php

namespace Craft;

class ContactStorageService extends BaseApplicationComponent
{
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
