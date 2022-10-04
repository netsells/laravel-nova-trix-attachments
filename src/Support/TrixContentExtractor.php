<?php

namespace Netsells\LaravelNovaTrixAttachments\Support;

use Symfony\Component\DomCrawler\Crawler;

class TrixContentExtractor
{
    public static function extractAttachmentData(string $html): array
    {
        $data = [];

        (new Crawler($html))->filter('figure')
            ->each(function (Crawler $figure) use (&$data) {
                $data[] = self::decode($figure->attr('data-trix-attachment'));
            });

        return array_values($data);
    }

    private static function decode(?string $encoded): ?array
    {
        return json_decode(html_entity_decode($encoded ?? ''), true);
    }
}
