<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Entity\Enquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $blogs = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('BlogBundle:Blog')
            ->getLatestBlogs();

        return $this->render('BlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    public function aboutAction()
    {
        return $this->render('BlogBundle:Page:about.html.twig');
    }

    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom('test@mail.com')
                    ->setTo($this->container->getParameter('blog.emails.contact_email'))
                    ->setBody($this->renderView('BlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));

                $this->get('mailer')->send($message);


                $this->get('session')
                    ->getFlashBag()
                    ->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

                return $this->redirect($this->generateUrl('BlogBundle_contact'));
            }
        }

        return $this->render('BlogBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function sideBarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('BlogBundle:Blog');

        $tagWeights = $repo->getTagWeights($repo->getTags());

        $limit = $this->container->getParameter('blog.comments.latest_comment_limit');
        $latestComments = $em->getRepository('BlogBundle:Comment')->getLatestComments($limit);


        return $this->render('BlogBundle:Page:sidebar.html.twig', array(
            'tags' => $tagWeights,
            'latestComments' => $latestComments
        ));
    }
}