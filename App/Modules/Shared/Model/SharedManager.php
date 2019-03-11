<?php

namespace ES\App\Modules\Shared\Model;

use ES\Core\Model\AbstractManager;
use ES\Core\Mail\Mail;

/**
 * SharedManager short summary.
 *
 * SharedManager description.
 *
 * @version 1.0
 * @author ragus
 */
class SharedManager extends AbstractManager
{
    public function sendMailContact($name, $mail, $subject,$message):bool
    {
        try {
            $content='Bonjour,<br/><br/>' .
                $name .' ( ' . $mail . ' ) est entré en contact à ce sujet : ' . $subject .
                '<br/> Message :<br/> ' . $message;

            $mail=new Mail();
            if(! $mail->send(SMTP_USER_MAIL,'Prise de contact de ' . $name ,$content)) {
                throw new \InvalidArgumentException('Erreur lors de l\'envoi du mail.');
            }
            return true;
        }
        catch(\InvalidArgumentException $e)
        {
            $this->errorCatchView($e->getMessage());
            return false;
        }
    }
}