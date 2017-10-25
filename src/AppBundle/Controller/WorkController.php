<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WorkController
 * @package AppBundle\Controller
 *
 * Producer for the 2nd part of a rabbitMQ tutorial(https://www.rabbitmq.com/tutorials/tutorial-two-php.html)
 */
class WorkController extends Controller
{
    /**
     * @Route("/work", name="work")
     * @Method({"GET", "POST"})
     */
    public function workExampleAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('message', TextType::class)
            ->add('send', SubmitType::class, array('label' => 'Send'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $message = $data['message'];

            for ($i = random_int(1, 10); $i > 0; $i--) {
                $message .= '.';
            }

            $producer = $this->get('old_sound_rabbit_mq.work_producer');
            $producer->publish($message, 'work');

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
