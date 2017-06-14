<?php

abstract class Message {

    public static function success($msg){
        print "<script>showMsg('$msg','success');</script>";
    }

    public static function warning($msg){
        print "<script>showMsg('$msg','warning');</script>";
    }

     public static function error($msg){
        print "<script>showMsg('$msg','error');</script>";
    }

     public static function info($msg){
        print "<script>showMsg('$msg','info');</script>";
    }

}
