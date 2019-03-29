<?php

    include_once(__DIR__."/../config/database.php");

    class AnalysisModel{

        private $database;
        private $connection;
        private $table_name = "overall_sentiment";
        
        function __construct(){
            $this->database = new Database();
            $this->connection = $this->database->getConnection();

        }

        public function checkIfExists($id){
            $statement = $this->connection->prepare("SELECT id FROM ".$this->table_name." WHERE id = ?");
            $statement->bind_param("i",$id);
            $result = $statement->execute();
            $statement->close();

            if($result->num_rows == 0){
                return false;
            }else{
                return true;
            }
        }

        public function deleteEntry($id){
            if($this->checkIfExists($id)){
                return false;
            }else{
                $current_time = mktime();
                $statement = $this->connection->prepare("DELETE FROM ".$this->table_name." WHERE id = ?");
                $statement->bind_param("i",$id);
                $statement->execute(); 
                $statement->close();

                return true;
            }
        }

        public function updateOverallSentiment($id, $sentiment){
            $statement = $this->connection->prepare("UPDATE ".$this->table_name." SET sentiment = ? WHERE id = ?");
            $statement->bind_param("si", $sentiment, $id);
            $statement->execute();
            $statement->close();

        }

        public function getAllEntries($limit=50){

            $statement = $this->connection->prepare("SELECT * FROM ".$this->table_name." ORDER BY id DESC LIMIT ?");
            $statement->bind_param("i",$limit);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            $all_entries = array();
            while($row = $result->fetch_assoc()){

                $result_array = $row;
                //print_r($result_array);
                array_push($all_entries, $result_array);
            }

            //print_r($all_tweets);

            $count = count($all_entries);
            $return_data = array("entries"=>$all_entries, "count"=>$count);
            return $return_data;
        }

        public function getEntriesAfterId($id, $limit=20){
            //This gets $amount of tweets following the given id, by descending order. As new tweets have an incremental id value.
            //The older the tweet, the smaller the id value.
            //TODO: Test this
            
            $statement = $this->connection->prepare("SELECT * FROM ".$this->table_name." WHERE `id` < ? ORDER BY id DESC LIMIT ?");
            $statement->bind_param("ii",$id, $limit);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();

            $all_entries = array();
            while($row = $result->fetch_assoc()){

                $result_array = $row;
                //print_r($result_array);
                array_push($all_entries, $result_array);
            }

            //print_r($all_tweets);

            $count = count($all_entries);
            $return_data = array("entries"=>$all_entries, "count"=>$count);

            //print_r($return_data, true);
            return $return_data;
        }

        public function createEntry($created_at, $total_tweets, $positive_tweets, $negative_tweets, $neutral_tweets, $sentiment){

            try{
                echo "Create sentiment triggered";
                $created_at = date("Y-m-d H:i:s");
                $statement = $this->connection->prepare("INSERT INTO ".$this->table_name." (created_at, total_tweets, positive_tweets, negative_tweets, neutral_tweets, average_sentiment) VALUES (?, ?, ?, ?, ?, ?)");
                $statement->bind_param("biiiis",$created_at, $total_tweets, $positive_tweets, $negative_tweets, $neutral_tweets, $sentiment);

                $statement->execute(); 
                $statement->close();

                return true; 
            }catch(Exception $e){
                LogError($e->message);
            }
            
        }

    }

?>