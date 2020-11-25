<?php 

namespace Dynamic\Banners\Controller\Adminhtml\Index;

class MassDelete extends \Magento\Backend\App\Action { 
    
    public function execute() {
        
        $ids = $this->getRequest()->getParam('banners_id');
         
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select Banner(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_objectManager->create('Dynamic\Banners\Model\Banners')->load($id);
                    $row->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
         $this->_redirect('*/*/');
    }
}
