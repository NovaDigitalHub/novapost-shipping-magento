<?php

namespace Novapost\Shipping\Block\Adminhtml\Form\Render\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Warehouse extends Field
{
    /**
     * Warehouse constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Element HTML fragment
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = $this->getAfterElementHtml();

        return parent::_getElementHtml($element) . $html;
    }

    /**
     * Generates and returns HTML and JavaScript for dynamic warehouse loading based on the selected settlement.
     *
     * @return string
     */
    public function getAfterElementHtml()
    {
        return <<<EOT
        <script type="text/javascript">
        require(['jquery', 'domReady!'], function($) {
        	function loadWarehouses(settlement) {
                $.ajax({
                    url: '/rest/V1/novapost/warehouse',
                    type: 'POST',
                    dataType: 'json',
                    data: JSON.stringify({
                        settlementId: settlement,
                        source: true
                    }),
                    contentType: 'application/json',
                    beforeSend: function(xhr) {
                    },
                    success: function(response) {
                    	let items = JSON.parse(response);
                        let options = '';
                        $.each(items, function(index, item) {
                        	if (item.active) {
                        		options += '<option value="' + item.id + '" selected="selected">' + item.text + '</option>';
                        	} else {
                        		options += '<option value="' + item.id + '">' + item.text + '</option>';
                        	}

                        });
                        $('#carriers_novapost_division_id').html(options);
                    }
                });
        	}

            $('#carriers_novapost_settlement').change(function() {
                let settlement = $(this).val();
                if (settlement) {
                    loadWarehouses(settlement);
                }
            });
        });
        </script>
        EOT;
    }
}
