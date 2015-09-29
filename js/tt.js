String.prototype.strip = function(char) {
    return this.replace(new RegExp("^" + char + "*"), '').
        replace(new RegExp(char + "*$"), '');
}


$.extend_if_has = function(desc, source, array) {
    for (var i=array.length;i--;) {
        if (typeof source[array[i]] != 'undefined') {
            desc[array[i]] = source[array[i]];
        }
    }
    return desc;
};


(function($) {
    $.fn.tilda = function(eval, options) {
        if ($('body').data('tilda')) {
            return $('body').data('tilda').terminal;
        }
        this.addClass('tilda');
        options = options || {};
        eval = eval || function(command, term) {
            term.echo("you don't set eval for tilda");
        };

        var settings = {
            prompt: 'user@skynet:~$ ',
            name: 'user',
            height: 250,
            enabled: false,
            greetings: '$(SKYNET.hook).login(initialize): Type "help" for a list of commands',
            keypress: function(e) {
                if (e.which == 96) {
                    return false;
                }
            }
        };
        if (options) {
            $.extend(settings, options);
        }
        this.append('<div class="td"></div>');
        var self = this;
        self.terminal = this.find('.td').terminal(eval, settings);
        var focus = false;
        $(document.documentElement).keypress(function(e) {
            if (e.which == 96) {
                self.slideToggle('fast');
                self.terminal.focus(focus = !focus);
                self.terminal.attr({
                    scrollTop: self.terminal.attr("scrollHeight")
                });
            }
        });
        $('body').data('tilda', this);
        this.hide();
        return self;
    };
})(jQuery);



//----------STRING trim function
if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function()
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}
//--------------------------------------------------------------------------
jQuery(document).ready(function($) {

    $('#tilda').tilda(function(command, terminal) {
        if(command.trim().substring(0,3).toUpperCase() == 'SQL'){
            terminal.echo('No SQL for you');
        }
        else if(command == 'whoami'){
            terminal.echo('You are the latest zombie in my botnet... jk');
        }
        else if(command == 'help'){
            terminal.echo('Still working on it. Try again later');
        }
        else{
            terminal.echo('-sh: '+ command + ': command not found')
        }
    });
});
