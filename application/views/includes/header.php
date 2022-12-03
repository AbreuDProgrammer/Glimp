<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <? 
        $this->my_iterator->rewind();
        $this->my_iterator->setArray($css);
        while($this->my_iterator->valid()):
    ?>
    <link rel="stylesheet" href="<?= $this->my_iterator->current(); ?>">
    <? 
        $this->my_iterator->next();
        endwhile;
    ?>
    <?
        $this->my_iterator->rewind();
        $this->my_iterator->setArray($js);
        while($this->my_iterator->valid()):
    ?>
    <script src="<?= $this->my_iterator->current(); ?>"></script>
    <?
        $this->my_iterator->next();
        endwhile;
    ?>
    <title><? if(isset($title)) echo $title; ?></title>
</head>
<body>