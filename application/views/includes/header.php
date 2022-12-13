<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <? 
        $this->iterator->rewind();
        $this->iterator->setArray($css);
        while($this->iterator->valid()):
    ?>
    <link rel="stylesheet" href="<?= $this->iterator->current(); ?>">
    <? 
        $this->iterator->next();
        endwhile;
    ?>
    <?
        $this->iterator->rewind();
        $this->iterator->setArray($js);
        while($this->iterator->valid()):
    ?>
    <script src="<?= $this->iterator->current(); ?>"></script>
    <?
        $this->iterator->next();
        endwhile;
    ?>
    <title><? if(isset($title)) echo $title; ?></title>
</head>
<body>
    <div id="page">