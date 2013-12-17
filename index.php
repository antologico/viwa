<!DOCTYPE html>
<head>

  <meta charset="utf-8">
  <title>VIWA | Visual Sites Analyzer</title> 
  <meta name="description" content="Visual Sites Analyzer">
  <meta name="keywords" content="HTML,CSS,XML,JavaScript">
  <meta name="author" content="Antonio Juan Sánchez Martín. ant@usal.es">
   
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />

  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="css/crow.css" />

  <script src="javascript/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="javascript/d3.v3.min.js"></script>
  <script src="javascript/viwa-crow.js"></script>
  <script src="javascript/viwa-twitter.js"></script>
  <script src="javascript/gui.js"></script>

</head>
<body>

  <div id="splash-screen"><img src="images/splashscreen.svg" alt="logo" /></div>
  <div id="logo"><img src="images/logo.svg" alt="logo" /></div>
  <div id="menu-info"><img src="images/globe.png" alt="globo" /><div class="text">menu</div></div>
  <div id="menu">

      <ul id="crawTab" class="nav nav-tabs tabs-viwa">
        <li class="active"><a href="#tab_crawler">Crawler</a></li>
        <li><a href="#tab_twitter">Twitter</a></li>
      </ul>

      <div id="myTabContent" class="tab-content">
              <div class="tab-pane fade active in" id="tab_crawler">
                    <div id="site_list">
                          <div id="site_list_inputs"></div>
                          <div id="site_list_add">
                              <button  id="add_site_button" type="button" class="btn btn-info btn-xs">
                                  <span class="glyphicon glyphicon-plus"></span> New Site
                              </button>

                              <button  id="remove_site_button" type="button" class="btn btn-danger btn-xs">
                                  <span class="glyphicon glyphicon-remove-circle"></span> Clear emptys
                              </button>

                          </div>
                    </div>
                    <div class="button_list" >
                            <button  id="search_button" type="button" class="btn btn-success btn-sm">
                                  <span class="glyphicon glyphicon-search"></span> Analize 
                            </button>
                    </div>
              </div>
              <div class="tab-pane fade" id="tab_twitter">
                  <div class="input-group site_input_group">
                      <span class="input-group-addon glyph-at">@</span>
                      <input type="text" id="input_tweet" class="form-control">
                  </div>
                  
                  <div class="button_list" >
                        <button  id="search_tweets" type="button" class="btn btn-success btn-sm">
                            <span class="glyphicon glyphicon-search"></span> Analize ReTweets by User
                        </button>
                  </div>

              </div>
      </div>

  </div>
  <div id="visualizer"></div>


</body>
</html>
