<?php

namespace Dynamic\Banners\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;
     protected $_adapterFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory;
     */
    protected $_uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem;
     */
    protected $_filesystem;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     */
    public function __construct(Action\Context $context, PostDataProcessor $dataProcessor, \Magento\Framework\Image\AdapterFactory $adapterFactory,\Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,\Magento\Framework\Filesystem $filesystem)
    {
        $this->dataProcessor = $dataProcessor;
        $this->_adapterFactory = $adapterFactory;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_filesystem = $filesystem;
        parent::__construct($context);
        
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dynamic_Banners::save');
    }

    /**
     * Save action
     *
     * @return void
     */
    public function execute()
    {
        

         $data = $this->getRequest()->getParams();
        if ($data) {

            $model = $this->_objectManager->create('Dynamic\Banners\Model\Banners');

            if(isset($_FILES['image']) && isset($_FILES['image']['name']) && strlen($_FILES['image']['name'])) {

                try {
                    $baseMediaPath = 'banners/';
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);
                    $filenameAdapter = $this->_adapterFactory->create();
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->addValidateCallback('image', $filenameAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $mediaDirectory = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save($mediaDirectory->getAbsolutePath($baseMediaPath));

                    
                    $image = $result['file'];
                    $absPath = $mediaDirectory->getAbsolutePath($baseMediaPath).$image;
                    $imageResized = $mediaDirectory->getAbsolutePath('smallbanner/banners/').$image;

                    $imageResize = $this->_adapterFactory->create();
                    $imageResize->open($absPath);
                    $imageResize->keepAspectRatio(false);
                    $imageResize->keepFrame(true);
                    $imageResize->backgroundColor(array(255,255,255));
                    $imageResize->resize(1000,800);                    
                    $imageResize->save($imageResized);
                    $data['image'] = $baseMediaPath.$result['file'];


                } catch (\Exception $e) {
                    
                    if($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }

                }
            } else {
                if(isset($data['image']) && isset($data['image']['value'])) 
                {
                    if(isset($data['image']['delete'])) 
                    {
                        $data['image'] = null;
                        $data['delete_filename'] = true;
                    } 
                    else if(isset($data['image']['value'])) 
                    {
                        $data['image'] = $data['image']['value'];
                    } 
                    else 
                    {
                        $data['image'] = null;
                    }
                }
            }
            $id = $this->getRequest()->getParam('banners_id');
            if ($id) {
                $model->load($id);
            }
            
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Banner Has been Saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('banners_id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('banners_id' => $this->getRequest()->getParam('banners_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
