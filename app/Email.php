<?php

namespace App;

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;


/**
 * Class Email
 * @package App
 */
class Email
{

    /**
     * @var Url|null
     */
    public $objUrl;

    /**
     * @var Swift_Message
     */
    private $_objMessage;

    /**
     * @var Swift_SmtpTransport
     */
    private $_objTransport;

    /**
     * @var bool
     */
    /*private $_useSmtp = SMTP_USE;*/

    /**
     * @var string
     */
    private $_smtpHost = SMTP_HOST;

    /**
     * @var string
     */
    private $_smtpUsername = SMTP_USERNAME;

    /**
     * @var string
     */
    private $_smtpPassword = SMTP_PASSWORD;

    /**
     * @var string
     */
    private $_smtpPort = SMTP_PORT;

    /**
     * @var string
     */
    private $_smtpSsl = SMTP_SSL;

    /**
     *
     */
    const EMAIL_ADMIN = 'codeistalk@gmail.com';

    /**
     *
     */
    const NAME_ADMIN = 'Market Admin';

    /**
     * Email constructor.
     * @param null $objUrl
     */
    public function __construct($objUrl = null)
    {

        $this->objUrl = \is_object($objUrl) ? $objUrl : new Url();

        $this->_objMessage = new Swift_Message();

        $transport = (new Swift_SmtpTransport($this->_smtpHost, $this->_smtpPort, $this->_smtpSsl))
            ->setUsername($this->_smtpUsername)
            ->setPassword($this->_smtpPassword);

        $this->_objTransport = new Swift_Mailer($transport);

    }

    /**
     * @param null $case
     * @param null $array
     * @return bool
     * @throws \Exception
     */
    public function process($case = null, $array = null)
    {

        if (!empty($case)) {

            switch ($case) {
                case 1:
                    // add url to the array
                    $link = "<a href=\"";
                    $link .= $_SERVER['HTTP_ORIGIN'] . $this->objUrl->href('activate', array('code', $array['hash']));
                    $link .= "\">";
                    $link .= $_SERVER['HTTP_ORIGIN'] . $this->objUrl->href('activate', array('code', $array['hash']));
                    $link .= "</a>";
                    $array['link'] = $link;
                    $this->_objMessage->setTo([$array['email'] => $array['first_name'] . ' ' . $array['last_name']]);

                    $this->_objMessage->setFrom(self::EMAIL_ADMIN, self::NAME_ADMIN);
                    $this->_objMessage->setSubject('Activate your account');
                    $this->_objMessage->setBody($this->fetchEmail($case, $array));
                    $this->_objMessage->setContentType('text/html');

                    break;
            }

            // send email
            try {
                $this->_objTransport->send($this->_objMessage);
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }
            return true;
        }

    }

    /**
     * @param null $case
     * @param null $array
     * @return string
     */
    public function fetchEmail($case = null, $array = null)
    {

        if (!empty($case)) {

            if (!empty($array)) {
                foreach ($array as $key => $value) {
                    ${$key} = $value;
                }
            }

            ob_start();
            require_once(EMAILS_PATH . DS . $case . ".php");
            $out = ob_get_clean();
            return $this->wrapEmail($out);

        }

    }

    /**
     * @param null $content
     * @return string
     */
    public function wrapEmail($content = null)
    {
        if (!empty($content)) {
            return "<div style=\"font-family:Arial,Verdana,Sans-serif;font-size:12px;color:#333;line-height:21px;\">{$content}</div>";
        }
    }

}
