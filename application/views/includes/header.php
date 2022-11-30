<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <? if(isset($css)) foreach($css as $path): ?>
        <link rel="stylesheet" href="<?= $path; ?>">
    <? endforeach ?>
    <? if(isset($js)) foreach($js as $path): ?>
        <script src="<?= $path; ?>"></script>
    <? endforeach ?>
    <title><? if(isset($title)) echo $title; ?></title>
</head>
<body>