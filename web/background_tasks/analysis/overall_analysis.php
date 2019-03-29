<?php

    //This is the script responsible for handling the overall statistics
    /*What it does:
        Gest the total positve, total negative, and total neutral tweets, the total number of tweets in this batch. Calculates the average sentiment.
        Gets the date and time
        Then saves the overall sentiment */

    include(__DIR__ . "/../../api/models/analysis_model.php");

    class OverallAnalysis{

        private $total_tweets = 0;
        private $positive_tweets = 0;
        private $negative_tweets = 0;
        private $neutral_tweets = 0;
        private $average_sentiment = "neutral";
        private $date_created = null;


        function __construct($total_tweets, $positive_tweets, $negative_tweets, $neutral_tweets){
            $this->total_tweets = $total_tweets;
            $this->positive_tweets = $positive_tweets;
            $this->negative_tweets = $negative_tweets;
            $this->neutral_tweets = $neutral_tweets;

            $this->average_sentiment = $this->getAverageSentiment($positive_tweets, $negative_tweets, $neutral_tweets);
            $this->date_created = date("Y-m-d H:i:s");

            echo "\nOA total tweets: ".$this->total_tweets;
            echo "\nOA positive tweets: ".$this->positive_tweets;
            echo "\nOA negative tweets: ".$this->negative_tweets;
            echo "\nOA neutral tweets: ".$this->neutral_tweets;
            echo "\nOA average_sentiment: ".$this->average_sentiment;
            echo "\nOA date_created: ".$this->date_created;
        }

        private function getAverageSentiment($positive_tweets, $negative_tweets, $neutral_tweets){
            //The biggest out of the three
            $largest_value = max($positive_tweets, $negative_tweets, $neutral_tweets);
            echo "\nobtained max-value: ".$largest_value;
            $average_sentiment = null;

            if($largest_value == $positive_tweets){
                $average_sentiment = "positive";
            }else if($largest_value == $negative_tweets){
                $average_sentiment = "negative";
            }else{
                $average_sentiment = "neutral";
            }

            return $average_sentiment;
        }   

        public function save(){
            $model = new AnalysisModel();
            $model->createEntry($this->date_created, $this->total_tweets, $this->positive_tweets, $this->negative_tweets, $this->neutral_tweets, $this->average_sentiment);
        }


    }

?>