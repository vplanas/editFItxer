<?php
require __DIR__ . '/vendor/autoload.php';
/**
 * Generate a 32 byte random value. Can also use these other methods:
 *  - generateInt() to output integers up to PHP_INT_MAX
 *  - generateString() to map values to a specific character range
 */
$factory = new \RandomLib\Factory;
$generator = $factory->getMediumStrengthGenerator();
//Iniciem sessió per inicialitzar el cross-doamin control del formulari
session_start();
$_SESSION["csrf_token"]=hash('sha512', $generator->generate(32));
?>

<!DOCTYPE html>
<html lang="ca">
    <head>
        <title>Edició de Fitxer</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <style type="text/css" media="screen">
            #editor {
                display: block;
                height:300px;
            }
            .botons {
                margin-top:20px;
                text-align: right;
            }
            #result-messages {
                margin-top:20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Edició de Fitxer</h1>
                    <div id="editor"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="col-results">
                  <div class="alert" id="result-messages"></div>
                </div>
                <div class="col-md-6 botons">
                    <button id="desfes" class="btn btn-warning">Desfés</button>
                    <button id="desa" class="btn btn-success">Desa</button>
                </div>
            </div>


        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://code.jquery.com/jquery-2.2.1.min.js" bintegrity="sha256-gvQgAFzTH6trSrAWoH1iPo9Xc96QxSZ3feW6kem+O00=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script>
            $(function() {
                var editor = ace.edit("editor");
                editor.$blockScrolling = Infinity;
                editor.setTheme("ace/theme/chrome");
                editor.getSession().setMode("ace/mode/plain_text");
                editor.getSession().on('change', function(e) {
                    $('#desa').prop('disabled', false);
		    $('#desfes').prop('disabled', false);
                });
                $.get("file.txt").done(function (data) {
                    editor.setValue(data);
                    editor.gotoLine(editor.session.getLength() - 1);
                    editor.execCommand("gotolineend");
                });
                $('#desa').click(editor,function () {
                    $.ajax({
                        method: "POST",
                        url: "srv.php",
                        data: { action:'save' ,content: editor.getValue(), csrf_token: '<?php echo $_SESSION["csrf_token"]; ?>' },
                        beforeSend : function() {
                          $('#col-results').html('<div class="alert" id="result-messages"></div>')
                        }
                    })
                    .error(function( msg ) {
                        $('#result-messages')
                          .html('Hi ha hagut algun error')
                          .addClass('alert-danger');
                    })
                    .success(function( msg ) {
                        $('#result-messages')
                          .html('S\'ha desat el fitxer')
                          .addClass('alert-success');
                        $('#desa').prop('disabled', true);
			$('#desfes').prop('disabled', true);
                    });
                });
                $('#desfes').click(editor,function () {
                    $.get("file.txt").done(editor,function (data) {
                        editor.setValue(data);
                        editor.gotoLine(editor.session.getLength());
                        editor.execCommand("gotolineend");
                    });
                })
		.prop('disabled', true);
            });
        </script>
    </body>
</html>
