<?php

namespace Craft;

class ContactStorage_FormRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'contactstorage_forms';
    }

    public function defineAttributes()
    {
        return array(
            'name' => array(AttributeType::String, 'default' => null),
        );
    }
}
