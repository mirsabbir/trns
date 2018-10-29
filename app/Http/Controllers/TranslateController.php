<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Translate\TranslateClient;

class TranslateController extends Controller
{
    public function translate(Request $request){
        $client = new \GuzzleHttp\Client();
        if($this->word($request->q)){
            $res_nouns = $client->get('https://api.datamuse.com/words?rel_jja='.$request->q);
            $res_adjs = $client->get('https://api.datamuse.com/words?rel_jjb='.$request->q);
            $res_syns = $client->get('https://api.datamuse.com/words?rel_syn='.$request->q);
            $res_ants = $client->get('https://api.datamuse.com/words?rel_ant='.$request->q);
            $res_hypernyms = $client->get('https://api.datamuse.com/words?rel_spc='.$request->q);
            $res_hyponyms = $client->get('https://api.datamuse.com/words?rel_gen='.$request->q);
            $res_holonyms = $client->get('https://api.datamuse.com/words?rel_com='.$request->q);
            $res_meronyms = $client->get('https://api.datamuse.com/words?rel_par='.$request->q);
        

            $res_nounsA = json_decode((string) $res_nouns->getBody());
            $res_adjsA = json_decode((string) $res_adjs->getBody());
            $res_synsA = json_decode((string) $res_syns->getBody());
            $res_antsA = json_decode((string) $res_ants->getBody());
            $res_hypernymsA = json_decode((string) $res_hypernyms->getBody());
            $res_hyponymsA = json_decode((string) $res_hyponyms->getBody());
            $res_holonymsA = json_decode((string) $res_holonyms->getBody());
            $res_meronymsA = json_decode((string) $res_meronyms->getBody());

            return [
                'nouns' => $res_nounsA,
                'adjs' => $res_adjsA,
                'syns' => $res_synsA ,
                'ants' => $res_antsA,
                'hypernyms' => $res_hypernymsA,
                'hyponyms' => $res_hyponymsA,
                'holonyms'=> $res_holonymsA,
                'holonyms' => $res_holonymsA ,
                'meronyms' => $res_meronymsA ,
            ];

        }
        $res = $client->get('https://translation.googleapis.com/language/translate/v2/?q='.$request->q.'&target='.$request->target.'&key=AIzaSyAG_XAAKIJ6negxAQfRx_fryAFjRflqih8');
        $data = json_decode((string)$res->getBody())->data;
        $translations = $data->translations;
        $first = $translations[0];
        $lang = $first->detectedSourceLanguage;
        $translatedText = $first->translatedText;
        return  [ 'translation' => $translatedText , 'lang' => $lang ];
    }
    private function word($text){
        $len = strlen($text);
        $spaces = 0;
        trim($text);
        for($i=0;$i<$len;$i++) if($text[$i]==' ') return 0;
        return 1;
    }
}
