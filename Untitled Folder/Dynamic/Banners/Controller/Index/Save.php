<?php

namespace Dynamic\Banners\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Magento\Framework\App\Action\Action
{
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $resultFactory;
	 protected $_uploaderFactory;
    protected $_filesystem;
    
     protected $_adapterFactory;
	/**
     * @param \Magento\Framework\App\Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $adapterFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultFactory;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_filesystem = $filesystem;
        $this->_adapterFactory = $adapterFactory;
        parent::__construct($context);
    }
	
    /**
     * Default Banners Index page
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
            }
            if(isset($_FILES['doc']) && isset($_FILES['doc']['name']) && strlen($_FILES['doc']['name'])) {

                try {
                    $baseMediaPath = 'banners/';
                    $uploader = $this->_uploaderFactory->create(['fileId' => 'doc']);
                    $filenameAdapter = $this->_adapterFactory->create();
                    $uploader->setAllowedExtensions(['pdf', 'xls', 'doc', 'docx','xls']);
                    $uploader->addValidateCallback('doc', $filenameAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $mediaDirectory = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $result = $uploader->save($mediaDirectory->getAbsolutePath($baseMediaPath));

                    
                    $doc = $result['file'];
                    $absPath = $mediaDirectory->getAbsolutePath($baseMediaPath).$image;
                    $imageResized = $mediaDirectory->getAbsolutePath('smallbanner/banners/').$doc;

                    $data['doc'] = $baseMediaPath.$result['file'];


                } catch (\Exception $e) {
                    
                    if($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }

                }
            } 
            
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Banner Has been Saved.'));
                
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            
        }
        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl('index');
        return $redirect;
    }
}
