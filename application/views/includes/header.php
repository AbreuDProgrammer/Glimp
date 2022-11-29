<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<? if(isset($cssMain)) echo $cssMain; ?>">
    <link rel="stylesheet" href="<? if(isset($cssPage)) echo $cssPage; ?>">
    <link rel="stylesheet" href="<? if(isset($cssMenu)) echo $cssMenu; ?>">
    <title><?= $title ?></title>
</head>
<body>