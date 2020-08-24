<?php

/**
 * cette fonction sert a formater la date donnée
 *
 * @param string $oriDate
 * @return string
 */
function formatDate(string $oriDate): string
{
    // on definit la langue du site
    setlocale(LC_TIME, 'FR_fr');

    // on formate la date dans la langue choisie
    $newDate = strftime('%A %e %B %Y', strtotime(date($oriDate)))." &agrave ".strftime('%T', strtotime(date($oriDate)));

    // on encode en UTF-8 pour gérer les caractère spéciaux
    $newDate = utf8_encode($newDate);

    // on retourne la date formatée
    return $newDate;
}

/**
 * cette fonction renvoie un extrait du texte raccourci à la longueur du texte demandé
 *
 * @param string $texte
 * @param integer $longueur
 * @return string
 */
function extrait(string $texte, int $longueur): string
{
    // on décode les caractères html
    $texte = htmlspecialchars_decode($texte);

    // on supprime le html
    $texte = strip_tags($texte);

    // on raccourcit le texte
    $textereduit = mb_strimwidth($texte, 0, $longueur, "...");
    return $textereduit;
}

/**
 * cette fonction génère une miniature d'une image (png ou jpeg) dans la taille demandée (carré)
 *
 * @param string $fichier chemin complet du fichier
 * @param integer $taille taille en pixels
 * @return boolean
 */
function mini($fichier, $taille): bool
{
    $dimension = getimagesize($fichier);

    // on définit l'orientation et les déclages qui en découlent
    // on initialise les décalages
    $decalageY = $decalageX = 0;

    switch($dimension[0] <=> $dimension[1]){
        case -1:
            $taillecarre = $dimension[0];
            $decalageY = ($dimension[1] - $taillecarre)/2;;
            break;
        case 0:
            $taillecarre = $dimension[0];
            break;
        case 1:
            $taillecarre = $dimension[1];
            $decalageX = ($dimension[0] - $taillecarre)/2;
    }

    // on vérifie le type Mime de l'image
    switch($dimension['mime']){
        case 'image/png':
            $imageTemp = imagecreatefrompng($fichier);
            break;
        case 'image/jpeg':
            $imageTemp = imagecreatefromjpeg($fichier);
            break;
        default:
            return false;
    }

    // on crée une image temporaire en mémoire pour créer la copie
    $imageDest = imagecreatetruecolor($taille, $taille);

    // on copie la totalité de l'image source dans l'image de destination
    imagecopyresampled(
        $imageDest, //image destination
        $imageTemp, //image source
        0, //point gauche de la zone de collage (position de là ou on va coller l'image dans la destination)
        0, //point supérieur de la zone de collage (position de là ou on va coller l'image dans la destination)
        $decalageX, //point gauche de la zone de copie (position du carré dans son image d'origine)
        $decalageY, //point supérieur de la zone de copie (position du carré dans son image d'origine)
        $taille, // largeur de la zone de collage (taille de la zone dans laquelle on va coller l'image)
        $taille, // hauteur de la zonne de collage (taille de la zone dans laquelle on va coller l'image)
        $taillecarre, // largeur de la zonne de copie (taille du carrée dans son image d'origine)
        $taillecarre // hauteur de la zonne de copie (taille du carrée dans son image d'origine)
    );

    // on démonte le nom du fichier
    $chemin = pathinfo($fichier, PATHINFO_DIRNAME);
    $nomfichier = pathinfo($fichier, PATHINFO_FILENAME);
    $extension = pathinfo($fichier, PATHINFO_EXTENSION);
    $nouveaufichier = "$chemin/$nomfichier-{$taille}x$taille.$extension";

    // on enregistre l'image sur le disque
    switch($dimension['mime']){
        case 'image/png':
            imagepng($imageDest, $nouveaufichier);
            break;
        case 'image/jpeg':
            imagejpeg($imageDest, $nouveaufichier);
    }

    // on détruit les images en mémoire
    imagedestroy($imageDest);
    imagedestroy($imageTemp);

    return true;
}