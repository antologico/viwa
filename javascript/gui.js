/*
------------------------------------------------------------

  proyecto:   VIWA. Anilizador Visual de Websites
  autor:      anto@usal.es
  
------------------------------------------------------------
*/

 $(document).on( "ready", function ()
 {

      $('#crawTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      })

      var num_input_id = 0;
      function addInputSite ()
      {
          num_input_id ++;
          var input_id = "grup_inputs_"+num_input_id;

          $("#site_list_inputs").prepend ('<div id="'+input_id+'" class="input-group site_input_group"><span class="input-group-addon"> <span class="glyphicon glyphicon-link"></span> </span><input type="text" id="site[]" class="form-control"></div>');

          $("#"+input_id).css("opacity", "0");
          $("#"+input_id).animate({"opacity": "1"}, 400);
      }

      $("#add_site_button").click (function()
      {
           addInputSite ();
      });

      if ($("#site_list_inputs").html() == "") addInputSite ();

      $("#remove_site_button").click (function()
      {
          var inputs = $("#site_list_inputs :input");
          var site_list = Array ();
          inputs.each (function (i) {
              if ($(this).val() == "")
              {
                  var parent = $(this).parent();
                  parent.hide(300).delay(400).queue (function(){ parent.remove();});
              }
          });
      });

      $("#search_button").click (function()
      {
          var inputs = $("#site_list_inputs :input");

          var site_list = Array ();
          inputs.each (function (i)
          {
              var valor = $(this).val();
              if (valor != "")
                    site_list.push(valor);
              else
              {
                  var parent = $(this).parent();
                  parent.hide(300).delay(400).queue (function(){ parent.remove();});
              }
          });
          
          crearGraph ("#visualizer", site_list);
      });

      $("#splash-screen").delay(3000).animate({opacity: 0}, 500, function() { $(this).remove();});

      $("#search_tweets").click (function()
      {
          if ($("#input_tweet").val() != "")
              verTweets ("#visualizer", $("#input_tweet").val()); 
      });

  });