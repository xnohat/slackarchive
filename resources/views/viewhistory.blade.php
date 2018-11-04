<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SlackArchiveBot</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700,700italic,900" rel="stylesheet" type="text/css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <link href="/static/css/app.css" rel="stylesheet">

    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>

<body>
    <div id="app" class="ready">
        <div class="splash" style="display: none;"><img src="/static/img/logo.b781b16.png" alt="SlackArchive.io" class="img-fluid">
            <div class="loader loader-sm"></div>
        </div>
        <!---->
        <div class="header">
            <div class="container-fluid">
                <div class="mobile-header">
                    <a href="javascript:togglenavbar()" class="navbar-toggle">
                        <div class="icon-bar"></div>
                        <div class="icon-bar"></div>
                        <div class="icon-bar"></div>
                    </a>
                    <div id="team" class="team-name">{{ config('slackarchive.team_name','Team Name') }}</div>
                </div>
                <ul class="header-nav">
                    <li class="hidden-sm"><a href="" target="_blank">{{ config('slackarchive.team_name','Team Name') }}</a></li>
                </ul>
                <div class="promo"><a href="" target="_blank"><img src="/static/img/logo.b781b16.png" alt="SlackArchive.io" class="logo logo-md"> <img src="/static/img/slackarchive.png" alt="slackarchive.cybertizen.com" class="logo logo-xs"></a></div>
            </div>
        </div>
        <div ng-cloak="" class="sidebar"><a href="" class="team-name router-link-active">{{ config('slackarchive.team_name','Team Name') }}</a>
            <div class="content">
                <form id="searchForm" class="search-form" method="GET" action="/searchhistory">
                    <div class="input-group">
                        <input name="query" type="text" size="30" placeholder="Search..." class="search-input"> <span class="icon icon-search"></span> <a href=""><span class="icon icon-remove" style="display: none;"></span></a>
                        <input name="sid" type="hidden" value="{{ $sessionid }}"
                    </div>
                </form>
                <h2 id="sepH2"><span class="filters"><a href="" class="active">Active</a> <a href="" class="">All</a></span> <span>Channels</span> <span style="display: none;">Filter by channel</span></h2>
                <div id="shadowContainer" class="shadow-container">
                    <ul id="channelsList" class="channels-list" style="height: 432px;">
                        @if($type == 'search')
                        <li><a href="" class="" id="channel-menu-community-sites"># search-results</a></li>
                        @endif
                        <li><a href="/viewhistory?sid={{ $sessionid }}" class="" id="channel-menu-community-sites"># {{ $channel }}</a></li>
                    </ul>
                    <div id="topShadow" class="sofa-scrolling-shadow-top" style="top: -10px;"></div>
                    <div id="bottomShadow" class="sofa-scrolling-shadow-bottom" style="bottom: 0px;"></div>
                </div>
            </div>
        </div>
        <div id="main_container" class="main-content">
            <div>
                <div class="loader messages-loader-top loader-xs" style="display: none;"></div>
                <ul id="messages" class="messages">
                    <!---->
                    @foreach ($messages as $message)
                    <li id="{{ $message->id }}" class="msg-type-">
                        <div class="msg-avatar"><span><img src="{{ $message->sender->avatar }}" class="msg-thumb"></span></div>
                        <div class="msg-container">
                            <div class="msg-header"><span class="msg-user">{{ $message->sender->name }}</span> <span class="msg-time"><a href="" rel="nofollow">{{ $message->ts }}</a> <!----> <!----> <br></span></div>
                            <div class="msg-body">
                                {!! slack_format_message($message->message) !!}
                                @if(isset($message->fileid))
                                <div class="msg-attachment" style="border-left-color: #E3E4E6">
                                    <p><b>{{ $message->filetitle }}</b></p>
                                    <p><a href="{{ $message->file_slack_url }}" target="_blank">{{ $message->filename }}</a></p>
                                    @if(strstr('jpg,png,jpeg,gif',$message->filetype) != false)
                                        <p><img class="attachmentimage" src="{{ $message->file_slack_url }}" alt="{{ $message->filetitle }}" /></p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        <!---->
                    </li>
                    @endforeach
                    <!---->
                </ul>
                <div class="loader messages-loader-center loader-sm" style="display: none;">sm</div>
                <div class="messages-loader-bottom">
                    <div class="loader loader-xs" style="display: none;"></div>
                </div>
                
            </div>
        </div>
        <div id="pagination" class="pagination-container">
            @if($type == 'search')
                {!! $messages->appends(['query' => $query,'sid' => $sessionid])->links('vendor.pagination.custom') !!}
            @else
                {!! $messages->appends(['sid' => $sessionid])->links('vendor.pagination.custom') !!}
            @endif
        </div>
    </div>
    <!--[if lt IE 10]>
<p class="browse-happy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
  your browser</a> to improve your experience.</p>
<![endif]-->

    <script type="text/javascript">

        
        document.addEventListener('DOMContentLoaded', function(){
            
            //Check have Page param ?
            if(checkPageParam() == false){ //if not redirect to last page
                window.location = '{!! $messages->url($messages->lastPage()) !!}';
            }

            //Scroll to bottom at on Loaded
            var main_content_window = document.querySelector('#main_container');
            main_content_window.scrollTop = main_content_window.scrollHeight;

            //Start tracking Scroll Behaviour for infinitive load
            infinityScrollFeed();
        });

        
        /**
         * Infinity Scroll Feed: Init Scroll Feed listener detect scroll event
         */
        function infinityScrollFeed() {
            var didScroll = false;

            $('#main_container').scroll(function() { //watches scroll of the window
                didScroll = true;
            });

            // Sets an interval so your window.scroll event doesn't fire constantly.
            // This waits for the user to stop scrolling for not even a second and then
            // fires the pageCountUpdate function (and then the getPost function)
            setInterval(function() {
                if (didScroll) {
                    didScroll = false;
                    if ($('#main_container').scrollTop() == 0) {
                        setTimeout(function(){
                            window.location = '{!! $messages->previousPageUrl() !!}';
                        },3000);
                        
                    }
                }
            }, 250);
        }

        function getCurrentPage(){
            var url = new URL(window.location);
            var page = url.searchParams.get("page");
            if(page == null){
                return 1;
            }else{
                return page;
            }
        }

        function checkPageParam(){
            var url = new URL(window.location);
            var page = url.searchParams.get("page");
            if(page == null){
                return false;
            }else{
                return true;
            }
        }

	    function togglenavbar(){
		    if (document.getElementById("app").className == 'ready') { // Check the current class name
			        document.getElementById("app").className = "with-menu";   // Set other class name
			} else {
			        document.getElementById("app").className = "ready";  // Otherwise, use `second_name`
		    }
		    return false;
		}   
    </script>

    <script type="text/javascript" src="/static/js/slackemojireplacer.js"></script>

</body>

</html>