<?php

namespace AngularJSModule\Controller;

use App;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of __CLASSNAME__Controller
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class DefaultController 
{

	/**
     * Index action
     * 
     * @param App $app
     * @return string
     */
    function index(App $app)
    {
        return $app['twig']->render('default/index.twig', array());
    }
    
    /**
     * Basic auth action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function basicAuth(App $app)
    {
        $valid_passwords = array (
            "admin@example.com" => "admin",
            "user@example.com"  => "user"
        );
        $valid_users = array_keys($valid_passwords);

        $email = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

        $validated = (in_array($email, $valid_users)) && ($pass == $valid_passwords[$email]);

        if (!$validated) {
            $response = new JsonResponse(array('error' => 'Not Authorized'), 401);
        } else {
            $response = new JsonResponse(array(
                'response' => 'Authorized',
                'user'     => array(
                    'email' => $email,
                    'pass'  => $pass
                )
            ));
        }
        
        return $response;
    }

    /**
     * Form auth action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function formAuth(App $app)
    {
        $valid_passwords = array (
            "admin@example.com" => "admin",
            "user@example.com"  => "user"
        );
        $valid_users = array_keys($valid_passwords);

        $email = $app['request']->request->get('email');
        $pass = $app['request']->request->get('password');

        $validated = (in_array($email, $valid_users)) && ($pass == $valid_passwords[$email]);

        if (!$validated) {
            $response = new JsonResponse(array('error' => 'Not Authorized'), 401);
        } else {
            $response = new JsonResponse(array(
                'response' => 'Authorized',
                'user'     => array(
                    'email' => $email,
                    'pass'  => $pass
                )
            ));
        }
        
        return $response;
    }

    /**
     * Sign Up action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function signUp(App $app)
    {
        $firstName   = $app['request']->request->get('firstName');
        $lastName    = $app['request']->request->get('lastName');
        $displayName = $app['request']->request->get('displayName');
        $email       = $app['request']->request->get('email');
        $password    = $app['request']->request->get('password');
        $agree       = $app['request']->request->get('agree');
        
        return new JsonResponse(array(
            'inserted' => true,
            'user'     => array(
                'firstName'   => $firstName,
                'lastName'    => $lastName,
                'displayName' => $displayName,
                'email'       => $email,
                'password'    => $password, 
                'agree'       => $agree
            )
        ));
    }

    /**
     * Contact action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function contact(App $app)
    {
        $website = 'Silex Generator Dashboard';
        $to      = 'ramon.calle.88@gmail.com';

        // Request Data
        $name    = $app['request']->request->get('name');
        $subject = $app['request']->request->get('subject');
        $email   = $app['request']->request->get('email');
        $message = $app['request']->request->get('message');

        $messageHtml  = '<div style="margin:auto;position: relative;background: #FFF;border-top: 2px solid #00C0EF;margin-bottom: 20px;border-radius: 3px;width: 90%;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);padding: 20px 30px">';
        $messageHtml .= "<p>You received a message from $name $email por medio de $website.</p>";
        $messageHtml .= "<p>The message is:";
        $messageHtml .= "<div style=\"background-color: #F0F7FD;margin: 0px 0px 20px;padding: 15px 30px 15px 15px;border-left: 5px solid #D0E3F0;\">$message</div>";
        $messageHtml .= '</div>';

        $swiftMessage = \Swift_Message::newInstance("$subject - $website")
                ->setFrom(array($email => $name))
                ->setTo($to)
                ->setBody($messageHtml, 'text/html');
                
        try 
        {
            // Custom GMail transport
            $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                ->setUsername('****')
                ->setPassword('****');

            $mailer = \Swift_Mailer::newInstance($transport);

            $result = $mailer->send($swiftMessage);

            // Response
            $response = new JsonResponse(array('result' => $result));
        } catch (\Swift_TransportException $ste) {
            $mailer->getTransport()->stop();
            
            // Response
            $response = new JsonResponse(array(
                'error' => $ste->getMessage()
            ), 500);
        } catch (\Exception $ex) {
            // Response
            $response = new JsonResponse(array(
                'error' => $ex->getMessage()
            ), 500);
        }

        return $response;
    }
}