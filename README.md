Laravel Nova Trix Attachments
=============================

Simplifies and enhances the use of Laravel Nova Trix file attachments by providing the required migrations and scheduled command out of the box as well as a rule to validate allowed file MIME types.

## Features

Use rule `TrixFileAttachmentRule` to validate an array of allowed file MIME types patterns.

```php
use Laravel\Nova\Fields\Trix;
use Netsells\LaravelNovaTrixAttachments\TrixFileAttachmentRule;

public function fields(Request $request)
{
    return [
        // ...

        Trix::make('Field')->rules([new TrixFileAttachmentRule(['image/*'])]),
    ];
}

```

## Installation

Install the package with:

`composer require netsells/laravel-nova-trix-attachments`


## Testing

`./vendor/bin/phpunit`
