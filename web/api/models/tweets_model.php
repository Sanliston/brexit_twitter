<?php

    include_once(__DIR__."/../config/database.php");

    class TweetsModel{

        private $database;
        private $connection;
        private $table_name = "brexit_posts";
        private $fillable = [
            "tweet_id", //tweet id etc
            "username",
            "text",
            "created_at",
            "sentiment",
            "time_saved"
        ];
        
        function __construct(){
            $this->database = new Database();
            $this->connection = $this->database->getConnection();

        }

        public function checkIfExists($tweet_id){
            $statement = $this->connection->prepare("SELECT tweet_id FROM ".$this->table_name." WHERE tweet_id = ?");
            $statement->bind_param("s",$tweet_id);
            $result = $statement->execute();
            $statement->close();

            return true;
        }

        public function deleteTweet($tweet_id){
            if($this->checkIfExists($tweet_id)){
                return false;
            }else{
                $current_time = mktime();
                $statement = $this->connection->prepare("DELETE FROM ".$this->table_name." WHERE tweet_id = ?");
                $statement->bind_param("s",$tweet_id);
                $statement->execute(); 
                $statement->close();

                return true;
            }
        }

        public function updateTweetSentiment($tweet_id, $sentiment){
            $statement = $this->connection->prepare("UPDATE ".$this->table_name." SET sentiment = ? WHERE tweet_id = ?");
            $statement->bind_param("ss", $sentiment, $tweet_id);
            $statement->execute();
            $statement->close();

        }

        public function getAllTweets($limit=50){

            $statement = $this->connection->prepare("SELECT * FROM ( SELECT * FROM ".$this->table_name." ORDER BY id DESC) sub ORDER BY id DESC LIMIT ?");
            $statement->bind_param("i",$limit);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            $all_tweets = array();
            while($row = $result->fetch_assoc()){

                $result_array = $row;
                //print_r($result_array);
                array_push($all_tweets, $result_array);
            }

            //print_r($all_tweets);

            $count = count($all_tweets);
            $return_data = array("tweets"=>$all_tweets, "tweet_count"=>$count);
            return $return_data;
        }

        public function getTweetsAfterId($id, $limit=20){
            //This gets $amount of tweets following the given id, by descending order. As new tweets have an incremental id value.
            //The older the tweet, the smaller the id value.
            //TODO: Test this
            
            $statement = $this->connection->prepare("SELECT * FROM ".$this->table_name." WHERE `id` < ? ORDER BY id DESC LIMIT ?");
            $statement->bind_param("ii",$id, $limit);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            $all_tweets = array();
            while($row = $result->fetch_assoc()){

                $result_array = $row;
                //print_r($result_array);
                array_push($all_tweets, $result_array);
            }

            //print_r($all_tweets);

            $count = count($all_tweets);
            $return_data = array("tweets"=>$all_tweets, "tweet_count"=>$count);

            //print_r($return_data, true);
            return $return_data;
        }

        public function createTweet($tweet_id, $username, $text, $created_at, $sentiment){

            echo "Create tweet triggered";

            if($this->checkIfExists($tweet_id)){
                return false;
            }else{

                try{
                    $created_at = date("Y-m-d H:i:s", strtotime($created_at));
                    echo "created at value: ".$created_at;
                    $current_time = date("Y-m-d H:i:s");
                    echo "current time value: ".$current_time;
                    $statement = $this->connection->prepare("INSERT INTO ".$this->table_name." (tweet_id, username, text, created_at, sentiment, time_saved) VALUES (?, ?, ?, ?, ?, ?)");
                    $statement->bind_param("sssssb",$tweet_id, $username, $text, $created_at, $sentiment, $current_time);

                    $statement->execute(); 
                    $statement->close();
                }catch(Exception $e){
                    LogError($e->message);
                    return false;
                }
                

                return true;
            }

        }

        private function sanitizeTweet($text){


        }

    }

?>