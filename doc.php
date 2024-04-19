<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>

    <!-- Import Swagger UI from CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.16.0/swagger-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.16.0/swagger-ui-bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.16.0/swagger-ui-standalone-preset.js"></script>
</head>

<body>
    <div id="swagger-ui"></div>

    <script>
        window.onload = function() {
            // Initialize Swagger UI
            const ui = SwaggerUIBundle({
                url: "swagger.json", // Use relative path to your local Swagger/OpenAPI file
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ]
            })
        }
    </script>
</body>

</html>