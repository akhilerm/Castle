<!DOCTYPE html>
<html>
<head>
    <title>Castle</title>

    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="js/jquery.terminal-1.5.3.js"></script>
    <link href="css/jquery.terminal-1.5.3.css" rel="stylesheet"/>
    <script>
        jQuery(function($, undefined) {
            $('#term').terminal(function(command, term) {
                var cmd = $.terminal.parse_command(command);

                this.pause();
                $.jrpc("http://localhost:8000/dashboard",cmd.name,[cmd.args],
                    function(data){   // process data.result depending on the command and return value
                        term.resume();
                        if (cmd.name == "cd"){
                            if (data.result[1] == "true")
                                term.set_prompt(data.result[0]);
                            else
                                term.echo(data.result[0]);
                        }
                        else if (cmd.name == "test") {
                            term.echo("\nRequesting challenge...\n");
                            setTimeout(function () {
                                term.echo(data.result);
                            }, 400);
                        }
                        else {
                            term.echo(data.result);
                        }
                    },
                    function(xhr, status, error) {
                        term.error('[AJAX] ' + status +' - Server reponse is: \n' + xhr.responseText);
                        term.resume();
                    });

                /*else {
                 this.echo('\n'+cmd.name+': command not found. Type [[;orange;]help] for a list of commands\n');
                 }*/
            }, {
                tabcompletion: true,
                completion: function(command, callback) { ///write tab completion of files also iterating over $pwd here
                    callback(['cd', 'cat', 'clear', 'edit', 'help', 'ls', 'logout', 'request', 'status', 'submit', 'verify','test']);
                },
                greetings: 'Mounting /home/swaraj...',
                name: 'js',
                height: 200,
                prompt: '@Castle:'
            });
        });
    </script>
</head>
<body>
<div id="term"></div>
</body>

</html>