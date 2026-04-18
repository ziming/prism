<?php

declare(strict_types=1);

use Prism\Prism\Providers\Gemini\Maps\SchemaMap;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\BooleanSchema;
use Prism\Prism\Schema\EnumSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\RawSchema;
use Prism\Prism\Schema\StringSchema;

it('maps array schema correctly', function (): void {
    $map = (new SchemaMap(new ArraySchema(
        name: 'testArray',
        description: 'test array description',
        items: new StringSchema(
            name: 'testName',
            description: 'test string description',
            nullable: true,
        ),
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'test array description',
        'type' => ['array', 'null'],
        'items' => [
            'description' => 'test string description',
            'type' => ['string', 'null'],
        ],
    ]);
});

it('maps boolean schema correctly', function (): void {
    $map = (new SchemaMap(new BooleanSchema(
        name: 'testBoolean',
        description: 'test description',
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'test description',
        'type' => ['boolean', 'null'],
    ]);
});

it('maps enum schema correctly', function (): void {
    $map = (new SchemaMap(new EnumSchema(
        name: 'testEnum',
        description: 'test description',
        options: ['option1', 'option2'],
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'test description',
        'enum' => ['option1', 'option2'],
        'type' => ['string', 'null'],
    ]);
});

it('maps number schema correctly', function (): void {
    $map = (new SchemaMap(new NumberSchema(
        name: 'testNumber',
        description: 'test description',
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'test description',
        'type' => ['number', 'null'],
    ]);
});

it('maps string schema correctly', function (): void {
    $map = (new SchemaMap(new StringSchema(
        name: 'testName',
        description: 'test description',
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'test description',
        'type' => ['string', 'null'],
    ]);
});

it('maps object schema correctly', function (): void {
    $map = (new SchemaMap(new ObjectSchema(
        name: 'testObject',
        description: 'test object description',
        properties: [
            new StringSchema(
                name: 'testName',
                description: 'test string description',
            ),
        ],
        requiredFields: ['testName'],
        allowAdditionalProperties: true,
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'test object description',
        'type' => ['object', 'null'],
        'properties' => [
            'testName' => [
                'description' => 'test string description',
                'type' => 'string',
            ],
        ],
        'required' => ['testName'],
    ]);
});

it('preserves all nullable enum types', function (): void {
    $map = (new SchemaMap(new EnumSchema(
        name: 'temperature',
        description: 'temperature reading',
        options: [98.6, 'unknown'],
        nullable: true,
    )))->toArray();

    expect($map)->toBe([
        'description' => 'temperature reading',
        'enum' => [98.6, 'unknown'],
        'type' => ['number', 'string', 'null'],
    ]);
});

it('does not map a raw schema', function (): void {
    $map = (new SchemaMap(new RawSchema(
        'schema',
        [
            'type' => 'array',
            'items' => ['type' => 'string'],
        ]
    )))->toArray();

    expect($map)->toBe([
        'type' => 'array',
        'items' => [
            'type' => 'string',
        ],
    ]);
});
