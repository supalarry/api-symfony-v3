<?php


namespace App;


use App\Entity\Products;
use App\Exception\CreateProductServiceException;
use App\Exception\JsonToArrayException;
use App\Exception\ValidateProductException;
use App\Interfaces\IHandle;
use App\Interfaces\IProductsRepository;
use App\Interfaces\IReturn;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateProduct
{
    private $request;
    private $repository;
    private $userIdValidator;

    public function __construct(RequestStack $requestStack, IProductsRepository $repository, UserIdValidator $userIdValidator)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->repository = $repository;
        $this->userIdValidator = $userIdValidator;
    }

    public function handle(int $id_user): IReturn
    {
        if (!$this->userIdValidator->validate($id_user))
            throw new CreateProductServiceException(["id" => "invalid user"]);

        try {
            /* get json data */
            $converter = new JsonToArray($this->request);
            $dataArray = $converter->retrieve();
            /* validate data */
            $validateProduct = new ValidateProduct($dataArray, new ProductTypeValidator(), new TitleValidator(), new SkuValidator($this->repository), new ErrorsLoader());
            $validateProduct->validateKeys();
            /* create user */
            $newProduct = $this->repository->create([
                Products::PRODUCT_OWNER_ID => $id_user,
                Products::PRODUCT_TYPE => $dataArray[Products::PRODUCT_TYPE],
                Products::PRODUCT_TITLE => $dataArray[Products::PRODUCT_TITLE],
                Products::PRODUCT_SKU => $dataArray[Products::PRODUCT_SKU],
                Products::PRODUCT_COST => $dataArray[Products::PRODUCT_COST]
            ]);
        } catch (JsonToArrayException $e) {
            throw new CreateProductServiceException($e->getErrors());
        } catch (ValidateProductException $e) {
            throw new CreateProductServiceException($e->getErrors());
        } catch (ORMException $e) {
            throw new CreateProductServiceException(array($e));
        }
        /* return created user object for JSON response body */
        return new ReturnProduct(
            $newProduct->getId(),
            $newProduct->getOwnerId(),
            $newProduct->getType(),
            $newProduct->getTitle(),
            $newProduct->getSku(),
            $newProduct->getCost()
        );
    }
}