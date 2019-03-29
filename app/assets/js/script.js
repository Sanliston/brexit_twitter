$(document).ready(function(){
    
    //set up global functions
    window.currentPage = "overview";
    window.initialFetchComplete = false;
    window.scrollInactive = true;
    window.nextCallInProgress = false;
    window.updateInProgress = false;

    if(window.currentPage == "overview"){
        initializeOverview();
    }else if(window.currentPage == "statistics"){

    }else if(window.currentPage == "about"){

    }

});

function initializeOverview(){
    window.updateInProgress = true;
    getTweets();
    getOverallSentiment();
    bindScrollEvent();
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
        data: {'id': id, 'count': 20},
        responseType:'application/json',
        success: function(response) {
            var loadingContainer = $('#loading-animation-container');
            loadingContainer.css({'display':'none'});
            window.nextCallInProgress = false;
            console.log("Call to server to get next tweets successful, response: "+JSON.stringify(response));
            addToTweetsContainer(response);
        },
        error: function(response) {
            window.nextCallInProgress = false;
            console.log("ERROR: Call to server to get next tweets unsuccessful, response: "+JSON.stringify(response));
        },
    });
}

function getOverallSentiment(){
    $.ajax({
        type: "GET",
        url: 'http://ec2-18-188-118-137.us-east-2.compute.amazonaws.com/web/api/analysis/read.php',
        contentType: 'application/json',
        dataType:'json',
        responseType:'application/json',
        success: function(response) {
          console.log("Call to server successful, response: "+JSON.stringify(response));
          populateOverallSentiment(response);
        },
        error: function(response) {
            console.log("ERROR: Call to server unsuccessful, response: "+JSON.stringify(response));
        },
    });
}

function populateOverallSentiment(data){
    var result = calculateOverallSentiment(data);
    var sentimentIcon = $('#ov-overall-sentiment-icon');
    var sentimentText = $('#ov-sentiment-value');

    if(result.overallSentiment == "Positive"){
        sentimentIcon.text('sentiment_very_satisfied');
    }else if(result.overallSentiment == "Negative"){
        sentimentIcon.text('sentiment_very_dissatisfied');
    }else{
        sentimentIcon.text('sentiment_satisfied');
    }

    sentimentText.text(result.overallSentiment);
}

function calculateOverallSentiment(data){
    var entriesArray = data["entries"];

    var totalTweets = 0;
    var totalPositiveTweets = 0;
    var totalNegativeTweets = 0;
    var totalNeutralTweets = 0; 

    for(i in entriesArray){
        var entry = entriesArray[i];
        var tweetsCount = entry['total_tweets'];
        var positiveTweets = entry['positive_tweets'];
        var negativeTweets = entry['negative_tweets'];
        var neutralTweets = entry['neutral_tweets'];

        totalTweets = totalTweets + tweetsCount;
        totalPositiveTweets = totalPositiveTweets+positiveTweets;
        totalNegativeTweets = totalNegativeTweets+negativeTweets;
        totalNeutralTweets = totalNeutralTweets+negativeTweets;

    }

    var overallSentiment = "Neutral";
    var largestValue = Math.max(totalPositiveTweets, totalNegativeTweets, totalNeutralTweets);

    if(largestValue == totalPositiveTweets){
        overallSentiment = "Positive";
    }else if(largestValue == totalNegativeTweets){
        overallSentiment = "Negative";
    }else{
        overallSentiment = "Neutral";
    }

    var percentagePositive = (totalPositiveTweets/totalTweets)*100;
    var percentageNegative = (totalNegativeTweets/totalTweets)*100;
    var percentageNeutral = (totalNeutralTweets/totalTweets)*100;

    var result = {
        overallSentiment: overallSentiment,
        percentagePositive: percentagePositive,
        percentageNegative: percentageNegative,
        percentageNeutral: percentageNeutral,
        totalTweets: totalTweets
    };

    return result;
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
   window.updateInProgress = false;
}

function addToTweetsContainer(data){
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

    if(window.scrollInactive){
        //event for when user scrolls to bottom of page
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {

                if(window.nextCallInProgress || window.updateInProgress){
                    console.log('Update in progress.');
                    return false;
                }else{
                    var loadingContainer = $('#loading-animation-container');
                    loadingContainer.css({'display':'flex'});
                    prepareNextTweets(); 
                }
                
            }else if($(window).scrollTop() > 0){
                //for header
                toggleHeader("active");

            }else{
                toggleHeader("inactive");
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

function latestTweetsHandler(button){
    window.updateInProgress = true;
    $(button).css({'display': 'none'});
    var tweetsContainer = $('#ov-tweets-container');
   tweetsContainer.html('');
   var loaderElement = '<div id="ov-loading-animation" class="loader"></div>';
   tweetsContainer.html(loaderElement);
    getTweets();
}

function toggleHeader(state){

    var header = $('#persistent-header');
    var headerOptionHighlight = $('.header-option-highlight');

    if(state == "inactive"){

        if(header.hasClass('persistent-header-inactive')){
            return true;
        }

        //change header class
        header.removeClass('persistent-header-active');
        header.addClass('persistent-header-inactive');

        //do same for highlight
        headerOptionHighlight.removeClass('header-option-highlight-active');
        headerOptionHighlight.addClass('header-option-highlight-inactive');

    }else if("active"){

        if(header.hasClass('persistent-header-active')){
            return true;
        }

        //change header class
        header.removeClass('persistent-header-inactive');
        header.addClass('persistent-header-active');

        //do same for highlight
        headerOptionHighlight.removeClass('header-option-highlight-inactive');
        headerOptionHighlight.addClass('header-option-highlight-active');
    }
}
