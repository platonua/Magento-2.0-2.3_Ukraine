<?php

namespace Platon\PlatonPay\Controller\Redirect;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var Session
     */
    protected $session;

    protected $session_manager;

    /**
     * @param Context     $context
     * @param PageFactory $pageFactory
     * @param Session     $session
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Session $session,
        SessionManager $session_manager
    ) {
        $this->pageFactory = $pageFactory;
        $this->session = $session;
        $this->session_manager = $session_manager;

        parent::__construct($context);
    }

    /**
     * Index Action
     */
    public function execute()
    {
        $this->session->setQuoteId($this->session->getPlatonQuoteId());
        $this->session->getLastRealOrder()->setStatus('pending_payment')->save();
        $this->session_manager->setPlatonQuoteId($this->session->getQuoteId());

        $data = $this->getData($this->session->getLastRealOrder()
            ->getIncrementId());
        $html = $this->getHtml($data);

        $this->getResponse()
            ->setBody($html);
    }

    private function getData($order)
    {
        $objectManager = ObjectManager::getInstance();
        $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $base_url = $storeManager->getStore()
            ->getBaseUrl();
        $settings = $objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $data = base64_encode(json_encode([
            'amount' => sprintf("%01.2f", $this->session->getLastRealOrder()
                ->getGrandTotal()),
            'name' => 'Order from ' . $storeManager->getStore()
                    ->getGroup()
                    ->getName(),
            'currency' => $this->session->getLastRealOrder()
                ->getGlobalCurrencyCode(),
        ]));
        $result = [
            'key' => $settings->getValue('payment/platon/key'),
            'payment' => 'CC',
            'data' => $data,
            'url' => $base_url . "platon_platon_pay/success/index",
            'action' => $settings->getValue('payment/platon/url'),
            'email' => $this->session->getLastRealOrder()->getCustomerEmail(),
            'phone' => $this->session->getLastRealOrder()->getShippingAddress()->getTelephone(),
            'order' => $order,
        ];

        $result['sign'] = hash(
            'md5',
            strtoupper(
                strrev($result['key']) .
                strrev($result['payment']) .
                strrev($result['data']) .
                strrev($result['url']) .
                strrev($settings->getValue('payment/platon/pass'))
            )
        );
        return $result;
    }

    private function getHtml($data)
    {
        $html ="<html>
                <body>
                    <form action='".$data['action']."' method='post' name='platon_checkout' id='platon_checkout'>";

        unset($data['action']);

        foreach ($data as $field => $value) {
            $html .= "<input hidden name='".$field."' value='".$value."'>";
        }

        $html .= "</form>
        " . __('You will be redirected to Platon when you place an order.') . "
        <script type=\"text/javascript\">document.getElementById(\"platon_checkout\").submit();</script>
        </body>
        </html>";

        return $html;
    }
}
