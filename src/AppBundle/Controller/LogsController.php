<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LogsController
 * @package AppBundle\Controller
 *
 * Producer for the 3nd part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-three-php.html)
 */
class LogsController extends Controller
{
    /**
     * @Route("/logs", name="logs")
     * @Method({"GET", "POST"})
     */
    public function logsExampleAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('message', TextType::class)
            ->add('send', SubmitType::class, array('label' => 'Send'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $message = $data['message'];

            $producer = $this->get('old_sound_rabbit_mq.logs_producer');
            $producer->publish($message);

            $this->addFlash(
                'success',
                sprintf('Message "%s" send to consumer!', $message)
            );
        }

        return $this->render('hello/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
