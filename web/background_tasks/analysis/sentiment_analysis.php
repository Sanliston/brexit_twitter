<?php

    class SentimentAnalysis {


        private $total_word_count = null;
        private $positive_word_count = null;
        private $negative_word_count = null;
        private $very_bad_word_count = null;
        private $threshold = 0.05;
        private $sentiment = "neutral";

        function __construct($text){
            $this->total_word_count = str_word_count($text, 0, '0..9');
            $this->positive_word_count = $this->getWordCount($text, __DIR__."/positive_words.txt", 1);
            $this->negative_word_count = $this->getWordCount($text, __DIR__."/negative_words.txt", 1);
            $this->very_bad_word_count = $this->getWordCount($text, __DIR__."/very_bad_words.txt", 1.5);
            $this->evaluateSentiment();
        }

        private function getWordCount($text, $path, $weight){
            $words_array = $this->getFileContents($path);
            $word_count = 0;

            if($words_array){
                $word_count = $this->getMatchCount($words_array, $text, $weight);
            }

            return $word_count;

        }

        private function getFileContents($path){

            $word_array = [];

            /*if ($file = fopen($path, 'r')) {
                while (!feof($file)) {
                    $line = fgets($file);

                    array_push($word_array, $line);
                }

                fclose($file);
            }*/

            $word_array = file($path, FILE_IGNORE_NEW_LINES);

            return $word_array;
        }

        private function getMatchCount($word_array, $text, $weight){

            $match_count = 0;

            foreach($word_array as $word){

                $match = stripos($text, $word);

                if($match === FALSE){
                    //echo "No match, word: ".$word;
                    continue;
                }
 
                $match_count = $match_count+1;
                
            }

            $match_count = $match_count * $weight;

            return $match_count;
        }

        public function evaluateSentiment(){
            $total_word_count = $this->total_word_count;
            $positive_word_count = $this->positive_word_count;
            $negative_word_count = $this->negative_word_count;
            $very_bad_word_count = $this->very_bad_word_count;

            $weighted_negative_count = $negative_word_count + $very_bad_word_count;

            $positive_ratio = $positive_word_count/$total_word_count;
            $negative_ratio = $weighted_negative_count/$total_word_count;

            if($positive_ratio > $this->threshold || $negative_ratio > $this->threshold){

                if($positive_ratio > $negative_ratio){
                    $this->sentiment = "positive";
                }else{
                    $this->sentiment = "negative";
                }

            }else{
                $this->sentiment = "neutral";
            }
        }

        public function getSentiment(){
            return $this->sentiment;
        }



    }

?>