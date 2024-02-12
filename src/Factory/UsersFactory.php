<?php

namespace App\Factory;
use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ModelFactory<User>
 *
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create(array|callable $attributes = [])
 */
final class UsersFactory extends ModelFactory
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    /**
     * UsersFactory constructor.
     *
     * @param UserPasswordHasherInterface $userPasswordHasherInterface Symfony's UserPasswordHasherInterface service.
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        parent::__construct();
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    /**
     * Get the default values for creating User instances.
     *
     * @return array An array of default attributes for User instances.
     */
    protected function getDefaults(): array
    {
        return [
            // Model Factories URL : https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
            'email' => self::faker()->email(),
            'plainPassword' => 'umami12345',
            'username' => self::faker()->userName(),
            //'isVerified' => true,
        ];
    }

    /**
     * Initialize the factory, setting up post-instantiation hooks.
     *
     * @return $this The initialized factory instance.
     */
    protected function initialize(): self
    {
        // Initialization URL : https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            ->afterInstantiate(function(User $user) {
                if ($user->getPlainPassword()) {
                    $user->setPassword(
                        $this->userPasswordHasherInterface->hashPassword($user, $user->getPlainPassword())
                    );
                }
            })
        ;
    }

    /**
     * Get the class name of the model this factory produces.
     *
     * @return string The fully qualified class name of the User model.
     */
    protected static function getClass(): string
    {
        return User::class;
    }
}