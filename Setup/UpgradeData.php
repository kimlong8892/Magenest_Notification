<?php


namespace Magenest\Notification\Setup;
use Magento\Catalog\Model\Product;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;


class UpgradeData implements UpgradeDataInterface
{

    protected $customerSetupFactory;
    protected $_customerSetupAddAttribute;
    private $attributeSetFactory;
    private $eavSetupFactory;

    public function __construct
    (
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.1.1') < 0) {
            $this->addAttributeReceivedCustomer($setup);
            $this->addAttributeViewedCustomer($setup);
        }
    }
    private function addAttributeReceivedCustomer($setup)
    {
        $setup->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'notification_received', [
            'type' => 'text',
            'label' => 'notification_received',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => false,
            'sort_order' => 210,
            'position' => 210,
            'system' => false,
        ]);
        $image = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'notification_received')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
            ]);
        $image->save();
        $setup->endSetup();
    }
    private function addAttributeViewedCustomer($setup)
    {
        $setup->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'notification_viewed', [
            'type' => 'text',
            'label' => 'notification_viewed',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => false,
            'sort_order' => 210,
            'position' => 210,
            'system' => false,
        ]);
        $image = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'notification_viewed')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
            ]);
        $image->save();
        $setup->endSetup();
    }




}