/*
------------------------------------------------------------

  proyecto:   VIWA. Anilizador Visual de Websites
  autor:      anto@usal.es
  
------------------------------------------------------------
*/
      
function crearGraph (divsource, urls_list)
{

      $(divsource).html("");

      $(divsource).append('<div id="loader"><img src="images/loader.gif" alt="loader"> </div>');
      $("#loader").show (300);


      var width = $( document ).width(), height = $( document ).height();

      var color = d3.scale.category20();

      var force = d3.layout.force()
          .charge(-parseInt($(document).height()/5))
          .linkDistance(parseInt($(document).height()/5))
          .size([width, height]);

      var svg = d3.select(divsource).append("svg")
          .attr("width", width)
          .attr("height", height)
          .attr("class", "pantalla_completa");

      var radio = parseInt($(document).height()/200);
      if (radio < 5) radio = 5;
     
      var urls = urls_list;
      
      // urls[0] = "http://www.nebusens.com";
      // urls[1] = "http://gsii.usal.es/guardian";

      // urls[0] = "http://localhost:8888/pags/1";
      // urls[1] = "http://localhost:8888/pags/2";

      for (var i=0; i<urls.length; i++)
      {
             urls[i] = encodeURIComponent(urls[i]);
      }
      var urlGET = "services/crow.json.php?urls[]="+urls.join("&urls[]=");

      d3.json(urlGET, function(error, graph) 
      {
          $("#loader").hide(300);

          force
            .nodes(graph.nodes)
            .links(graph.links)
            .start();

          var color = d3.scale.category10();

          var link = svg.append("g")
              .attr("class", "links")
              .selectAll(".link")
              .data(graph.links)
              .enter().append("line")
              .attr("class", function(d) { return "link "+(d.linker)+" "+(d.bridge); } )
              ;

          var leyenda = d3.select("body").append("div")   
              .attr("class", "tooltip")               
              .style("opacity", 0);

          var node = svg.append("g").attr("class", "nodes").selectAll(".node")
                  .data(graph.nodes)
                  .enter().append("circle")
                  .attr("class", "node")
                  .attr("r", radio)
                  .style("fill", function(d) { return color(d.group); })
                  .call(force.drag)
                  .on("mouseover", function(d) 
                      { 
                        var nodo = this;     
                        leyenda.transition()        
                            .duration(200)      
                            .style("opacity", .7);      
                        leyenda.html("<div class=\"title\">"+d.name+"<div>")
                            .style("left", d3.event.pageX + "px")     
                            .style("top", d3.event.pageY-30 + "px") ;    
                        })                  
                    .on("mouseout", function(d) {       
                        leyenda.transition()        
                            .duration(500)      
                            .style("opacity", 0);   
                    });

         
          force.on("tick", function() 
          {
              
              link.attr("x1", function(d) { return d.source.x; })
                  .attr("y1", function(d) { return d.source.y; })
                  .attr("x2", function(d) { return d.target.x; })
                  .attr("y2", function(d) { return d.target.y; })
                  ;

              node.attr("cx", function(d) { return d.x; })
                  .attr("cy", function(d) { return d.y; });    
          });

      });
}