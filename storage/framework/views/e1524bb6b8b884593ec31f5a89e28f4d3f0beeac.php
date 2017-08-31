<?php
use Illuminate\Support\Facades\Auth;
use App\Models;
use Illuminate\Support\Facades\Session;
?>


<?php $__env->startSection('css'); ?>
    <link href="css/jquery.terminal-1.5.3.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('topscript'); ?>
    <script src="js/jquery.terminal-1.5.3.js"></script>
    <script>
        var countDownDate = <?php
            $user = Models\user::find(Auth::id())->first();
            $time = Models\level::find($user->level_id)->first()->time;
            $startTime = $user->updated_at;
            if ($user->status == 'PLAYING')
                echo $time + strtotime($startTime);
            else
                echo 0;
        ?>;

        if (countDownDate != 0) {
                // Update the count down every 1 second
                var x = setInterval(function() {
                    // Get todays date and time
                    var now = Math.round(new Date().getTime()/1000);

                    // Find the distance between now an the count down date
                    var distance = countDownDate - now;

                    // Time calculations for hours, minutes and seconds
                    var hours = Math.floor(distance / (60 * 60));
                    var minutes = Math.floor((distance % (60 * 60)) / (60));
                    var seconds = Math.floor(distance % 60);

                    // Display the result
                    document.getElementById("timer").innerHTML = hours + " : " + minutes + " : " + seconds + " : ";

                    // countdown fininshed.
                    if (distance < 0) {
                        clearInterval(x);
                        <?php
                            //setting status field to timeout and removing the question directory
                            $user = Models\user::find(Auth::id())->first();
                            $question_name=Models\level::find($user->level_id)->first()->name;
                            $user->status = 'TIMEOUT';
                            $user->save();
                            $full_path = storage_path()."/app/public/";
                            shell_exec("rm -r ".$full_path."users/".Auth::id()."/".$question_name);
                            Session::put('pwd', '~');
                        ?>
                    }
                }, 1000);
        }
    </script>
    <script>
        var editor;
        var fileName = false;

        $(document).ready(function () {

            editor = $("#input");

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

<?php $__env->stopSection(); ?>>

<?php $__env->startSection('content'); ?>
    <!--Terminal-->
    <div class="container">

        <div class="row">
            <div id="term">
            </div>
        </div>

        <!--Countdown timer-->
        <div id = "timer"></div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>