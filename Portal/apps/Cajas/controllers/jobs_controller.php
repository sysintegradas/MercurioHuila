<?php

class JobsController extends ApplicationController {

    public function beforeFilter(){
        return true;
    }

    public function migra079_aportes079Action(){
        $modelos = array("migra079");
        $Transaccion = parent::startTrans($modelos);
        $this->Migra079->setTransaction($Transaccion);
        $this->Migra079->deleteAll();
        $datos = $this->webService("Aportes079", array());
        foreach($datos as $mdatos){
            $migra079 = new Migra079();
            foreach($mdatos as $key => $value){
                if(property_exists($migra079, $key)){
                    if(!empty($value)){
                        $migra079->writeAttribute($key, $value);
                    }
                }
            }
            if(!$migra079->save()){
                Debug::addVariable("a", print_r($migra079->getMessages(), true));
                throw new DebugException(0); 
                parent::ErrorTrans();
            }
        }
        $this->Migra079->updateAll("division='', grupo=''","conditions: division IS NULL");
        $this->Migra079->updateAll("grupo=''","conditions: grupo IS NULL");
        parent::finishTrans();
    }

    public function migra087_aportes087Action(){
        $modelos = array("migra087");
        $Transaccion = parent::startTrans($modelos);
        $this->Migra087->deleteAll();
        $datos = $this->webService("Aportes087", array());
        foreach($datos as $mdatos){
            $migra087 = new Migra087();
            foreach($mdatos as $key => $value){
                switch($key){
                    case 'idbarrio':
                        $key = "codbar";
                        break;
                    case 'codigozona':
                        $key = "codzon";
                        break;
                    case 'barrio':
                        $key = "detalle";
                        break;
                }
                if(property_exists($migra087, $key)){
                    $migra087->writeAttribute($key, $value);
                }
            }
            if(!$migra087->save()){
                Debug::addVariable("a", print_r($migra087->getMessages(), true));
                throw new DebugException(0);
                parent::ErrorTrans();
            }
        }
        parent::finishTrans();
    }

    public function migra089_aportes089Action(){
        $modelos = array("migra089");
        $Transaccion = parent::startTrans($modelos);
        $this->Migra089->deleteAll();
        $datos = $this->webService("Aportes089", array());
        foreach($datos as $mdatos){
            $migra089 = new Migra089();
            foreach($mdatos as $key => $value){
                switch($key){
                    case 'coddepartamento':
                        $key = "coddep";
                        break;
                    case 'codmunicipio':
                        $key = "codciu";
                        break;
                    case 'codzona':
                        $key = "codzon";
                        break;
                    case 'departmento':
                        $key = "detdep";
                        break;
                    case 'municipio':
                        $key = "detciu";
                        break;
                    case 'zona':
                        $key = "detzon";
                        break;
                    case 'idpais':
                        $key = "pais";
                        break;
                }
                if(property_exists($migra089, $key)){
                    $migra089->writeAttribute($key, $value);
                }
            }
            if(!$migra089->save()){
                Debug::addVariable("a", print_r($migra089->getMessages(), true));
                throw new DebugException(0);
                parent::ErrorTrans();
            }
        }
        parent::finishTrans();
    }

    public function migra090_aportes090Action(){
        $modelos = array("migra090");
        $Transaccion = parent::startTrans($modelos);
        $this->Migra090->deleteAll();
        $datos = $this->webService("Aportes090", array());
        foreach($datos as $mdatos){
            $migra090 = new Migra090();
            foreach($mdatos as $key => $value){
                if(property_exists($migra090, $key)){
                    $migra090->writeAttribute($key, $value);
                }
            }
            if(!$migra090->save()){
                Debug::addVariable("a", print_r($migra090->getMessages(), true));
                throw new DebugException(0);
                parent::ErrorTrans();
            }
        }
        parent::finishTrans();
    }

    public function migra091_aportes091Action(){
        $modelos = array("migra091");
        $Transaccion = parent::startTrans($modelos);
        $this->Migra091->deleteAll();
        $datos = $this->webService("Aportes091", array());
        foreach($datos as $mdatos){
            $migra091 = new Migra091();
            foreach($mdatos as $key => $value){
                if(property_exists($migra091, $key)){
                    $migra091->writeAttribute($key, $value);
                }
            }
            if(!$migra091->save()){
                Debug::addVariable("a", print_r($migra091->getMessages(), true));
                throw new DebugException(0);
                parent::ErrorTrans();
            }
        }
        parent::finishTrans();
    }

}
?>
