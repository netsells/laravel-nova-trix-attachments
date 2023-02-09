<?php

namespace Tests\Unit;

use Netsells\LaravelNovaTrixAttachments\TrixFileAttachmentRule;
use PHPUnit\Framework\TestCase;

class TrixFileAttachmentRuleTest extends TestCase
{
    /**
     * @dataProvider passesProvider
     */
    public function testPasses(?string $html, bool $expected)
    {
        $rule = new TrixFileAttachmentRule(['image/*', 'video/*']);

        $this->assertSame($expected, $rule->passes('attribute', $html));
    }

    public function passesProvider(): array
    {
        return [
            'Missing data-trix-attachment attribute fails' => [
                'html' => '<figure></figure>',
                'expected' => false,
            ],
            'Non existent data fails' => [
                'html' => '<figure data-trix-attachment=""></figure>',
                'expected' => false,
            ],
            'A not allowed content type fails' => [
                'html' => $this->getFigureHtml('application/json', 'file.json'),
                'expected' => false,
            ],
            'Not allowed content types fail' => [
                'html' => implode('', [
                    $this->getFigureHtml('application/json', 'file.json'),
                    $this->getFigureHtml('image/jpeg', 'file.jpeg'),
                ]),
                'expected' => false,
            ],
            'Empty data passes' => [
                'html' => null,
                'expected' => true,
            ],
            'A valid image content type passes' => [
                'html' => $this->getFigureHtml('image/jpeg', 'file.jpeg'),
                'expected' => true,
            ],
            'A valid video content type passes' => [
                'html' => $this->getFigureHtml('video/avi', 'file.avi'),
                'expected' => true,
            ],
            'Valid mixed content types pass' => [
                'html' => implode('', [
                    $this->getFigureHtml('image/jpeg', 'image.jpeg'),
                    $this->getFigureHtml('image/png', 'image.png'),
                    $this->getFigureHtml('video/mp4', 'video.mp4'),
                ]),
                'expected' => true,
            ],
            'HTML without figure elements passes' => [
                'html' => '<div></div>',
                'expected' => true,
            ],
            'Empty HTML passes' => [
                'html' => '',
                'expected' => true,
            ],
        ];
    }

    private function getFigureHtml(string $contentType, string $filename): string
    {
        $data = json_encode(compact('contentType', 'filename'));

        return <<<HTML
<div>
    <figure data-trix-attachment='$data'></figure>
</div>
HTML;
    }
}
