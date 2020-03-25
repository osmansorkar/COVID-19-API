<?php

namespace OsmanSorkar\Corona;

use Goutte\Client;

class Generate{

    public function generateData(){

        if(@file_get_contents("time.text")>time()-600){
            //print_r("can' run");
            return;
        }

        file_put_contents("time.text",time());

        //$client = new Generate();

        $client = new Client();
        $crawler = $client->request('GET', 'https://www.worldometers.info/coronavirus/');


        global  $bref;
        $bref=[];


        $crawler->filter('#maincounter-wrap')->each(function ($node,$i) {

            $str=$node->text();
            $str=preg_replace('~\D~', '', $str);


            global $bref;
            $bref["update"]=time();
            switch ($i){
                case 0:
                    $bref["confirmed"]=$str;
                    break;
                case 1:
                    $bref["deaths"]=$str;
                    break;
                case 2:
                    $bref["recovered"]=$str;
                    break;
            }
        });

        file_put_contents("bref.json",json_encode($bref));

        global  $country;

        $country = [];

        $crawler->filter('#main_table_countries_today tr')->each(function ($node) {

            global $data;
            $data=[];
            $subCrawler = $node->filter('td')->each(function ($node,$i) {

                global $data;

                $data["update"] = time();


                switch ($i){
                    case 0:
                        $data["country"]=$node->text();
                        break;
                    case 1:
                        $data["total"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 2:
                        $data["new"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 3:
                        $data["deaths"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 4:
                        $data["new_death"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 5:
                        $data["recovered"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 6:
                        $data["active_case"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 7:
                        $data["serious"]=preg_replace('~\D~',"",$node->text());
                        break;
                    case 8:
                        $data["toto_case_1m"]=preg_replace('~\D~',"",$node->text());
                        break;
                }


            });

            global $country;
            if(!empty($data)){

                $country[strtolower(rtrim($data["country"]))]=$data;

            }
        });

        file_put_contents("latest.json",json_encode($country));
    }


}
