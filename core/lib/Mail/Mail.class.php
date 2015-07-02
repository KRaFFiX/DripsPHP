<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 16:22.
 */
namespace DripsPHP\Mail;

use Exception;

/**
 * Class Mail.
 *
 * used for sending mails
 * only DripsPHP default configuration
 */
class Mail
{
    protected static $mailer;

    /**
     * start connecting to mailserver via
     * phpmailer object.
     *
     * @param Phpmailer $phpmailer
     */
    public static function connect(Phpmailer $phpmailer)
    {
        self::$mailer = $phpmailer;
    }

    /**
     * creates a new email.
     *
     * @throws MailMailerNotFound
     */
    public function __construct()
    {
        if (!isset(self::$mailer)) {
            throw new MailMailerNotFound();
        }
    }

    /**
     * set sender of the email.
     *
     * @param $email
     * @param null $name
     */
    public function setFrom($email, $name = null)
    {
        self::$mailer->From = $email;
        if ($name !== null) {
            self::$mailer->FromName = $email;
        }
    }

    /**
     * set other options for phpmailer.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        self::$mailer->$name = $value;
    }

    /**
     * bridge methods to phpmailer-object.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)) {
            return call_user_func_array(array(self::$mailer, $name), $arguments);
        }
    }
}

class MailMailerNotFound extends Exception
{
}
