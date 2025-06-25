<?php

use Modules\Page\Entities\Page;
use Modules\Page\PageService;
use Modules\Page\Repository\PageRepository;
use Modules\Setting\OptionService;
use Psr\Container\ContainerExceptionInterface;

function getOption($key, $default = null)
{
    try {
        return app(OptionService::class)->get($key, $default) ?? $default;
    } catch (\Exception|ContainerExceptionInterface $e) {
        return $default;
    }
}

function getPageAttributes($pageID, $attribute): string
{
    $service = new PageService(new PageRepository(new Page()));
    return $service->getAttributes($pageID, $attribute);
}

function getPageAttribute($pageID, $attribute): string
{
    $service = new PageService(new PageRepository(new Page()));
    return $service->getAttributes($pageID, $attribute)->firstWhere('label', $attribute)->content;
}

function prettyPrint( $json ): string
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                $level--;
                $ends_line_level = NULL;
                $new_line_level = $level;
                break;

                case '{': case '[':
                $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                $char = "";
                $ends_line_level = $new_line_level;
                $new_line_level = NULL;
                break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}
