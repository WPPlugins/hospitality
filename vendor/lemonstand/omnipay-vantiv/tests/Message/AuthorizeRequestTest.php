<?php namespace Omnipay\Vantiv\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Common\CreditCard;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
                'description' => 'Order #42',
                'orderId' => '42',
                'customerId' => '1'
            )
        );
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('AuthorizeRequestSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testAuthorizeSendError()
    {
        $this->setMockHttpResponse('NoMessageNoResponseCodeFailure.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getResponseCode());
    }

    public function testSendInsufficentFunds()
    {
        $this->setMockHttpResponse('AuthorizeRequestInsufficientFunds.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Insufficient Funds', $response->getMessage());
        $this->assertSame('110', $response->getResponseCode());
    }

    public function testPreLiveMode()
    {
        $this->assertSame($this->request, $this->request->setPreLiveMode(true));
        $this->assertSame(true, $this->request->getPreLiveMode());
    }

    public function testReportGroup()
    {
        $this->assertSame($this->request, $this->request->setReportGroup('test-group-1'));
        $this->assertSame('test-group-1', $this->request->getReportGroup());
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame("1200", (string) $data->authorization->amount);
    }

    public function testGetPreliveEndpoint()
    {
        $this->assertSame($this->request, $this->request->setPreLiveMode(true));
        $this->assertSame('https://transact-prelive.litle.com/vap/communicator/online', $this->request->getEndpoint());
    }

    public function testTestEndpoint()
    {
        $this->assertSame($this->request, $this->request->setTestMode(true));
        $this->assertSame('https://www.testlitle.com/sandbox/communicator/online', $this->request->getEndpoint());
    }

    public function testPreLiveModeEndpointPrecedence()
    {
        $this->assertSame($this->request, $this->request->setPreLiveMode(true));
        $this->assertSame($this->request, $this->request->setTestMode(true));
        $this->assertSame('https://transact-prelive.litle.com/vap/communicator/online', $this->request->getEndpoint());
    }

    public function testLiveEndpoint()
    {
        $this->assertSame('https://transact.litle.com/vap/communicator/online', $this->request->getEndpoint());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The card parameter is required
     */
    public function testCardRequired()
    {
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->assertSame($this->request, $this->request->setCard($card));
        $data = $this->request->getData();
        $this->assertSame($card['number'], (string) $data->authorization->card->number);
    }

    public function testDataWithToken()
    {
        $this->assertSame($this->request, $this->request->setToken('1234'));
        $data = $this->request->getData();
        $this->assertSame('1234', (string) $data->authorization->token->litleToken);
    }

    public function testGetCardType()
    {
        $codes = array(
            CreditCard::BRAND_AMEX        => 'AX',
            CreditCard::BRAND_DINERS_CLUB => 'DC',
            CreditCard::BRAND_DISCOVER    => 'DI',
            CreditCard::BRAND_JCB         => 'JC',
            CreditCard::BRAND_MASTERCARD  => 'MC',
            CreditCard::BRAND_VISA        => 'VI'
        );

        $this->assertSame('VI', $this->request->getCreditType(CreditCard::BRAND_VISA));
        $this->assertSame('DC', $this->request->getCreditType(CreditCard::BRAND_DINERS_CLUB));
        $this->assertSame('DI', $this->request->getCreditType(CreditCard::BRAND_DISCOVER));
        $this->assertSame('JC', $this->request->getCreditType(CreditCard::BRAND_JCB));
        $this->assertSame('MC', $this->request->getCreditType(CreditCard::BRAND_MASTERCARD));
        $this->assertSame('AX', $this->request->getCreditType(CreditCard::BRAND_AMEX));
        $this->assertNull($this->request->getCreditType('NOTIN'));
    }
}
