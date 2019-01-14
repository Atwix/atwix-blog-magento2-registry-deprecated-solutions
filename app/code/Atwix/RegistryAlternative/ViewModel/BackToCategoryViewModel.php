<?php
/**
 * @author Atwix Team
 * @copyright Copyright (c) 2018 Atwix (https://www.atwix.com/)
 * @package Atwix_RegistryAlternative
 */

declare(strict_types=1);

namespace Atwix\RegistryAlternative\ViewModel;

use Atwix\RegistryAlternative\Service\GetCurrentCategoryService;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 *  BackToCategoryViewModel
 */
class BackToCategoryViewModel implements ArgumentInterface
{
    /**
     * @var CategoryInterface|null
     */
    protected $category;

    /**
     * @var GetCurrentCategoryService
     */
    private $currentCategoryService;

    /**
     * @param GetCurrentCategoryService $currentCategoryService
     */
    public function __construct(GetCurrentCategoryService $currentCategoryService)
    {
        $this->currentCategoryService = $currentCategoryService;
    }

    /**
     * @return null|string
     */
    public function getCategoryUrl(): ?string
    {
        if (!$this->getCategory()) {
            return '';
        }

        return $this->getCategory()->getUrl();
    }

    /**
     * @return null|string
     */
    public function getCategoryName(): ?string
    {
        if (!$this->getCategory()) {
            return '';
        }

        return $this->getCategory()->getName();
    }


    /**
     * @return CategoryInterface|null
     */
    private function getCategory(): ?CategoryInterface
    {
        if (!$this->category) {
            $this->category = $this->currentCategoryService->getCategory();
        }

        return $this->category;
    }
}
