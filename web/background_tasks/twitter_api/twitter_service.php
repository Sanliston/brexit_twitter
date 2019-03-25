<?php
include(__DIR__ . "/../../api/models/tweets_model.php");
require_once('twitter_api.php');


$twitter_api = new TwitterAPI("brexit", 100, "en");
$tweets_array = $twitter_api->makeCall();

foreach( $tweets_array as $tweetElement){
    //echo "\n".$tweetElement["full_text"];

    $tweet = new Tweet($tweetElement);
    $tweet->save();
}

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

    }

    function save(){
        $model = new TweetsModel();
        $model->createTweet($this->tweet_id, $this->username, $this->full_text, $this->created_at, $this->sentiment);
    }

}


?>