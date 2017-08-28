@extends('layouts.app')

@section('css')
    <link href="css/jquery.terminal-1.5.3.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
@endsection

@section('topscript')
    <script src="js/jquery.terminal-1.5.3.js"></script>
    <script>
        var editor;
        var fileName = false;

        $(document).ready(function () {

            editor = $("#input");
            $("#save").click(function () {
                if(fileName !== false){
                    $.post("/editor",{ '_token': $('meta[name=csrf-token]').attr('content'), value: editor.val(), file: fileName}, function (data) {
                        alert(data['MSG']);
                    }).fail(function(response) {
                        alert('Error: ' + response.responseText);
                    });
                } else{
                    alert("Make Sure File is open");
                }
            });

            $("#close").click(function () {
                    editor.val("");
                    fileName = false;
            });

            editor.keydown(function (e) {
                if(e.keyCode === 9) { // tab was pressed
                    var start = this.selectionStart;
                    var end = this.selectionEnd;
                    var $this = $(this);
                    var value = $this.val();
                    $this.val(value.substring(0, start)
                        + "\t"
                        + value.substring(end));
                    this.selectionStart = this.selectionEnd = start + 1;
                    e.preventDefault();
                }
            });

        });

        jQuery(function($, undefined) {
            $('#term').terminal(function(command, term) {
                var cmd = $.terminal.parse_command(command);
                this.pause();
                $.post("/shell",{ '_token': $('meta[name=csrf-token]').attr('content'), method: cmd.name, args :cmd.args}, function (data) {
                    term.resume();
                    switch (cmd.name){
                        case "cd":
                            if (data['STS'] === true)
                                term.set_prompt(data['MSG']);
                            else
                                term.echo(data['MSG']);
                            break;
                        case "request":
                            term.echo("\nRequesting challenge...");
                            setTimeout(function () {
                                term.echo(data['MSG']);
                            }, 200);
                            break;
                        case "clear":
                            term.clear();
                            break;
                        case "logout":
                            if (data['STS'] === true) {
                                term.echo(data['MSG']);
                                location.reload();
                            }
                            else{
                                term.echo(data['MSG']);
                            }
                            break;
                        case "submit":
                            if (data['STS'] === true)
                                term.set_prompt('<?php echo Auth::user()['name'] ?>@Castle:~$ ');
                            term.echo(data['MSG']);
                            break;
                        case "edit":
                            if(data["STS"] === true){
                                editor.val(data['MSG']);
                                fileName = data['FILE'];
                            } else{
                                term.echo(data['MSG']);
                            }
                            break;
                        default:
                            term.echo(data['MSG']);
                            break;

                    };

                });
            }, {
                tabcompletion: true,
                completion: function(command, callback) { ///write tab completion of files also iterating over $pwd here
                    callback(['cd', 'cat', 'clear', 'edit', 'help', 'ls', 'logout', 'request', 'status', 'submit', 'verify']);
                },
                greetings: 'Mounting /home/<?php echo Auth::user()['name']; ?>...',
                name: 'js',
                height: 200,
                prompt: '<?php echo Auth::user()['name'] ?>@Castle:<?php echo session('pwd')?>$ '
            });
        });
    </script>

@endsection>

@section('content')
    <!--Terminal-->
    <div class="container">

        <div class="row">
            <div id="term">
            </div>
        </div>

        <!--Editor-->
        <div class="editor row">


            <div class="row input-block">
                    <textarea id='input'>

                     </textarea>
            </div>

            <div class="navigation row">
                <div class="col s9">
                    <h5 class="white-text">File name</h5>
                </div>
                <div class="col s1" id="save">
                    <i class="material-icons">save</i>
                </div>
                <div class="col s1" id="close">
                    <i class="material-icons">close</i>
                </div>
            </div>

        </div>
    </div>
@endsection