<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    public function em(){
        return $this->getDoctrine()->getManager()->getRepository(Customer::class);

    }

    /**
     * @Route("/customer/add", name="add_customer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addCustomers(Request $request): JsonResponse
    {
        $em = $this->em();

        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phoneNumber = $data['phoneNumber'];


        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $em->saveCustomer($firstName, $lastName, $email, $phoneNumber);

        return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/customers/get/{id}", name="get_costomer", methods={"GET"})
     */
    public function getCustomer($id) :JsonResponse{

        $em = $this->em();

        $getAllCustomers = $em->findOneBy(['id'=>$id]);

        $data = [
            'id' => $getAllCustomers->getId(),
            'firstName' => $getAllCustomers->getFirstName(),
            'lastName' => $getAllCustomers->getLastName(),
            'email' => $getAllCustomers->getEmail(),
            'phoneNumber' => $getAllCustomers->getPhoneNumber(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/customers/get_all", name="get_all_costomers", methods={"GET"})
     */
    public function getAllCustomers() :JsonResponse{

        $em = $this->em();

        $getAllCustomers = $em->findAll();
        $data = [];

        foreach ($getAllCustomers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/customers/update/{id}", name="update_customer", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCustomers($id, Request $request): JsonResponse {

        $em = $this->em();

        $customer = $em->findOneBy(['id'=>$id]);
        $data = json_decode($request->getContent(), true);

        empty($data['firstName']) ? true : $customer->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $customer->setLastName($data['lastName']);
        empty($data['email']) ? true : $customer->setEmail($data['email']);
        empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);

        $updatedCostumer = $em->updateCustomer($customer);

        return new JsonResponse($updatedCostumer->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route ("/customers/delete/{id}", name="delete_customer", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function deleteCustomer($id): JsonResponse{

        $em = $this->em();

        $customer = $this->em()->findOneBy(['id'=>$id]);
        $em->deleteCustomer($customer);

        return new JsonResponse(['status' => 'Customer deleted'], Response::HTTP_NO_CONTENT);
    }
}
