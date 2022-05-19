<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Documentación de la API</title>
    <link rel="stylesheet" type="text/css" href="/swagger/dist/swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="/swagger/dist/index.css" />
    <link rel="icon" type="image/png" href="/swagger/dist/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/swagger/dist/favicon-16x16.png" sizes="16x16" />
  </head>

  <body>
    <div id="swagger-ui"></div>
    <script src="/swagger/dist/swagger-ui-bundle.js" charset="UTF-8"> </script>
    <script src="/swagger/dist/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
    
    <script>
      window.onload = function() {
      //<editor-fold desc="Changeable Configuration Block">

      // the following lines will be replaced by docker/configurator, when it runs in a docker-container
      window.ui = SwaggerUIBundle({
        url: "<?php echo base_url(route_to("documentation_json")) ?>",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout"
      });

      //</editor-fold>
    };

    </script>
  </body>
</html>