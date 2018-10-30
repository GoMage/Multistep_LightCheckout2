<?php

namespace GoMage\SuperLightCheckout\Model\PostCode;

use GoMage\SuperLightCheckout\Model\ResourceModel\PostCode\Collection;

class EmptyCollection
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function execute()
    {
        $this->collection->walk('delete');
    }
}
