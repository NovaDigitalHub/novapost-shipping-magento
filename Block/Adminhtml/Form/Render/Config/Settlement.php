<?php

namespace Novapost\Shipping\Block\Adminhtml\Form\Render\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Settlement extends Field
{
    /**
     * Settlement constructor.
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
     * Generates and returns HTML and JavaScript for dynamic settlement loading based on the selected country code.
     *
     * @return string
     */
    public function getAfterElementHtml()
    {
        return <<<EOT
        <script type="text/javascript">
        require(['jquery', 'domReady!'], function($) {
        	function loadSettlements(countryCode) {
                $.ajax({
                    url: '/rest/V1/novapost/settlement',
                    type: 'POST',
                    dataType: 'json',
                    data: JSON.stringify({
                        countryCode: countryCode
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
                        $('#carriers_novapost_settlement').html(options);
                    }
                });
        	}

            $('#carriers_novapost_country_code').change(function() {
                let countryCode = $(this).val();
                if (countryCode) {
                    loadSettlements(countryCode);
                }
            });
        });
        </script>
        EOT;
    }
}
