<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;

use App\Service\StripeService;

class PaymentController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(Request $request, StripeService $stripeService): RedirectResponse
    {
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $totalAmount = $request->request->get('totalAmount');

        $checkout_session = $stripeService->checkout($totalAmount);

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/success', name: 'stripe_success')]
    public function success() {
        return $this->render('stripe/success.html.twig');
    }

    #[Route('/cancel', name: 'stripe_cancel')]
    public function cancel() {
        return $this->render('stripe/cancel.html.twig');
    }
}
