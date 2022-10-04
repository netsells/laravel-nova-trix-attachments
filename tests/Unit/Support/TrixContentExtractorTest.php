<?php

namespace Tests\Unit\Support;

use Netsells\LaravelNovaTrixAttachments\Support\TrixContentExtractor;
use PHPUnit\Framework\TestCase;

class TrixContentExtractorTest extends TestCase
{
    public function testExtractAttachmentContentTypes(): void
    {
        $data = TrixContentExtractor::extractAttachmentData(
            file_get_contents(__DIR__.'/fixtures/trix-content.html')
        );

        $expected = [
            null,
            null,
            null,
            [
                'contentType' => 'image/jpeg',
                'filename' => 'test-image.jpeg',
                'filesize' => 0,
                'href' => 'path/2/hash.jpg',
                'url' => 'path/2/hash.jpg',
            ],
            [
                'contentType' => 'text/plain',
                'filename' => 'test-file.txt',
                'filesize' => 0,
                'href' => 'path/2/hash',
                'url' => 'path/2/hash',
            ],
        ];

        $this->assertEquals($expected, $data);
    }
}
