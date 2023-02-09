<?php

namespace Netsells\LaravelNovaTrixAttachments;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Netsells\LaravelNovaTrixAttachments\Support\TrixContentExtractor;

class TrixFileAttachmentRule implements Rule
{
    private array $allowedTypePatterns;

    private string $message = '';

    public function __construct(array $allowedTypePatterns)
    {
        $this->allowedTypePatterns = $allowedTypePatterns;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $data = TrixContentExtractor::extractAttachmentData($value ?? '');

        return Collection::make($data)->every(function (?array $attachment) {
            [$contentType, $filename] = $this->getAttachmentData($attachment);

            if ($this->isAllowedContentType($contentType ?? '')) {
                return true;
            }

            $this->message = sprintf(
                "Content type '%s' of file '%s' does not match any of '%s'.",
                $contentType,
                $filename,
                implode("', '", $this->allowedTypePatterns)
            );

            return false;
        });
    }

    private function getAttachmentData(?array $attachment): array
    {
        return [
            $attachment['contentType'] ?? null,
            $attachment['filename'] ?? null,
        ];
    }

    private function isAllowedContentType(string $contentType): bool
    {
        return Str::is($this->allowedTypePatterns, $contentType);
    }
}
