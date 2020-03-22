<?php

namespace noone;

interface ContainerInterface{

    public function get(string $id);

    public function has(string $id);

}