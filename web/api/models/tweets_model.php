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

            if($result->num_rows == 0){
                return false;
            }else{
                return true;
            }
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

            $limit = $limit*2;
            
            $statement = $this->connection->prepare("SELECT * FROM ( SELECT * FROM ".$this->table_name." ORDER BY id DESC LIMIT ".$limit.") sub ORDER BY id ASC");
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            $all_tweets = array();
            while($row = $result->fetch_array(MYSQLI_NUM)){

                $result_array = $result->fetch_assoc();
                //print_r($result_array);
                array_push($all_tweets, $result_array);
            }

            print_r($all_tweets);

            $count = count($all_tweets);
            echo "Total number of tweets = ".$count;
        }

        public function createTweet($tweet_id, $username, $text, $created_at, $sentiment){

            echo "Create tweet triggered";

            if($this->checkIfExists($tweet_id)){
                return false;
            }else{
                $created_at = date("Y-m-d H:i:s", strtotime($created_at));
                echo "created at value: ".$created_at;
                $current_time = date("Y-m-d H:i:s");
                echo "current time value: ".$current_time;
                $statement = $this->connection->prepare("INSERT INTO ".$this->table_name." (tweet_id, username, text, created_at, sentiment, time_saved) VALUES (?, ?, ?, ?, ?, ?)");
                $statement->bind_param("sssssb",$tweet_id, $username, $text, $created_at, $sentiment, $current_time);

                $statement->execute(); 
                $statement->close();

                return true;
            }

        }

        private function sanitizeTweet($text){


        }

    }


?>