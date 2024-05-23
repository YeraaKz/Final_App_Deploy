<?php

namespace App\Enum;

enum CustomItemAttributeDatatype: string
{
    case Integer = 'INT';

    case String = 'STRING';

    case Text = 'TEXT';

    case Boolean = 'BOOL';

    case Date = 'DATE';
}
