<?php

function getWorld(){
    $my_var = new Avengers();
    return $my_var->getStar();
}

class Avengers{
    public function getStar(){
        return "Spyder mahn";
    }
}