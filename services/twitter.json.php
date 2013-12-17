
<?php
/*
------------------------------------------------------------

  proyecto:   VIWA. Anilizador Visual de Websites
  autor:      anto@usal.es
  
------------------------------------------------------------
*/

    require '..'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'tmhoauth'.DIRECTORY_SEPARATOR.'tmhOAuthExpanded.php';


    $tmhOAuth = new tmhOAuthExpanded();

    if (!isset($_GET["user"]))
          $_GET["user"] = "AlejandroSanz";


    error_log("Iniciando rastreo de tweet para: ".$_GET["user"]);

    $elementos = array ();

    $code = $tmhOAuth->apponly_request(array(
        'url' => $tmhOAuth->url('1.1/statuses/user_timeline'),
        'params' => array(
          'screen_name' => $_GET["user"],
          'count' => 10
        )
      ));


    if ($code == 200)
    {
          $data = json_decode($tmhOAuth->response['response'], true);
        
          // print_r($tmhOAuth->response['response']);

          $cuentaTweets = 0;

          foreach ($data as $tweet) 
          {

                      $datetime = new DateTime($tweet["created_at"]);
                      $datetime->setTimezone(new DateTimeZone('Europe/Zurich'));

                      $primary_tweet = $cuentaTweets;

                      $untweet = array (
                        "name"        => $tweet["text"],
                        "size"        => $tweet["retweet_count"],
                        "user_alias"  => $tweet["user"]["screen_name"],
                        "user"        => $tweet["user"]["screen_name"],
                        "user_photo"  => $tweet["user"]["profile_image_url"],
                        "date"        => $datetime->format('U'),
                        );


                        error_log ("Tweet: ".$tweet["id"]);

                        if ($tweet["retweet_count"] > 0)
                        {

                                // Buscamos quien ha hecho los retweets
                                $code = $tmhOAuth->apponly_request(array(
                                      'url'     => $tmhOAuth->url('1.1/statuses/retweets/'.$tweet["id"]),
                                      'params'  => array(
                                                  'id' => $tweet["id"]
                                                )
                                  ));      

                                if ($code == 200)
                                {
                                    $dataretweets = json_decode($tmhOAuth->response['response'], true);

                                    

                                    foreach ($dataretweets  as $retweet) 
                                    {
                                        $datetime_re = new DateTime($retweet["created_at"]);
                                        $datetime_re->setTimezone(new DateTimeZone('Europe/Zurich'));

                                        $untweet["children"][] = array (
                                          "name"        => $retweet["user"]["screen_name"],
                                          "size"        => $retweet["retweet_count"],
                                          "user"        => $retweet["user"]["screen_name"],
                                          "user_photo"  => $retweet["user"]["profile_image_url"],
                                          "date"        => $datetime_re->format('U')
                                          );

                                        
                                    } 
                                }
                                else error_log ("error solitando retweet :".$code);                               
                        
                        }

                        $elementos [] = $untweet;

          }

          // $elementos = array_values($elementos);
    }
    else
    {

    }
    print_r(json_encode(array ("name" => $_GET["user"], "children" => $elementos)));
?>