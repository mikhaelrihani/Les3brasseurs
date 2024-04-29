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
       
        $pictures = [];
        $i = 0;
        while ($this->hasReference("picture_" . $i)) {
            $picture = $this->getReference("picture_" . $i);
            $pictures[] = $picture;
            $i++;
        }
    
    
        //! SupplyType

        $supplytypes = [];
        for ($i = 0; $i < 12; $i++) {
            $supplytype = new SupplyType();
            $supplytype
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $supplytypes[] = $supplytype;
            $manager->persist($supplytype);
        }

        //! Product

       
       
        // We fetch the rooms from the references to link them with the products
        $rooms = [];
        $l = 0;
        while ($this->hasReference("room_" . $l)) {
            $room = $this->getReference("room_" . $l);
            $rooms[] = $room;
            $l++;
        }

        $products = [];

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product
                ->setSupplyType($supplytypes[array_rand($supplytypes)])
                ->setPrice($this->faker->randomFloat(2, 0, 1000))
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setCurrency($this->faker->currencyCode())
                ->setConditionning($this->faker->text(10))
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);




            // Each product can have multiple unique pictures ,each picture must be unique to a product.
            $maxNbPictures = (count($pictures) > 300) ? 300 : count($pictures);
            $nbPictures = rand(1, $maxNbPictures);
            $productPictures = [];

            for ($k = 0; $k <= $nbPictures; $k++) {
                $randomIndex = array_rand($pictures);
                $selectedPictures = $pictures[$randomIndex];

                if (!in_array($selectedPictures, $productPictures)) {
                    $product->addPicture($selectedPictures);
                    $productPictures[] = $selectedPictures;
                    $k++;
                }
                // remove the picture from the pictures array to avoid adding it to another product
                array_splice($pictures, $randomIndex, 1);
            }

            // Each product can have multiple unique rooms
            $nbRooms = rand(1, count($rooms));
            $nbMaxRooms = ($nbRooms > 4) ? 4 : $nbRooms;
            $roomProducts = [];

            for ($k = 0; $k < $nbMaxRooms; ) {
                $randomRoomIndex = rand(array_rand($rooms), 1);
                $selectedRooms = $rooms[$randomRoomIndex];

                if (!in_array($selectedRooms, $roomProducts)) {
                    $roomProducts[] = $selectedRooms;
                    $product->addRoom($selectedRooms);
                    $k++;
                }
            }

            $products[] = $product;
            $manager->persist($product);
        }


        //! Supplier

        $suppliers = [];

        // We fetch the users from the references to link them with the suppliers
        $users = [];
        $i = 0;

        while ($this->hasReference("user_" . $i)) {
            $user = $this->getReference("user_" . $i);
            $users[] = $user;
            $i++;
        }

        $nbSuppliers = floor(count($users) / 4);

        for ($i = 0; $i < $nbSuppliers; $i++) {
            
            $supplier = new Supplier();
            $supplier
                ->setName($this->faker->unique()->company())
                ->setDescription($this->faker->text(200))
                ->setComments($this->faker->text(200))
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            // Each supplier can have multiple unique staffs and a staff cannot be part of multiple suppliers
            $maxNbStaffs = (count($users) > 4) ? 4 : count($users);
            $nbStaffs = rand(1, $maxNbStaffs);
            $staffs = [];

            for ($k = 0; $k < $nbStaffs; ) {
                $randomStaffIndex = array_rand($users, 1);
                $selectedStaff = $users[$randomStaffIndex];
                if (!in_array($selectedStaff, $staffs)) {
                    $supplier->addStaff($selectedStaff);
                    $staffs[] = $selectedStaff;
                    $k++;
                }
                array_splice($staffs, $randomStaffIndex, 1);
            }

            // Each supplier can have multiple unique products
            $maxNbProducts = (count($products) > 50) ? 50 : count($products);
            $nbProducts = rand(1, $maxNbProducts);
            $supplierProducts = [];

            for ($j = 0; $j < $nbProducts; ) {
                $randomProductIndex = array_rand($products);
                $selectedProduct = $products[$randomProductIndex];
                if (!in_array($selectedProduct, $supplierProducts)) {
                    $supplier->addProduct($selectedProduct);
                    $supplierProducts[] = $selectedProduct;
                    $j++;
                }
            }
                $suppliers[] = $supplier;
                $this->addReference("supplier_" . $i, $supplier);
                $manager->persist($supplier);
            
        }
        //! Order

        $orders = [];

        // We fetch the dates from the references to link them with the orders.
        $dates = [];
        $i = 0;
        while ($this->hasReference("date_" . $i)) {
            $date = $this->getReference("date_" . $i);
            $dates[] = $date;
            $i++;
        }

        for ($i = 0; $i < 50; $i++) {
            $order = new Order();
            $order
                ->setSupplierName($this->getReference("supplier_" . rand(0, $nbSuppliers -1))->getName())
                ->setDate($dates[array_rand($dates)])
                ->setSlug($this->faker->unique()->slug(3, false))
                ->setName($this->faker->unique()->word())
                ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                ->setCreatedAt($this->createdAt);

            $orders[] = $order;
            $manager->persist($order);
        }

        //! OrdersProducts

        foreach ($orders as $order) {

            $numberOfProducts = rand(1, 20);
            $addedProducts = [];

            for ($i = 0; $i < $numberOfProducts; $i++) {
                $product = $products[array_rand($products)];

                if (!in_array($product, $addedProducts)) {
                    $orderProduct = new OrdersProducts();
                    $orderProduct
                        ->setOrders($order)
                        ->setProduct($product)
                        ->setQuantity(rand(1, 100))
                        ->setUpdatedAt($this->faker->dateTimeBetween($this->createdAt, 'now'))
                        ->setCreatedAt($this->createdAt);

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