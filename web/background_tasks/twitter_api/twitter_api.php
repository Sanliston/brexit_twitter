<?php
    //This is a small script that gets data from the twitter API every minute and puts it in the database
    require_once('TwitterAPIExchange.php');

    class TwitterAPI{

        private $access_token = null;
        private $access_token_secret = null;
        private $api_key = null;
        private $api_secret = null;
        private $get_field = "?";
        private $request_method = "GET";
        private $url = "https://api.twitter.com/1.1/search/tweets.json";

        function __construct($search_query, $count, $lang, $since_id = null, $geocode = null ){

            $this->get_field = $this->get_field."q=".$search_query."&count=".$count."&lang=".$lang."&tweet_mode=extended&exclude=retweets";

            if($since_id){
                $this->get_field = $this->get_field."&since_id=".$since_id;
            }

            if($geocode){
                $this->get_field = $this->get_field."&geocode=".$geocode;
            }

            $this->configureAPI();
        }

        private function configureAPI(){
            $config = file_get_contents("api_config.json");
            $config = json_decode($config, true);

            $this->access_token = $config['access_token'];
            $this->access_token_secret = $config['access_token_secret'];
            $this->api_key = $config['api_key'];
            $this->api_secret = $config['api_secret'];
        }

        public function makeCall(){

            $parameters = array(
                'oauth_access_token' => $this->access_token,
                'oauth_access_token_secret' => $this->access_token_secret,
                'consumer_key' => $this->api_key,
                'consumer_secret' => $this->api_secret
            );

            $call = new TwitterAPIExchange($parameters);
            $response = json_decode(
                            $call->setGetfield($this->get_field)
                                ->buildOauth($this->url, $this->request_method)
                                ->performRequest()
                            ,$assoc=TRUE
                        );

            return $response["statuses"];
        }


    }

    $twitter_api = new TwitterAPI("brexit", 100, "en");
    $tweets_array = $twitter_api->makeCall();
?>
