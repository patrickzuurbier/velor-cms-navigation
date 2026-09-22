<?php

declare(strict_types=1);

namespace Velor\Navigation\Tests\Unit\Rules;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Velor\Navigation\Rules\NavigationUrl;

class NavigationUrlTest extends TestCase
{
    #[DataProvider('validUrls')]
    public function test_it_accepts_navigation_urls(string $url): void
    {
        $validator = $this->app->make(ValidationFactory::class)->make(
            ['url' => $url],
            ['url' => [new NavigationUrl()]],
        );

        $this->assertTrue($validator->passes());
    }

    #[DataProvider('invalidUrls')]
    public function test_it_rejects_invalid_navigation_urls(string $url): void
    {
        $validator = $this->app->make(ValidationFactory::class)->make(
            ['url' => $url],
            ['url' => [new NavigationUrl()]],
        );

        $this->assertFalse($validator->passes());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validUrls(): array
    {
        return [
            'https URL'            => ['https://velor-cms.nl/docs?topic=navigation#links'],
            'http URL'             => ['http://localhost:8000'],
            'internal path'        => ['/about'],
            'internal path anchor' => ['/about#team'],
            'anchor'               => ['#contact'],
            'email link'           => ['mailto:info@velor-cms.nl'],
            'telephone link'       => ['tel:+31201234567'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidUrls(): array
    {
        return [
            'relative path'         => ['about'],
            'protocol-relative URL' => ['//example.com'],
            'script URL'            => ['javascript:alert(1)'],
            'data URL'              => ['data:text/html,example'],
            'incomplete URL'        => ['https://'],
            'empty anchor'          => ['#'],
            'whitespace'            => ['/about us'],
            'invalid email link'    => ['mailto:not-an-email'],
            'invalid phone link'    => ['tel:call-me'],
        ];
    }
}
