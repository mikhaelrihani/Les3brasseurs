<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Entity\SupplyType;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;



class SupplyFixtures extends CoreFixtures implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        //! SupplyType

        $supplytypes = [];
        for ($i = 0; $i < 12; $i++) {
            $supplytype = new SupplyType();
            $supplytype->setName($this->faker->word());
            $supplytype->setCreatedAt(new \DateTime($this->faker->date()));
            $supplytype->setUpdatedAt(new \DateTime($this->faker->date()));

            $supplytypes[] = $supplytype;
            $manager->persist($supplytype);
        }

        //! Product

        $products = [];

        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->setSupplyType($supplytypes[array_rand($supplytypes)]);
            $product->setPrice($this->faker->randomFloat(2, 0, 1000));
            $product->setSlug($this->faker->text(10));
            $product->setCurrency($this->faker->currencyCode());
            $product->setConditionning($this->faker->text(10));
            $product->setName($this->faker->word());
            $product->setCreatedAt(new \DateTime($this->faker->date()));
            $product->setUpdatedAt(new \DateTime($this->faker->date()));

            $products[] = $product;
            $manager->persist($product);
        }


        //! Supplier

        $suppliers = [];
        $staffIndices = range(0, UserFixtures::UserCount -1);
        $maxSupplierCount = floor(UserFixtures::UserCount / 4);
        $productIndices = range(0, count($products) - 1);

        // Boucle pour créer les fournisseurs
        for ($i = 0; $i < $maxSupplierCount; $i++) {
            $supplier = new Supplier();
            $supplier->setName($this->faker->company());

            // Ajouter jusqu'à 3 employés
            for ($k = 0; $k < rand(1, 4); $k++) {
                $randomStaffIndex = array_rand($staffIndices);
                $staffIndex = $staffIndices[$randomStaffIndex];
                if (isset($staffIndex)) {
                    $supplier->addStaff($this->getReference("user_" . $staffIndex));
                    unset($staffIndices[$randomStaffIndex]);
                }
            }

            // Ajouter jusqu'à 5 produits
            for ($j = 0; $j < min(5, count($productIndices)); $j++) {
                $randomProductIndex = array_rand($productIndices);
                $productIndex = $productIndices[$randomProductIndex];
                if (isset($productIndex)) {
                    $supplier->addProduct($products[$productIndex]);
                    unset($productIndices[$randomProductIndex]);
                }
            }


            $supplier->setDescription($this->faker->text(200));
            $supplier->setComments($this->faker->text(200));
            $supplier->setSlug($this->faker->slug(3, false));
            $supplier->setCreatedAt(new \DateTime($this->faker->date()));
            $supplier->setUpdatedAt(new \DateTime($this->faker->date()));

            $suppliers[] = $supplier;
            $manager->persist($supplier);
        }



        $manager->flush();

    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}