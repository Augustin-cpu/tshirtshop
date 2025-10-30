<?php
class StoreFront
{
    public $mSiteUrl;

    // Constructeur de classe
    public function __construct()
    {
        // Construit l'URL de base du site, en utilisant un lien vide
        $this->mSiteUrl = Link::Build('');
    }
}
