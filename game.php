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
            $.jrpc("shell.php",cmd.name,[cmd.args],
                function(data){
                    term.resume();
                    term.echo(data.result);
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
        completion: function(command, callback) {
            callback(['cd', 'cat', 'clear', 'edit', 'help', 'ls', 'logout', 'request', 'status', 'submit', 'verify']);
        },
        greetings: 'Mounting /home/user...',
        name: 'js',
        height: 200,
        prompt: 'user@Castle:~/$ '
    });
});
</script>
</head>
<body>
	<div id="term"></div>
</body>

</html>