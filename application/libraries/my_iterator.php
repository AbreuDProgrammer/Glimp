<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sempre chamar o rewind() e o valid() antes de iterar os objetos
 * valid() == false o loop termita
 * valid() == true o loop continua com key() e current()
 * next() sempre que acabar a rotina
 */

class My_Iterator implements Iterator 
{
    private $position = 0;
    private $array = NULL;

    public function current(): Mixed
    {
        return $this->array[$this->position];
    }
    public function key(): Mixed
    {
        return $this->position;
    }
    public function next(): Void
    {
        $this->position++;
    }    
    public function rewind(): Void
    {
        $this->position = 0;
    }
    public function valid(): Bool
    {
        return isset($this->array[$this->position]);
    }
    public function setArray($array): Void
    {
        $this->array = $array;
    }
}