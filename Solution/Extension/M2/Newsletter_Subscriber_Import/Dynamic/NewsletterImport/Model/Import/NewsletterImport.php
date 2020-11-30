<?php
namespace Dynamic\NewsletterImport\Model\Import;

use Dynamic\NewsletterImport\Model\Import\NewsletterImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;

class NewsletterImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{

    const STOREID = 'store_id';
    const CHANGESTAUS = 'change_status_at';
    const CUSTOMERID = 'customer_id';
    const EMAIL = 'subscriber_email';
    const STATUS = 'subscriber_status';
    const SUBSCRIBERCONFIRM = 'subscriber_confirm_code';

    const TABLE_Entity = 'newsletter_subscriber';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'TITLE is empty',
    ];

     protected $_permanentAttributes = [self::EMAIL];
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    protected $groupFactory;
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::STOREID,
       /*  self::CHANGESTAUS,
        self::CUSTOMERID,*/
         self::EMAIL,
        self::STATUS,
        /*self::SUBSCRIBERCONFIRM*/
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;

    protected $_validators = [];


    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    protected $_resource;

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
    }
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'newsletter_subscriber';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {

        $title = false;

        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;

            if (!isset($rowData[self::EMAIL]) || empty($rowData[self::EMAIL])) {
                $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                return false;
            }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }


    /**
     * Create Advanced price data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }
    /**
     * Save newsletter subscriber
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Replace newsletter subscriber
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }
    /**
     * Deletes newsletter subscriber data from raw data.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowTtile = $rowData[self::EMAIL];
                    $listTitle[] = $rowTtile;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listTitle) {
            $this->deleteEntityFinish(array_unique($listTitle),self::TABLE_Entity);
        }
        return $this;
    }

    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowTtile= trim($rowData[self::EMAIL]);
                $store_id= trim($rowData[self::STOREID]);
                $listTitle[] = $rowTtile;

                if($rowTtile!='' && $store_id!='' && $rowData[self::STATUS]==1){
                    $entityList[$rowTtile.'-'.$store_id][] = [
                      self::STOREID => trim($rowData[self::STOREID]),
                      /*self::CHANGESTAUS => $rowData[self::CHANGESTAUS],
                      self::CUSTOMERID => $rowData[self::CUSTOMERID],*/
                      self::EMAIL => trim($rowData[self::EMAIL]),
                      self::STATUS => trim($rowData[self::STATUS]),
                      /*self::SUBSCRIBERCONFIRM => $rowData[self::SUBSCRIBERCONFIRM]*/
                    ];
               }else{
                        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/NewsletterImport.log');
                        $logger = new \Zend\Log\Logger();
                        $logger->addWriter($writer);
                        $logger->info(date('Y-m-d')." This email Id not imported: ".trim($rowData[self::EMAIL]));
               }
            }

            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                continue;
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
                $subscriberCollection = $objectManager->create('Magento\Newsletter\Model\ResourceModel\Subscriber\Collection');

                foreach($subscriberCollection as $subscriber):
                     /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
                        $logger = new \Zend\Log\Logger();
                        $logger->addWriter($writer);
                        $logger->info($subscriber->getSubscriberEmail().'-'.$subscriber->getStoreId());*/
                if(in_array($subscriber->getSubscriberEmail().'-'.$subscriber->getStoreId(), array_keys($entityList))){
                    unset($entityList[$subscriber->getSubscriberEmail().'-'.$subscriber->getStoreId()]);
                }
                endforeach;
                    $this->saveEntityFinish($entityList, self::TABLE_Entity);  
            }
        }
        return $this;
    }
    /**
     * Save product prices.
     *
     * @param array $priceData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                    foreach ($entityRows as $row) {
                        $entityIn[] = $row;
                    }
            }

            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                self::STOREID,
                /*self::CHANGESTAUS,
                self::CUSTOMERID,*/
                self::EMAIL,
                self::STATUS,
                /*self::SUBSCRIBERCONFIRM*/
            ]);
            }
        }
        return $this;
    }
    protected function deleteEntityFinish(array $listTitle, $table)
    {
        if ($table && $listTitle) {
                try {
                    $this->countItemsDeleted =+$this->_connection->delete(
                        $this->_connection->getTableName($table),
                        $this->_connection->quoteInto('subscriber_email IN (?)', $listTitle)
                   );
                    return true;
                } catch (\Exception $e) {
                    return false;
                }

        } else {
            return false;
        }
    }

}
