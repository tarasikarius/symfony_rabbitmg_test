<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HelloController
 * @package AppBundle\Controller
 *
 * Producer for the 1st part of rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-one-php.html)
 */
class HelloController extends Controller
{
    /**
     * @Route("/hello", name="hello")
     * @Method({"GET", "POST"})
     */
    public function helloAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('message', TextType::class)
            ->add('send', SubmitType::class, array('label' => 'Send'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $message = $data['message'];

            $producer = $this->get('old_sound_rabbit_mq.hello_producer');
            $producer->publish($message, 'hello');

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
