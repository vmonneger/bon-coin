<?php

namespace App\Factory;

use App\Entity\Announces;
use App\Repository\AnnouncesRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Announces>
 *
 * @method static Announces|Proxy createOne(array $attributes = [])
 * @method static Announces[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Announces[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Announces|Proxy find(object|array|mixed $criteria)
 * @method static Announces|Proxy findOrCreate(array $attributes)
 * @method static Announces|Proxy first(string $sortedField = 'id')
 * @method static Announces|Proxy last(string $sortedField = 'id')
 * @method static Announces|Proxy random(array $attributes = [])
 * @method static Announces|Proxy randomOrCreate(array $attributes = [])
 * @method static Announces[]|Proxy[] all()
 * @method static Announces[]|Proxy[] findBy(array $attributes)
 * @method static Announces[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Announces[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AnnouncesRepository|RepositoryProxy repository()
 * @method Announces|Proxy create(array|callable $attributes = [])
 */
final class AnnouncesFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'user_id' => self::faker()->randomNumber(),
            'images' => [],
            'title' => self::faker()->text(),
            'description' => self::faker()->text(),
            'created_at' => null, // TODO add DATETIMETZ ORM type manually
            'price' => self::faker()->randomNumber(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Announces $ announces): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Announces::class;
    }
}
