<?php 
namespace App\Services;

use App\Repository\PaymentMethodRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class StripeService {

    public function __construct(
        private RequestStack $requestStack,
        private PaymentMethodRepository $paymentMethodRepo,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        $this->session = $requestStack->getSession();
    }

    public function getPublicKey(){
        $config = $this->paymentMethodRepo->findOneByName("Stripe");

        if($_ENV['APP_ENV'] === 'dev'){
            //development
            return $config->getTestPublicApiKey();
        }else{
            //production
            return $config->getProdPublicApiKey();
        }
    }

    public function getPrivateKey(){
        $config = $this->paymentMethodRepo->findOneByName("Stripe");

        if($_ENV['APP_ENV'] === 'dev'){
            //development
            return $config->getTestPrivateApiKey();
        }else{
            //production
            return $config->getProdPrivateApiKey();
        }
    }


}