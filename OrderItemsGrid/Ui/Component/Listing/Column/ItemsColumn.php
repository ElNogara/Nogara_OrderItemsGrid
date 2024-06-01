<?php
/**
 * Copyright © Nogara. All rights reserved.
 */

declare(strict_types=1);

namespace Nogara\OrderItemsGrid\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

/**
 * @package Nogara\OrderItemsGrid\Ui\Component\Listing\Column
 */
class ItemsColumn extends Column
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array $dataSource
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $orderId = $item['entity_id'];
                $order = $this->orderRepository->get($orderId);

                $productDetails = [];
                foreach ($order->getAllItems() as $orderItem) {
                    $productDetails[] = sprintf(
                        '<strong>SKU:</strong>&nbsp;%s&nbsp;:&nbsp;<strong>QTD:</strong>&nbsp;%d',
                        $orderItem->getSku(),
                        $orderItem->getQtyOrdered()
                    );
                }

                $item[$this->getData('name')] = implode('<br>', $productDetails);
            }
        }

        return $dataSource;
    }
}
