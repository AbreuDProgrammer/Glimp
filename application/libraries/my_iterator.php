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

    public function current(): mixed
    {
        return $this->array[$this->position];
    }
    public function key(): mixed
    {
        return $this->position;
    }
    public function next(): void
    {
        $this->position++;
    }    
    public function rewind(): void
    {
        $this->position = 0;
    }
    public function valid(): bool
    {
        return isset($this->array[$this->position]);
    }
    public function setArray($array): void
    {
        $this->array = $array;
    }
}