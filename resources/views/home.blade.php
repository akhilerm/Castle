
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
        var countDownDate = {{ $time }};
        var clock;
        var terminal;
        $(document).ready(function () {

            editor = $("#input");
            clock = $("#time");
            clock.hide();

            //To Check if Mobile
            var isMobile = {
                Android: function() {
                    return navigator.userAgent.match(/Android/i);
                },
                BlackBerry: function() {
                    return navigator.userAgent.match(/BlackBerry/i);
                },
                iOS: function() {
                    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                },
                Opera: function() {
                    return navigator.userAgent.match(/Opera Mini/i);
                },
                Windows: function() {
                    return navigator.userAgent.match(/IEMobile/i);
                },
                any: function() {
                    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                }
            };

            if (isMobile.any()){
                window.location.replace("/mobile");
            }
            
            // Update the count down every 1 second
            var x = setInterval(function() {
                if (countDownDate !== 0) {
                    // Get todays date and time
                    var now = Math.round(new Date().getTime() / 1000);

                    // Find the distance between now an the count down date
                    var distance = countDownDate - now;

                    // Time calculations for hours, minutes and seconds
                    var hours = Math.floor(distance / (60 * 60));
                    var minutes = Math.floor((distance % (60 * 60)) / (60));
                    var seconds = Math.floor(distance % 60);

                    // countdown fininshed.
                    if (distance <= 0) {
                        //clearInterval(x);
                        $.post("/timeout", {'_token': $('meta[name=csrf-token]').attr('content')}, function (data) {
                            if (data['result'] === true) {
                                alert('Timed Out');
                                countDownDate = 0;
                                clock.text("00:00:00");
                                clock.hide();
                                terminal.set_prompt('<?php echo Auth::user()['name'] ?>@Castle:<?php echo session('pwd')?>$ ');
                                fileName = false;
                            }
                        }).fail(function (response) {
                            alert('Error: ' + response.responseText);
                        });
                    }

                    // Display the result
                    clock.text(hours + " : " + minutes + " : " + seconds);
                    if (!clock.is(":visible")){
                        clock.show();
                    }
                }
            }, 1000);


            var saveData = function () {
                if(fileName !== false){
                    $.post("/editor",{ '_token': $('meta[name=csrf-token]').attr('content'), value: editor.val(), file: fileName}, function (data) {
                        alert(data['MSG']);
                    }).fail(function(response) {
                        alert('Error: ' + response.responseText);
                    });
                } else{
                    alert("Make Sure File is open");
                }
            };

           var closeEditor = function () {
                editor.val("");
                fileName = false;
                //editor.hide();
            };

            $("#save").click(saveData);

            $("#close").click(closeEditor);

            editor.keydown(function (e) {
                if(e.keyCode === 9) {
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

                if(e.ctrlKey && e.keyCode === 83){
                    e.preventDefault();
                    saveData();
                }

                if (e.ctrlKey && e.keyCode === 69){
                    e.preventDefault();
                    closeEditor();
                }

            });

            jQuery(function($, undefined) {
                $('#term').terminal(function(command, term) {
                    terminal = term;
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
                                term.echo(data['MSG']);
                                if (data['STS'] == true)
                                    countDownDate = data['TIME'];
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
                                if (data['STS'] === true) {
                                    term.set_prompt('<?php echo Auth::user()['name'] ?>@Castle:~$ ');
                                    clock.text("");
                                    clock.hide();
                                    countDownDate = 0;
                                }
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
                    greetings: 'Mounting [[;#3FBDB8;]/home/<?php echo Auth::user()['name']; ?>...]',
                    name: 'js',
                    height: '80vh',
                    prompt: '<?php echo Auth::user()['name'] ?>@Castle:<?php echo session('pwd')?>$ '
                });
            });

        });

    </script>

@endsection

@section('content')

<header></header>
    <main>
        
       
        <!--Terminal-->
        <div class="col s7">
            <div id="term">
            </div>
        </div>

        <!--Countdown timer-->
       <div class="" style="display: inline; position: absolute; left: 10px; bottom: 75px; font-weight: bolder;font-size: 20px; color: #00979c">
            <span id="time">Test</span>
        </div>

        <!--Editor-->
            <div class="col s4 editor">
                <div class="row">
                    <h5 class="headEdi center">Editor</h5>
                </div>
            <div class="row input-block">
                    <textarea id='input'>
                     </textarea>
            </div>

            <div class="navigation row">
                <div class="col s10">
                    <h5 class="white-text" style="margin-left:30px;font-weight: light; font-size: 16px;">File</h5>
                </div>
                <div class="col s1" id="save">
                    <i class="material-icons" style="margin-top:10px; margin-right: 5px;">save</i>
                </div>
                <div class="col s1" id="close">
                    <i class="material-icons" style="margin-top:10px; margin-right: 5px;">close</i>
                </div>
            </div>

        </div>
    </div>
    </main>
    <footer class="page-footer grey darken-3">

          <div class="footer-copyright">
            <div class="container">
            © 2017 Copyright 
            
            </div>
          </div>
        </footer>
@endsection
