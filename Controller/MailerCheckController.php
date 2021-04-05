<?php

namespace App\Controller;
use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\NamedAddress;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerCheckController extends AbstractController
{
    /**
     * @Route("/email", name="email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $user_email = $this->getUser();
        $email = (new Email())
            ->from(new NamedAddress('mailtrap@example.com', 'Mailtrap'))
            ->to($user_email->getUsername())
            //->cc('mailtrapqa@example.com')
            //->addCc('staging@example.com')
            //>bcc('mailtrapdev@example.com')
            //->replyTo('mailtrap@example.com')
            ->subject('Best practices of building HTML emails')
            //->embed(fopen('/path/to/newlogo.png', 'r'), 'logo')
            //->embedFromPath('/path/to/newcover.png', 'new-cover-image')
            ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
            ->html('<html>
            <body>
		            <p><br>Hey</br>
		                Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
		            <p><a href="https://blog.mailtrap.io/build-html-email/">Mailtrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
		            <img src="cid:logo"> ... <img src="cid:new-cover-image">
			</body>
			</html>')
            ->attachFromPath('images/logo.jpg');

                $mailer->send($email);
        return $this->redirectToRoute('profile');
    }

}