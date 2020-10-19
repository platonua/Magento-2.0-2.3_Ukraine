<?php
namespace Platon\PlatonPay\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\ObjectManager;
use Platon\PlatonPay\Gateway\Http\Client\ClientMock;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'platon_pay';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        ClientMock::SUCCESS => __('Success'),
                        ClientMock::FAILURE => __('Fraud')
                    ],
                    'policy' => ObjectManager::getInstance()
                        ->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
                        ->getValue('payment/platon/policy')
                ]
            ]
        ];
    }
}
