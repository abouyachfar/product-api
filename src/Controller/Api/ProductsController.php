<?php

namespace App\Controller\Api;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Exception;

#[Route(path: "/api/products", name: "products")]
class ProductsController extends AbstractController {

    #[Route(path: "/", name: "post", methods: ["POST"], format: "JSON")]
    public function post(
        LoggerInterface $logger,
        Request $request, 
        ValidatorInterface $validator, 
        ProductService $productService
    ): JsonResponse {

        $httpCode = Response::HTTP_CREATED;
        $productDto = new ProductDTO();

        try {
            // Create DTO
            $data = json_decode($request->getContent(), true);
            $productDto->name = !empty($data['name']) ? $data['name'] : "";
            $productDto->price = !empty($data['price']) ? (float)$data['price'] : 0;

            // Validate DTO
            $errors = $validator->validate($productDto);

            // Creat Prduct if DTO is valide
            if (count($errors) > 0) {
                $httpCode = Response::HTTP_BAD_REQUEST;
            } else {
                $productDto = $productService->create($productDto);
            }
        } catch (Exception $e) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $logger->error("[Controller : ProductsController - Action : post] : " . $e->getMessage());
        }

        return new JsonResponse($productDto, $httpCode, []);
    }

    #[Route(path: "/{id}", name: "get", methods: ["GET"])]
    public function get(
        string $id,
        LoggerInterface $logger,
        Request $request,
        ProductService $productService
    ): JsonResponse {

        $httpCode = Response::HTTP_OK;
        $productDto = null;

        try {
            // Search Product and Return DTO
            $productDto = $productService->search($id);

            if (empty($productDto)) {
                $httpCode = Response::HTTP_NOT_FOUND;
            }
        } catch (Exception $e) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $logger->error("[Controller : ProductsController - Action : get] : " . $e->getMessage());
        }

        return new JsonResponse($productDto, $httpCode, []);
    }

    #[Route(path: "/", name: "put", methods: ["PUT"], format: "JSON")]
    public function put(
        LoggerInterface $logger,
        Request $request, 
        ValidatorInterface $validator, 
        ProductService $productService
    ): JsonResponse {

        $httpCode = Response::HTTP_OK;
        $productDto = new ProductDTO();
        $errors = array();

        try {
            // Create DTO
            $data = json_decode($request->getContent(), true);
            $productDto->id = !empty($data['id']) ? $data['id'] : "";
            $productDto->name = !empty($data['name']) ? $data['name'] : "";
            $productDto->price = !empty($data['price']) ? (float)$data['price'] : 0;

            // Validate DTO
            $errors = $validator->validate($productDto);

            if (count($errors) > 0 || empty($data['id'])) {
                $httpCode = Response::HTTP_BAD_REQUEST;
            } else {
                $productDtoSearched = $productService->search($productDto->id);

                if (!empty($productDtoSearched)) {
                    $productDto = $productService->update($productDto);
                } else {
                    $httpCode = Response::HTTP_NOT_FOUND;
                    $productDto = null;
                }
            }
        } catch (Exception $e) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $logger->error("[Controller : ProductsController - Action : put] : " . $e->getMessage());
        }

        return new JsonResponse($productDto, $httpCode, []);
    }

}