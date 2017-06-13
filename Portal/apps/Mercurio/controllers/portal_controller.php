<?php

class PortalController extends ApplicationController {

    private $contadorimg = 1;
    private $imagenes = array();

    public function initialize(){
        //$this->setTemplateAfter('escritorio');
        //$this->setPersistance(true);

        $this->setPersistance(true);
        $this->imagenes = array();
        $this->imagenes[0] = "head_sys.jpg";
        $mercurio26 = $this->Mercurio26->findAllBySql("SELECT * from mercurio26 WHERE tipo = 1");
        $count=1;
        foreach($mercurio26 as $mmercurio26){
          $this->imagenes[$count++] = $mmercurio26->getNomimg();
        }
    }

    public function indexAction(){
        $html = "";
        $mercurio02 = $this->Mercurio02->findAllBySql("SELECT codcaj,sigla,nomarc FROM mercurio02 WHERE mercurio02.estado='A'");
        foreach($mercurio02 as $mmercurio02){
          $html .="<div class='principal_td' title='{$mmercurio02->getSigla()}' codcaj='{$mmercurio02->getCodcaj()}' onclick='irlogin(this);' >".Tag::image("portal/{$mmercurio02->getNomarc()}","width: 100px","height: 100px")."</div>";
        }
        $this->setParamToView("html", $html);
        $banner = "";
        for($i =1; $i < count($this->imagenes); $i++){
          $banner.= '<div class="puntoImg" onClick="slidePort(this)" numeroBanner="'.$i.'"></div>';
        }
        $this->setParamToView("banner", $banner);
    }
    
    public function asignarCodcajAction(){
      $this->setResponse("ajax");
      $codcaj = $this->getPostParam("codcaj");
      Session::setDATA("codcaj",$codcaj);
      $this->renderText(json_encode(true));
    }



    public function enviarCorreoAction(){
        $this->setResponse("ajax");
        $nombre = $this->getPostParam("nombre");
        $email_user = $this->getPostParam("email");
        $asunto = $this->getPostParam("asunto");
        $file = $this->getPostParam("archivo");
        $mensaje = "<br><strong>Nombre:</strong> $nombre";        
        $mensaje .= "<br><strong>Email:</strong> $email_user <br><br>";        
        $mensaje .= $this->getPostParam("mensaje");

        Core::importFromLibrary("Swift", "Swift.php");
        Core::importFromLibrary("Swift", "Swift/Connection/SMTP.php");
        
        $smtp = new Swift_Connection_SMTP("mail.syseu.com", Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
        $smtp->setUsername("mercurio@syseu.com");
        $smtp->setPassword("controlmercurio");
        $message = new Swift_Message($asunto);
        if($file!=""){
            $swiftfile = new Swift_File($file);
            $attachment = new Swift_Message_Attachment($swiftfile);
            $message->attach($attachment);
        }

        $bodyMessage = new Swift_Message_Part(utf8_decode($mensaje), "text/html");
        $bodyMessage->setCharset("UTF-8");
        $message->attach($bodyMessage);
        $swift = new Swift($smtp);
        $email = new Swift_RecipientList();
        $email->addTo("mercurio@syseu.com", "Formulario Contáctenos");
        $swift->send($message, $email, new Swift_Address($email_user));
        $this->renderText(json_encode(true));
    }

    public function slideportAction(){
      $this->setResponse("ajax");
      $numBanner = $this->getPostParam('numBanner');
      if ($numBanner != "")
        $this->contadorimg = $numBanner;

      if (!isset($this->imagenes[$this->contadorimg])){
        $this->contadorimg = 0;
      }
      $responseimg = Tag::image("portal/{$this->imagenes[$this->contadorimg]}");
      $response = array($this->contadorimg,$responseimg);
      $this->contadorimg++;
      $this->renderText(json_encode($response));
    }

}
?>
