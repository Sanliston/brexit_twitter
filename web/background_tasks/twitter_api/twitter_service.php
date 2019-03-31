<?php
include(__DIR__ . "/../../api/models/tweets_model.php");
include(__DIR__ . "/../analysis/sentiment_analysis.php");
include(__DIR__ . "/../analysis/overall_analysis.php");
include(__DIR__ . "/../../Logs/Log.php");
require_once('twitter_api.php');

//date_default_timezone_set('Europe/London');

try{
    $twitter_api = new TwitterAPI("brexit", 50, "en");
    $tweets_array = $twitter_api->makeCall();
}catch(Exception $e){
    LogError($e->message);
}


$overall_sentiment = "neutral";
$total_tweets = 0;
$positive_tweets = 0;
$negative_tweets = 0;
$neutral_tweets = 0;

$tweets_array = array_reverse($tweets_array);

foreach( $tweets_array as $tweetElement){
    //echo "\n".$tweetElement["full_text"];


    $tweet = new Tweet($tweetElement);
    $sentiment = $tweet->getSentiment();

    if($sentiment == "positive"){
        $positive_tweets++;
    }else if($sentiment == "negative"){
        $negative_tweets++;
    }else {
        $neutral_tweets++;
    }

    $total_tweets++;

    $tweet->save();
}

$overall_analysis = new OverallAnalysis($total_tweets, $positive_tweets, $negative_tweets, $neutral_tweets);
$overall_analysis->save();
LogBackground("Tweets successfully fetched.");

class Tweet {

    private $full_text = "";

    private $sentiment = "neutral";

    private $tweet_id = null;

    private $created_at=null;

    private $username = "";

    private $model = null;


    function __construct($tweet){

        $this->full_text = $tweet["full_text"];
        $this->tweet_id= $tweet["id_str"];
        $this->username = $tweet['user']['name'];
        $this->created_at = $tweet['created_at'];

        $this->analyseTweet($this->full_text);
        //echo "\n Focused text: ".$this->focused_text;
    }

    private function analyseTweet($full_text){
        $sentiment_analysis = new SentimentAnalysis($full_text);
        $sentiment = $sentiment_analysis->getSentiment();
        $this->sentiment = $sentiment;
    }

    function getSentiment(){
        return $this->sentiment;
    }

    function save(){
        $model = new TweetsModel();
        $model->createTweet($this->tweet_id, $this->username, $this->full_text, $this->created_at, $this->sentiment);
    }

}


?>