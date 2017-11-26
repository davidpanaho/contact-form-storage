<?php

namespace Craft;

class ContactStorageRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'contact_storage';
    }

    public function defineAttributes()
    {
        return array(
            'fromName' => array(AttributeType::String, 'default' => null),
            'fromEmail' => array(AttributeType::String, 'default' => null),
            'subject' => array(AttributeType::String, 'default' => null),
            'htmlMessage' => array(AttributeType::String, 'default' => null),
        );
    }
}
