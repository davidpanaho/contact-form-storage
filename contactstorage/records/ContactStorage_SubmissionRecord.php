<?php

namespace Craft;

class ContactStorage_SubmissionRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'contactstorage_submissions';
    }

    public function defineAttributes()
    {
        return array(
            'fromName' => array(AttributeType::String, 'default' => null),
            'fromEmail' => array(AttributeType::String, 'default' => null),
            'subject' => array(AttributeType::String, 'default' => null),
            'htmlMessage' => array(AttributeType::String, 'default' => null),
            'formId' => array(
                'type' => AttributeType::Number,
                'required' => true
            ),
        );
    }
}
