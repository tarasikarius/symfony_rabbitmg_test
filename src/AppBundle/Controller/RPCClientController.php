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
 * RPC Client
 *
 * Class RPCClientController
 * @package AppBundle\Controller
 */
class RPCClientController extends Controller
{
    /**
     * @Route("/rpc-client", name="rpc_client")
     * @Method({"GET", "POST"})
     */
    public function directExampleAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('message', TextType::class, [
                'data' => 'http://www.rollingstone.com/music/rss'
            ])
            ->add('send', SubmitType::class, array('label' => 'Send'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $message = json_encode(['url' => $data['message']]);

            $client = $this->get('old_sound_rabbit_mq.parse_url_rpc');
            $client->addRequest(serialize($message), 'parse_url', 'request_id');
            $replies = $client->getReplies();

            $this->addFlash('success', $replies['request_id']);
        }

        return $this->render('hello/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
