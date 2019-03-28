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
        },
        error: function(response) {
            console.log("Call to server unsuccessful, response: "+JSON.stringify(response));
        },
    });
}
