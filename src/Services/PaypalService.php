<?php 
namespace App\Services;

use App\Repository\PaymentMethodRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class PaypalService {
    private $session;
    public function __construct(
        private RequestStack $requestStack,
        private PaymentMethodRepository $paymentMethodRepo,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        $this->session = $requestStack->getSession();
    }

    public function getPublicKey(){
        $config = $this->paymentMethodRepo->findOneByName("Paypal");

        if($_ENV['APP_ENV'] === 'dev'){
            //development
            return $config->getTestPublicApiKey();
        }else{
            //production
            return $config->getProdPublicApiKey();
        }
    }

    public function getPrivateKey(){
        $config = $this->paymentMethodRepo->findOneByName("Paypal");

        if($_ENV['APP_ENV'] === 'dev'){
            //development
            return $config->getTestPrivateApiKey();
        }else{
            //production
            return $config->getProdPrivateApiKey();
        }
    }
    public function getBaseUrl(){
        $config = $this->paymentMethodRepo->findOneByName("Paypal");

        if($_ENV['APP_ENV'] === 'dev'){
            //development
            return $config->getTestBaseUrl();
        }else{
            //production
            return $config->getProdBaseUrl();
        }
    }


}