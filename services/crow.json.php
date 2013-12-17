
<?php
/*
------------------------------------------------------------

  proyecto:   VIWA. Anilizador Visual de Websites
  autor:      anto@usal.es
  
------------------------------------------------------------
*/

// -----------------------------------------------------------------------------
// Tiempo límite para rastrear un sítio
// -----------------------------------------------------------------------------
  set_time_limit(10000);

// -----------------------------------------------------------------------------
// Includes
// -----------------------------------------------------------------------------

  include("..".DIRECTORY_SEPARATOR."php".DIRECTORY_SEPARATOR."PHPCrawler".DIRECTORY_SEPARATOR."libs".DIRECTORY_SEPARATOR."PHPCrawler.class.php");

// -----------------------------------------------------------------------------
// Funciones
// -----------------------------------------------------------------------------

    // Devuelve la posición de un elemento en un array
    function posEnArray ($elemento, $array)
    { 
        $i = 0;
        foreach ($array as $clave => $valor) 
        {
            if ($valor["name"] == $elemento) return $i;
            $i++;
        }
        return NULL;
    }

    // Devuelve la posición de un elemento en un array
    function esEnlaceDuro($source, $target, &$array)
    { 
        foreach ($array as $clave => $valor) 
        {
          if (isset($valor["target"]))
          {
            if (($valor["target"] == $target) && ($valor["source"] == $source)) 
              {
                $array[$clave]["linker"] = "hard";
                return true;
              }
          }
          else break;
        }
        return false;
    }

    // Devuelve la posición de un elemento en un array
    function analizandoGrupo ($elemento, &$array)
    { 
        $i = 1;
        
        // $elemento = str_replace("http:\/\/www.", "http:\/\/", $elemento);
        
        foreach ($array as $valor) 
        {
            // Eliminamos las mayúsculas
            if (strpos (strtolower ($elemento), strtolower ($valor)) !== false) return $i;
            $i++;
        }
        
        // Si no ha encontrado el grupo, es que es un grupo nuevo y
        // lo añadimos
        $parse = parse_url($elemento);
        $array[] = $parse['host'];
        return $i;
        
    }    

// -----------------------------------------------------------------------------
// Clases
// -----------------------------------------------------------------------------

    // Extendemos la clase
    class MyCrawler extends PHPCrawler 
    {
        // Esta función se invoca una vez se haya leido y analizado una página
        function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
        {
                global $elementos;
                if ($DocInfo->bytes_received > 0)
                {
                  $pagina       = $DocInfo->url;
                  foreach ($DocInfo->links_found as $enlace) 
                  {
                        $elementos["links"][] =  array ($pagina , $enlace["url_rebuild"] );
                        $elementos["nodes"][] =   $enlace["url_rebuild"];
                  }
                  $elementos["nodes"][] = $pagina;
                }
              
        }
    }

// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------

// Almacen para resultados y contador de grupos
// Funciona como variables globales para este archivo
$elementos = array ("nodes"=>array(), "links"=>array(), 
                      "statistics" => array(
                            "nodes" => 0,
                            "links" => 0,
                            "bidireccional_links" => 0,
                           )  
                    );

    $crawler = new MyCrawler();
    $misUrls = array();

    $grupos_inicales = 1;

    foreach ($_GET["urls"] as $valor)
    {
        $valor = str_replace("http:\/\/www.", "http:\/\/", $valor);
        $misUrls [] = $valor;
        $grupos_inicales ++;
    }

    $misUrls = array_unique ($misUrls);

    foreach ($misUrls as $miUrl) 
    {

          // error_log("Analizando: ".$miUrl);
          // URL a crawlear
          $crawler->setURL($miUrl);

          $elementos["nodes_tmp"][] = $miUrl;

          // Solo se analiza cóidgo html
          $crawler->addContentTypeReceiveRule("#text/html#");

          // Filtro para los archivo
          $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");

          // Alamcenamos cookies, simulado que se trata de un navegador
          $crawler->enableCookieHandling(true);

          // Establecemos un tamño máximo para leer los archivos del sítio
          // Si se pasa en este caso de 1Mb de html leidó se para la inspección
          $crawler->setTrafficLimit(1000 * 1024);

          // Iniciamos el crawler
          $crawler->go();
          // error_log("Fin del análisis ");

    }

    // Reindexamos y colocamos los valores

    $elementos["nodes"] = array_unique ($elementos["nodes"]);
    
    foreach ($elementos["nodes"] as $clave => $valor)
    {
        $grupo =  analizandoGrupo ($valor, $misUrls);
        $elementos["nodes"][$clave] = array ("name" => $valor ,"group" => $grupo);
        $elementos["statistics"]["nodes"] ++;
    }

    // Actualizamos indices
    $elementos["nodes"]= array_values($elementos["nodes"]);

    foreach ($elementos["links"] as $clave => $enlace)
    {
        $target = posEnArray ($enlace[0], $elementos["nodes"]);
        $source = posEnArray ($enlace[1], $elementos["nodes"]);

        // Buscamos los id y remplazamos la lista por su correspondientes valores
        if (!esEnlaceDuro ($target, $source, $elementos["links"]))
        {
            $elementos["links"][$clave] = array (
                "target"=> $target, 
                "source"=> $source, 
                "value" => 1);

            // Solo evalúa los grupos iniciales
            
            $grupo1 = $elementos["nodes"][$target]["group"];
            $grupo2 = $elementos["nodes"][$source]["group"];

            // error_log($grupo1." <> ".$grupo2);
            if (($grupo1 != $grupo2) && ($grupo1 < $grupos_inicales) && ($grupo2 < $grupos_inicales))
            {
                $elementos["links"][$clave]["bridge"] = "bridge";
            }
            $elementos["statistics"]["links"] ++;
        }
        // En el caso de ser un puente, lo marcamos y borramos el actual enlace
        else 
        {

            unset($elementos["links"][$clave]);
            $elementos["statistics"]["bidireccional_links"] ++;
        }
    }

    // Actualizamos indices
    $elementos["links"]= array_values($elementos["links"]);

    print_r(json_encode($elementos));

?>