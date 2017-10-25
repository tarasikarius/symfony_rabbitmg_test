<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WorkController
 * @package AppBundle\Controller
 *
 * Producer for the 4th part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-four-php.html)
 */
class DirectLogsController extends Controller
{
    /**
     * @Route("/direct", name="direct")
     * @Method({"GET", "POST"})
     */
    public function directExampleAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('message', TextType::class)
            ->add('severity', ChoiceType::class,[
                'choices' => [
                    'Info' => 'info',
                    'Error' => 'error',
                    'Warning' => 'warning',
                ]
            ])
            ->add('send', SubmitType::class, array('label' => 'Send'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $severity = $data['severity'];
            $body = $data['message'];
            $message = json_encode([
                'body' => $body,
                'severity' => $severity,
            ]);


            $producer = $this->get('old_sound_rabbit_mq.direct_logs_producer');
            $producer->publish($message, $severity);

            $this->addFlash(
                'success',
                sprintf('Sent %s: "%s"', $severity, $body)
            );
        }

        return $this->render('hello/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
