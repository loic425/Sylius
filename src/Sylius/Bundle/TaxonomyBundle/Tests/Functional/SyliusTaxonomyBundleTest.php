<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\TaxonomyBundle\Tests\Functional;

use PHPUnit\Framework\Assert;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class SyliusTaxonomyBundleTest extends KernelTestCase
{
    /**
     * @test
     */
    public function its_services_are_initializable()
    {
        static::bootKernel();

        /** @var Container $container */
        $container = self::$kernel->getContainer();

        $serviceIds = array_filter($container->getServiceIds(), function (string $serviceId): bool {
            return 0 === strpos($serviceId, 'sylius.');
        });

        foreach ($serviceIds as $id) {
            Assert::assertNotNull($container->get($id, ContainerInterface::NULL_ON_INVALID_REFERENCE));
        }
    }

    /**
     * @test
     */
    public function its_initializes_default_doctrine_repositories(): void
    {
        static::bootKernel();

        /** @var Container $container */
        $container = self::$kernel->getContainer();

        $repositories = [
            'sylius.repository.taxon' => TaxonRepository::class,
            'sylius.repository.taxon_translation' => EntityRepository::class,
        ];

        foreach ($repositories as $id => $class) {
            $service = $container->get($id, ContainerInterface::NULL_ON_INVALID_REFERENCE);
            Assert::assertEquals(get_class($service), $class);
        }
    }
}
