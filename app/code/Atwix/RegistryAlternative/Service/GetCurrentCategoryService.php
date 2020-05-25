<?php
/**
 * @author Atwix Team
 * @copyright Copyright (c) 2019 Atwix (https://www.atwix.com/)
 * @package Atwix_RegistryAlternative
 */
declare(strict_types=1);

namespace Atwix\RegistryAlternative\Service;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\SessionFactory as CatalogSessionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 *  GetCurrentCategoryService
 */
class GetCurrentCategoryService
{
    /**
     * Current Category
     *
     * @var CategoryInterface
     */
    private $currentCategory;

    /**
     * Current Category ID
     *
     * @var int|null
     */
    private $categoryId;

    /**
     * @var CatalogSessionFactory
     */
    private $catalogSessionfactory;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param CatalogSessionFactory       $catalogSessionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        CatalogSessionFactory $catalogSessionFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->catalogSessionfactory = $catalogSessionFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryId()
    {
        if (!$this->categoryId) {
            $catalogSessionFactory = $this->catalogSessionfactory->create();
            $currentCategoryId = $catalogSessionFactory->getData('last_viewed_category_id');

            if ($currentCategoryId) {
                $this->categoryId =  (int)$currentCategoryId;
            }
        }

        return $this->categoryId;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getCategory(): ?CategoryInterface
    {
        if (!$this->currentCategory) {
            $categoryId = $this->getCategoryId();

            if (!$categoryId) {
                return null;
            }

            try {
                $this->currentCategory = $this->categoryRepository->get($categoryId);
            } catch (NoSuchEntityException $e) {
                return null;
            }
        }

        return $this->currentCategory;
    }
}
