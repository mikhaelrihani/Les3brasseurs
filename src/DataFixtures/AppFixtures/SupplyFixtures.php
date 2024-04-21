<?php

namespace App\DataFixtures\AppFixtures;

use App\DataFixtures\AppFixtures\CoreFixtures;
use App\Entity\Order;
use App\Entity\OrdersProducts;
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

        // we retrieve the pictures from the references to be able to associate them with the products
        $pictures = [];
        $i = 0;

        while ($this->hasReference("picture_" . $i)) {
            $picture = $this->getReference("picture_" . $i);
            $pictures[] = $picture;
            $i++;
        }

        // we retrieve the rooms from the references to be able to associate them with the products
        $rooms = [];
        $i = 0;

        while ($this->hasReference("room_" . $i)) {
            $room = $this->getReference("room_" . $i);
            $rooms[] = $room;
            $i++;
        }


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



            // add random number ( max 5) of unique picture to each product 

            $randomCount = rand(1, min(5, count($pictures)));
            $productPictures = [];

            for ($j = 0; $j < $randomCount; $j++) {

                $randomIndex = rand(0, count($pictures) - 1);
                $picture = $pictures[$randomIndex];

                if (!in_array($picture, $productPictures)) {
                    $productPictures[] = $picture;
                    $product->addPicture($picture);
                }
                // remove the picture from the pictures array to avoid adding it to another product
                array_splice($pictures, $randomIndex, 1);

                if (count($productPictures) == 5) {
                    break;
                }

            }

            // add a random number of unique room to roomProducts array 
            $randomCount = rand(1, count($rooms));
            $roomProducts = [];

            for ($k = 0; $k < $randomCount; $k++) {

                $randomIndex = rand(0, count($rooms) - 1);
                $room = $rooms[$randomIndex];

                if (!in_array($room, $roomProducts)) {
                    $roomProducts[] = $room;
                    $product->addRoom($room);
                }
            }

            $products[] = $product;
            $manager->persist($product);
        }


        //! Supplier

        $suppliers = [];
        $staffIndices = range(0, UserFixtures::UserCount - 1);
        $maxSupplierCount = floor(UserFixtures::UserCount / 4);


        // Boucle pour créer les fournisseurs
        for ($i = 0; $i < $maxSupplierCount; $i++) {
            $supplier = new Supplier();
            $supplier->setName($this->faker->company());
            $supplier->setDescription($this->faker->text(200));
            $supplier->setComments($this->faker->text(200));
            $supplier->setSlug($this->faker->slug(3, false));
            $supplier->setCreatedAt(new \DateTime($this->faker->date()));
            $supplier->setUpdatedAt(new \DateTime($this->faker->date()));

            // Ajouter jusqu'à 3 employés
            for ($k = 0; $k < rand(1, 4); $k++) {
                $randomStaffIndex = array_rand($staffIndices);
                $staffIndex = $staffIndices[$randomStaffIndex];
                if (isset($staffIndex)) {
                    $supplier->addStaff($this->getReference("user_" . $staffIndex));
                    unset($staffIndices[$randomStaffIndex]);
                }
            }

            // add a random number of unique products to supplierProducts array 
            $randomCount = rand(0, count($products));
            $supplierProducts = [];

            for ($j = 0; $j < $randomCount; $j++) {

                $randomIndex = rand(0, count($products) - 1);
                $product = $products[$randomIndex];

                if (!in_array($product, $supplierProducts)) {
                    $supplierProducts[] = $product;
                    $supplier->addProduct($product);
                }
            }


            $suppliers[] = $supplier;
            $manager->persist($supplier);
        }

        //! Order

        $orders = [];

        // we retrieve the dates from the references to be able to associate them with the orders
        $dates = [];
        $i = 0;

        while ($this->hasReference("date_" . $i)) {
            $date = $this->getReference("date_" . $i);
            $dates[] = $date;
            $i++;
        }

        for ($i = 0; $i < 50; $i++) {
            $order = new Order();
            $order->setSupplier($suppliers[array_rand($suppliers)]);
            $order->setDate($dates[array_rand($dates)]);
            $order->setSlug($this->faker->slug(3, false));
            $order->setName($this->faker->word());
            $order->setCreatedAt(new \DateTime($this->faker->date()));
            $order->setUpdatedAt(new \DateTime($this->faker->date()));

            $orders[] = $order;
            $manager->persist($order);
        }

        //! OrdersProducts

        foreach ($orders as $order) {
            // Nombre aléatoire de produits pour cette commande
            $numberOfProducts = rand(1, 5); // Par exemple, une commande peut contenir de 1 à 5 produits

            // Créer un tableau pour stocker les produits déjà ajoutés à cette commande
            $addedProducts = [];

            // Ajouter des produits à la commande jusqu'à ce que le nombre spécifié soit atteint
            for ($i = 0; $i < $numberOfProducts; $i++) {
                // Sélectionner un produit aléatoire
                $product = $products[array_rand($products)];

                // Vérifier si le produit a déjà été ajouté à cette commande
                if (!in_array($product, $addedProducts)) {
                    // Créer une relation entre la commande et le produit
                    $orderProduct = new OrdersProducts();
                    $orderProduct->setOrders($order);
                    $orderProduct->setProduct($product);
                    $orderProduct->setQuantity(rand(1, 100)); // Quantité aléatoire par produit
                    $orderProduct->setCreatedAt(new \DateTime($this->faker->date()));
                    $orderProduct->setUpdatedAt(new \DateTime($this->faker->date()));

                    // Ajouter le produit à la commande et le marquer comme ajouté
                    $manager->persist($orderProduct);
                    $addedProducts[] = $product;
                }
            }
        }


        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            InventoryFixtures::class,
        ];
    }
}