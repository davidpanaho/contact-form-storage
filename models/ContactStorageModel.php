<?php

namespace Craft;

class ContactStorageModel extends BaseModel
{
    public function defineAttributes()
    {
        return array(
            'id' => AttributeType::Number,
            'fromName' => AttributeType::String,
            'fromEmail' => AttributeType::String,
            'subject' => AttributeType::String,
            'htmlMessage' => AttributeType::String,
            'dateCreated' => AttributeType::DateTime,
            'dateUpdated' => AttributeType::DateTime,
        );
    }
}
