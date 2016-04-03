<?php

namespace FrontendModule\Controller;

use App;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ContactController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class ContactController 
{
    
    /**
     * Contact action page
     * 
     * @param App $app
     * @return RedirectResponse|Response
     */
    function contact(App $app)
    {
        if ('POST' == $app['request']->getMethod()) {
            $nombre = $app['request']->get('nombre');
            $asunto = $app['request']->get('asunto');
            $correo = $app['request']->get('correo');
            $mensaje = $app['request']->get('mensaje');

            $message  = '<div style="margin:auto;position: relative;background: #FFF;border-top: 2px solid #00C0EF;margin-bottom: 20px;border-radius: 3px;width: 90%;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);padding: 20px 30px">';
            $message .= "<p>Ha recibido un mensaje de $nombre < $correo > por medio de Horizonte.</p>";
            $message .= "<p>El mensaje es el siguiente:";
            $message .= "<div style=\"background-color: #F0F7FD;margin: 0px 0px 20px;padding: 15px 30px 15px 15px;border-left: 5px solid #D0E3F0;\">$mensaje</div>";
            $message .= '</div>';
            
            $swiftMessage = \Swift_Message::newInstance("$asunto - Horizonte")
                    ->setFrom(array($correo => $nombre))
                    ->setTo('tania_1019@hotmail.com')
                    ->setBcc(array('ramon.calle.88@gmail.com' => 'Ramon Serrano'))
                    ->setBody($message, 'text/html');
            
            $result = false;
            
            try {
                $result = $app['mailer']->send($swiftMessage);
            } catch (\Swift_TransportException $ste) {
                $app['mailer']->getTransport()->stop();
                throw $ste;
            }

            if ($result) {
                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => '¡Su mensaje ha sido enviado exitosamente!',
                    )
                );
            } else {
                $app['session']->getFlashBag()->add(
                    'danger',
                    array(
                        'message' => '¡Su mensaje no pudo ser enviado! Intente más tarde.',
                    )
                );
            }
            return $app->redirect($app['url_generator']->generate('contact'));
        }

        return $app['twig']->render('contact/contact.html.twig', array());
    }
}
