<?php

namespace Schranz\Search\SEAL\Tests\Schema\Loader;

use PHPUnit\Framework\TestCase;
use Schranz\Search\SEAL\Schema\Loader\PhpFileLoader;

class PhpFileLoaderTest extends TestCase
{
    public function testLoadBasic(): void
    {
        $schema = (new PhpFileLoader([__DIR__ . '/fixtures/basic']))->load();

        $this->assertEqualsCanonicalizing(
            [
                'news',
                'blog',
            ],
            array_keys($schema->indexes),
        );
    }

    public function testLoadMerge(): void
    {
        $schema = (new PhpFileLoader([__DIR__ . '/fixtures/merge']))->load();

        $this->assertSame(['blog'], array_keys($schema->indexes));

        $this->assertSame(
            [
                'id',
                'title',
                'description',
                'blocks',
                'footerText',
            ],
            array_keys($schema->indexes['blog']->fields),
        );

        $this->assertSame(
            [
                'option1' => true,
                'option2' => true,
            ],
            $schema->indexes['blog']->fields['description']->options,
        );

        $this->assertSame(
            [
                'text',
                'embed',
                'gallery',
            ],
            array_keys($schema->indexes['blog']->fields['blocks']->types),
        );

        $this->assertTrue(
            $schema->indexes['blog']->fields['blocks']->types['gallery']['media']->multiple,
        );
    }
}
