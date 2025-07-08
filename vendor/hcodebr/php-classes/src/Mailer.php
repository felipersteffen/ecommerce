<?php

namespace Hcode;

use Rain\Tpl;

class Mailer{
    const USERNAME = 'frfelipe22@gmail.com';
	const PASSWORD = 'qwtr pyij sflh cfqq';
	const NAME_FROM = "Curso PHP";

	private $mail;

	public function __construct($toAddress, $toName, $subject, $tplName, $data = array()){

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false
	    );

		Tpl::configure( $config );

		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);

		$this->mail = new \PHPMailer;
		$this->mail->CharSet = 'UTF-8';
        $this->mail->isSMTP();
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->mail->SMTPDebug = 2;
		$this->mail->Debugoutput = 'html';
		$this->mail->Host = 'smtp.gmail.com';

		// use
		// $this->mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6

		$this->mail->Port = 587;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->SMTPAuth = true;
		$this->mail->Username = Mailer::USERNAME;
		$this->mail->Password = Mailer::PASSWORD;
		$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);
		//Set an alternative reply-to address
		//$this->mail->addReplyTo('replyto@example.com', 'First Last');

		$this->mail->addAddress($toAddress, $toName);
		$this->mail->Subject = $subject;
		$this->mail->msgHTML($html);
		$this->mail->AltBody = 'This is a plain-text message body';

		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

	}

	public function send(){
		return $this->mail->send();
	}

}