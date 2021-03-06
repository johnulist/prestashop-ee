<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/prestashop-ee/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/prestashop-ee/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Models;

use Wirecard\PaymentSdk\Transaction\PoiPiaTransaction;
use WirecardEE\Prestashop\Helper\AdditionalInformationBuilder;

/**
 * Class PaymentPoiPia
 *
 * @extends Payment
 *
 * @since 1.0.0
 */
class PaymentPoiPia extends Payment
{
    /**
     * @var string
     * @since 2.1.0
     */
    const TYPE = PoiPiaTransaction::NAME;

    /**
     * @var string
     * @since 2.5.0
     */
    const PIA = 'pia';

    /**
     * @var string
     * @since 2.1.0
     */
    const TRANSLATION_FILE = "paymentpoipia";

    /**
     * PaymentPoiPia constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = self::TYPE;
        $this->name = 'Wirecard Payment on Invoice / Payment in Advance';
        $this->formFields = $this->createFormFields();
    }

    /**
     * Create form fields for POI/PIA
     *
     * @return array|null
     * @since 1.0.0
     */
    public function createFormFields()
    {
        return array(
            'tab' => 'POIPIA',
            'fields' => array(
                array(
                    'name' => 'enabled',
                    'label' => $this->getTranslatedString('text_enable'),
                    'type' => 'onoff',
                    'doc' => $this->getTranslatedString('enable_heading_title_poi_pia'),
                    'default' => 0,
                ),
                array(
                    'name' => 'title',
                    'label' => $this->getTranslatedString('config_title'),
                    'type' => 'text',
                    'default' => $this->getTranslatedString('heading_title_poi_pia'),
                    'required' => true,
                ),
                array(
                    'name' => 'merchant_account_id',
                    'label'   => $this->getTranslatedString('config_merchant_account_id'),
                    'type'    => 'text',
                    'default' => '105ab3e8-d16b-4fa0-9f1f-18dd9b390c94',
                    'required' => true,
                ),
                array(
                    'name' => 'secret',
                    'label'   => $this->getTranslatedString('config_merchant_secret'),
                    'type'    => 'text',
                    'default' => 'dbc5a498-9a66-43b9-bf1d-a618dd399684',
                    'required' => true,
                ),
                array(
                    'name' => 'base_url',
                    'label'       => $this->getTranslatedString('config_base_url'),
                    'type'        => 'text',
                    'doc' => $this->getTranslatedString('config_base_url_desc'),
                    'default'     => 'https://api-test.wirecard.com',
                    'required' => true,
                ),
                array(
                    'name' => 'http_user',
                    'label'   => $this->getTranslatedString('config_http_user'),
                    'type'    => 'text',
                    'default' => '70000-APITEST-AP',
                    'required' => true,
                ),
                array(
                    'name' => 'http_pass',
                    'label'   => $this->getTranslatedString('config_http_password'),
                    'type'    => 'text',
                    'default' => 'qD2wzQ_hrc!8',
                    'required' => true,
                ),
                array(
                    'name' => 'payment_type',
                    'type'    => 'select',
                    'default' => 'pia',
                    'label'   => $this->getTranslatedString('config_payment_type'),
                    'options' => array(
                        array('key' => 'pia', 'value' => $this->getTranslatedString('text_payment_type_pia')),
                        array('key' => 'poi', 'value' => $this->getTranslatedString('text_payment_type_poi')),
                    ),
                ),
                array(
                    'name' => 'payment_action',
                    'type'    => 'hidden',
                    'default' => 'reserve',
                ),
                array(
                    'name' => 'descriptor',
                    'label'   => $this->getTranslatedString('config_descriptor'),
                    'type'    => 'onoff',
                    'default' => 0,
                ),
                array(
                    'name' => 'send_additional',
                    'label'   => $this->getTranslatedString('config_additional_info'),
                    'type'    => 'onoff',
                    'default' => 1,
                ),
                array(
                    'name' => 'test_credentials',
                    'type' => 'linkbutton',
                    'required' => false,
                    'buttonText' => $this->getTranslatedString('test_config'),
                    'id' => 'poipiaConfig',
                    'method' => 'poipia',
                    'send' => array(
                        'WIRECARD_PAYMENT_GATEWAY_POIPIA_BASE_URL',
                        'WIRECARD_PAYMENT_GATEWAY_POIPIA_HTTP_USER',
                        'WIRECARD_PAYMENT_GATEWAY_POIPIA_HTTP_PASS'
                    )
                )
            )
        );
    }

    /**
     * Create PoiPiaTransaction
     *
     * @param \WirecardPaymentGateway $module
     * @param \Cart $cart
     * @param array $values
     * @param int $orderId
     * @return PoiPiaTransaction
     * @since 1.0.0
     */
    public function createTransaction($operation = null)
    {
        $context = \Context::getContext();
        $cart = $context->cart;
        $transaction = $this->createTransactionInstance($operation);

        $additionalInformation = new AdditionalInformationBuilder();
        $transaction->setAccountHolder($additionalInformation->createAccountHolder($cart, 'billing'));

        return $transaction;
    }

    /**
     * Get a clean transaction instance for this payment type.
     *
     * @param string $operation
     * @return PoiPiaTransaction
     * @since 2.4.0
     */
    public function createTransactionInstance($operation = null)
    {
        return new PoiPiaTransaction();
    }
}
