$(document).ready(function(){
    
    //set up global functions
    window.currentPage = "overview";
    window.initialFetchComplete = false;
    window.scrollInactive = true;
    window.nextCallInProgress = false;

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
          //console.log("Call to server successful, response: "+JSON.stringify(response));
          populateTweetsContainer(response);
        },
        error: function(response) {
            console.log("ERROR: Call to server unsuccessful, response: "+JSON.stringify(response));
        },
    });
}

function getNextTweets(id){

    $.ajax({
        type: "POST",
        url: 'http://ec2-18-188-118-137.us-east-2.compute.amazonaws.com/web/api/tweets/next.php?id='+id,
        contentType: 'application/json',
        dataType:'json',
        data: {'id': id},
        responseType:'application/json',
        success: function(response) {
            var loadingContainer = $('#loading-animation-container');
            loadingContainer.css({'display':'none'});
            window.nextCallInProgress = false;
            console.log("Call to server to get next tweets successful, response: "+JSON.stringify(response));
            updateTweetsContainer(response);
        },
        error: function(response) {
            window.nextCallInProgress = false;
            console.log("ERROR: Call to server to get next tweets unsuccessful, response: "+JSON.stringify(response));
        },
    });
}

function populateTweetsContainer(data){
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

   window.initialFetchComplete = true;
   bindScrollEvent();
}

function updateTweetsContainer(data){
    var tweetsArray =  data['tweets'];
 
    //iterate through tweets and insert into tweets container
    var tweetsContainer = $('#ov-tweets-container');
 
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


function bindScrollEvent(){

    if(window.initialFetchComplete && window.scrollInactive){
        //event for when user scrolls to bottom of page
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {

                if(window.nextCallInProgress){
                    return false;
                }else{
                    var loadingContainer = $('#loading-animation-container');
                    loadingContainer.css({'display':'flex'});
                    prepareNextTweets(); 
                }
                
            }
        });

        window.scrollInactive = false;
    }
}

function prepareNextTweets(){
    //get last element in tweet container
    window.nextCallInProgress = true;
    var tweetsContainer = $('#ov-tweets-container');
    var bottomTweet = tweetsContainer.children().last();
    var id = bottomTweet.attr('id');

    console.log("obtained id: "+id);
    getNextTweets(id);
}
