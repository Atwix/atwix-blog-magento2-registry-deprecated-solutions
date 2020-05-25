<?php
/**
 * @author Atwix Team
 * @copyright Copyright (c) 2018 Atwix (https://www.atwix.com/)
 * @package Atwix_RegistryAlternative
 */

declare(strict_types=1);

namespace Atwix\RegistryAlternative\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\SessionFactory as CatalogSessionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 *  CurrentProductDataProvider
 */
class GetCurrentProductService
{
    /**
     * Current Product
     *
     * @var ProductInterface
     */
    private $currentProduct;

    /**
     * Current Product ID
     *
     * @var int|null
     */
    private $productId;

    /**
     * @var CatalogSessionFactory
     */
    private $catalogSessionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param CatalogSessionFactory      $catalogSessionFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CatalogSessionFactory $catalogSessionFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->catalogSessionFactory = $catalogSessionFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        if (!$this->productId) {
            $catalogSessionFactory = $this->catalogSessionFactory->create();
            $productId = $catalogSessionFactory->getData('last_viewed_product_id');
            $this->productId = $productId ? (int)$productId : null;
        }

        return $this->productId;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        if (!$this->currentProduct) {
            $productId = $this->getProductId();

            if (!$productId) {
                return null;
            }

            try {
                $this->currentProduct =  $this->productRepository->getById($this->getProductId());
            } catch (NoSuchEntityException $e) {
                return null;
            }
        }

        return $this->currentProduct;
    }
}
