<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function saveCustomer($firstName, $lastName, $email, $phoneNumber){

        $newCustomer = new Customer();

        $newCustomer
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPhoneNumber($phoneNumber);

        $this->_em->persist($newCustomer);
        $this->_em->flush();
    }

   public function updateCustomer(Customer $customer){

        $this->_em->persist($customer);
        $this->_em->flush();

        return $customer;
   }

   public function deleteCustomer(Customer $customer){
        $this->_em->remove($customer);
        $this->_em->flush();
   }
}
