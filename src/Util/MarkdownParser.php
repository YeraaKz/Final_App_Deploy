<?php

namespace App\Util;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;

class MarkdownParser
{
    private $converter;

    public function __construct()
    {
        $this->converter = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * @throws CommonMarkException
     */
    public function parse(?string $markdown): string
    {
        if(!$markdown){
            return "";
        }

        return $this->converter->convert($markdown);
    }
}