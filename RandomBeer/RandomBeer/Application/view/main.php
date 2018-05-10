<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Beer App">
        <meta name="author" content="Marcin Milinski">

        <title>Beer App</title>

        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">

    </head>

    <body>

        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="/RandomBeer">BeerApp</a>
        </nav>

        <main role="main" class="container-fluid mt-3">

            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="jumbotron">
                                    <h1 class="display-4">Welcome to BeerApp!</h1>
                                    <p class="lead">The aim is to get random beer data from the external API.</p>
                                    <hr class="my-4">
                                    <p>Click below button to get another random beer information.</p>
                                    <p class="lead">
                                        <a class="btn btn-primary btn-lg" href="#" role="button" id="get-beer">Show another beer</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="jumbotron">
                                    <p>This section will be updated via AJAX with random beer data - just click the button on the left hand side.</p>
                                    <div id="beer_info">
                                        <?php echo $beer; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main><!-- /.container -->

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>
        (function(){
            "use strict";

            function escapeHtml() {
                return this.replace(/[&<>"'\/]/g, function (s) {
                    var entityMap = {
                        "&": "&amp;",
                        "<": "&lt;",
                        ">": "&gt;",
                        '"': '&quot;',
                        "'": '&#39;',
                        "/": '&#x2F;'
                    };

                    return entityMap[s];
                });
            }

            if (typeof(String.prototype.escapeHtml) !== 'function') {
                String.prototype.escapeHtml = escapeHtml;
            }
        })();
        
        $('#get-beer').click(function() {
            $.ajax({
                cache: false,
                type: "GET",
                dataType: "json",
                url: '/RandomBeer/',
                data: {
                    action: 'api'
                }
            }).done(function(data) {
                var beer = data[0];
                $('#beer-name').html(beer.name.escapeHtml());
                $('#beer-desc').html(beer.description.escapeHtml());
                $('#beer-abv').html(beer.abv.escapeHtml());
                $('#beer-location').html(beer.brewery_location.escapeHtml());
            }).fail(function(error) {
                var alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                'An error occured: ' + error.responseText +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                '</button>' + 
                            '</div>';
                $("#beer_info").before().html(alert);
            });
            
            return false;
        });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
