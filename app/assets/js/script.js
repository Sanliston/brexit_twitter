$(document).ready(function(){
    
    //set up global functions
    window.currentPage = "overview";

    if(window.currentPage == "overview"){
        initializeOverview();
    }else if(window.currentPage == "statistics"){

    }else if(window.currentPage == "about"){

    }
});

function initializeOverview(){
    getTweets();
}

function initializeStatistics(){

}

function initializeAbout(){

}

function getTweets(){

    $.ajax({
        type: "GET",
        url: 'http://ec2-18-188-118-137.us-east-2.compute.amazonaws.com/web/api/tweets/read.php',
        contentType: 'application/json',
        dataType:'json',
        responseType:'application/json',
        success: function(response) {
          console.log("Call to server successful, response: "+JSON.stringify(response));
          updateTweetsContainer(response);
        },
        error: function(response) {
            console.log("Call to server unsuccessful, response: "+JSON.stringify(response));
        },
    });
}

function updateTweetsContainer(data){
   var tweetsArray =  data['tweets'];

   //iterate through tweets and insert into tweets container
   var tweetsContainer = $('#ov-tweets-container');
   tweetsContainer.html('');

   for(i in tweetsArray){
        var tweet = tweetsArray[i];
        var id = tweet["id"];
        var username = tweet["username"];
        var text = tweet["text"];
        var sentiment = tweet["sentiment"];
        var sentimentIcon = "sentiment_satisfied"; //this is the neutral sentiment icon

        if(sentiment == "positive"){
            sentimentIcon = "sentiment_very_satisfied";
        }else if(sentiment == "negative"){
            sentimentIcon = "sentiment_very_dissatisfied";
        }
        
        var element =   '<div id="'+id+'" class="ov-tweet-element">'+
                            '<div class="ov-tweet-element-header">'+
                                '<span class="ov-tweet-element-username">'+username+'</span>'+
                                '<div class="ov-tweet-element-sentiment-container">'+
                                    '<span>Sentiment: '+sentiment+'</span>'+
                                    '<i class="material-icons ov-tweet-element-sentiment" style="font-size: 32px;">'+sentimentIcon+'</i>'+
                                '</div>'+
                            '</div>'+
                            '<span class="ov-tweet-element-content">'+
                                text+
                            '</span>'+
                        '</div>';
        
        tweetsContainer.append(element);
   }
}
