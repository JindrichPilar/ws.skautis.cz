<?php

namespace App;

use Nette\Application\UI\Form;

/**
 * @author sinacek
 */
class AppRequestPresenter extends BasePresenter
{

    /** @var \MailService */
    protected $mailService;

    /** @var  \WsService */
    protected $wsService;

    public function __construct(\MailService $ms, \WsService $ws)
    {
        parent::__construct();
        $this->mailService = $ms;
        $this->wsService = $ws;
    }

    public function createComponentAddForm($name)
    {
        $form = new Form($this, $name);
        $form->addText("name", "Název aplikace")
            ->addRule(Form::FILLED, "Zadej název aplikace");
        $form->addText("desc", "Popis aplikace")
            ->addRule(Form::FILLED, "Zadej popis aplikace");
        $form->addText("username", "Jméno a příjmení")
            ->addRule(Form::FILLED, "Zadejte jméno a příjmení");
        $form->addText("nick", "Přezdívka");
        $form->addText("email", "Kontaktní email")
            ->addRule(Form::FILLED, "Zadejte email")
            ->addRule(Form::EMAIL, "Zadejte platný email");
        $form->addText("orgNum", "Reg. číslo jednotky");
        $form->addText("urlBase", "URL aplikace")
            ->setDefaultValue("https://")
            ->addRule(Form::URL, "Zadej platnou URL aplikace");
        $form->addText("urlLogin", "URL po přihlášení")
            ->setDefaultValue("https://")
            ->addRule(Form::URL, "Zadej platnou  URL po přihlášení");
        $form->addText("urlLogout", "URL po odhlášení")
            ->setDefaultValue("https://")
            ->addRule(Form::URL, "Zadej platnou URL po odhlášení");
        $form->addTextArea("note", "Poznámka", 40, 5)
            ->getControlPrototype()->setClass("input-xlarge");
        $form->addSubmit('send', 'Odeslat')
            ->getControlPrototype()->setClass("btn btn-primary");
        $form->onSuccess[] = [$this, $name . 'Submitted'];
        return $form;
    }

    public function addFormSubmitted(Form $form)
    {
        $values = $form->values;
        $template = $this->template;
        $template->values = $values;
        $this->mailService->sendRequest($template, $values);
        $this->presenter->flashMessage("Žádost byla odeslána na ústředí a na zadaný kontaktní email.");
        $this->presenter->redirect("default");
    }
}
