<?php

namespace noone;

use Exception;

class Container
{

    public static array $services = [];

    public function singleton($service, $provider)
    {
        $this->bind($service,$provider,true);
    }

    public function bind($service, $provider, $singleton = false)
    {
         if($singleton && !is_object($provider)){
            throw new Exception('singleton provider must be an instance');
         }
 
         if(!$singleton && !class_exists($provider)){
            throw new Exception('provider class not exists!');
         }

         self::$services[$service] = [
             'provider'=>$provider,
             'singleton'=>$singleton
         ];
    }

    public static function getInstance(string $class)
    {
        # code...
    }

   public function bindParams()
   {
       # code...
   }


   public function reslove(string $class,$method,array $params = [])
   {
       
   }

}
