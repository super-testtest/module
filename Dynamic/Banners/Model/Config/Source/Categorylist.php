<?php 

namespace Dynamic\Banners\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Categorylist implements ArrayInterface
{
    /*  
     * Option getter
     * @return array
     */
    public function toOptionArray()
    {   
        $arr = $this->toArray();
        $ret = [];

        foreach ($arr as $key => $value)
        {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    /*
     * Get options in "key-value" format
     * @return array
     */
    public function toArray()
    {
        $cateArr = [];
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $categoryCollection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
        $categories = $categoryCollection->create();
        $categories->addAttributeToSelect('*');
        
        foreach ($categories as $category) {
            $cateArr[$category->getId()] = $category->getName();
        }

        return $cateArr;
    }
}