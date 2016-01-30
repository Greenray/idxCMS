<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Administration: Filemanager - file editor template

/* TextareaDecorator.js && Parser.js
 * written by Colin Kuebler 2012
 * Part of LDT, dual licensed under GPLv3 and MIT
 * Builds and maintains a styled output layer under a textarea input layer
 * Adapted for idxCMS by Greenray
 */

die();?>
<script type="text/javascript">
function TextareaDecorator(textarea, parser) {
    /* INIT */
    var api = this;
    // Construct editor DOM
    var parent = document.createElement("div");
    var output = document.createElement("pre");
    parent.appendChild(output);
    var label  = document.createElement("label");
    parent.appendChild(label);
    // Replace the textarea with RTA DOM and reattach on label
    textarea.parentNode.replaceChild(parent, textarea);
    label.appendChild(textarea);
    // Transfer the CSS styles to our editor
    parent.className = 'ldt ' + textarea.className;
    textarea.className = '';
    // Coloring algorithm
    var color = function(input, output, parser) {
        var oldTokens = output.childNodes;
        var newTokens = parser.tokenize(input);
        var firstDiff, lastDiffNew, lastDiffOld;
        // Find the first difference
        for(firstDiff = 0; firstDiff < newTokens.length && firstDiff < oldTokens.length; firstDiff++)
            if (newTokens[firstDiff] !== oldTokens[firstDiff].textContent) break;
        // trim the length of output nodes to the size of the input
        while(newTokens.length < oldTokens.length)
            output.removeChild(oldTokens[firstDiff]);
        // Find the last difference
        for(lastDiffNew = newTokens.length-1, lastDiffOld = oldTokens.length-1; firstDiff < lastDiffOld; lastDiffNew--, lastDiffOld--)
            if (newTokens[lastDiffNew] !== oldTokens[lastDiffOld].textContent) break;
        // Update modified spans
        for( ; firstDiff <= lastDiffOld; firstDiff++) {
            oldTokens[firstDiff].className = parser.identify(newTokens[firstDiff]);
            oldTokens[firstDiff].textContent = oldTokens[firstDiff].innerText = newTokens[firstDiff];
        }
        // Add in modified spans
        for(var insertionPt = oldTokens[firstDiff] || null; firstDiff <= lastDiffNew; firstDiff++) {
            var span = document.createElement("span");
            span.className = parser.identify(newTokens[firstDiff]);
            span.textContent = span.innerText = newTokens[firstDiff];
            output.insertBefore(span, insertionPt);
        }
    };
    api.input  = textarea;
    api.output = output;
    api.update = function() {
        var input = textarea.value;
        if (input)
             color(input, output, parser);
        else output.innerHTML = '';                // Clear the display
    };
    // Detect all changes to the textarea, including keyboard input, cut/copy/paste, drag & drop, etc
    if (textarea.addEventListener) {
        textarea.addEventListener("input", api.update, false);  // Standards browsers: oninput event
    } else {
        // MSIE: detect changes to the 'value' property
        textarea.attachEvent("onpropertychange",
            function(e) {
                if (e.propertyName.toLowerCase() === 'value') {
                    api.update();
                }
            }
        );
    }
    api.update();   // Initial highlighting
    return api;
};

function Parser(rules, i) {
    /* INIT */
    var api = this;
    // Variables used internally
    var i = i ? 'i' : '';
    var parseRE = null;
    var ruleSrc = [];
    var ruleMap = {};
    api.add = function(rules) {
        for(var rule in rules) {
            var s = rules[rule].source;
            ruleSrc.push( s );
            ruleMap[rule] = new RegExp('^(' + s +')$', i);
        }
        parseRE = new RegExp( ruleSrc.join('|'), 'g' + i);
    };
    api.tokenize = function(input) { return input.match(parseRE); };
    api.identify = function(token) {
        for( var rule in ruleMap ) {
            if (ruleMap[rule].test(token)) {
                return rule;
            }
        }
    };
    api.add( rules );
    return api;
};
</script>
<script type="text/javascript">
    function $(e) { return document.getElementById(e); };   // get element shortcut
    // generic syntax parser
    var parser = new Parser({
        whitespace: /\s+/,
        comment:    /\/\*([^\*]|\*[^\/])*(\*\/?)?|(\/\/|#)[^\r\n]*/,
        string:     /"(\\.|[^"\r\n])*"?|'(\\.|[^'\r\n])*'?/,
        number:     /0x[\dA-Fa-f]+|-?(\d+\.?\d*|\.\d+)/,
        keyword:    /(and|as|case|catch|class|const|def|delete|die|do|else|elseif|esac|exit|extends|false|fi|finally|for|foreach|function|global|if|new|null|or|private|protected|public|published|resource|return|self|static|struct|switch|then|this|throw|true|try|var|void|while|xor)(?!\w|=)/,
        variable:   /[\$\%\@](\->|\w)+(?!\w)|\${\w*}?/,
        define:     /[$A-Z_a-z0-9]+/,
        op:         /[\+\-\*\/=<>!]=?|[\(\)\{\}\[\]\.\|]/,
        other:      /\S+/,
    });
    // wait for the page to finish loading before accessing the DOM
    window.onload = function() {
        var textarea = $('tad');    // get the textarea
        decorator = new TextareaDecorator(textarea, parser);    // start the decorator
    };
</script>
<fieldset>
    <table class="std">
        <tr><th>$name</th></tr>
        <tr>
            <td class="row1">
                <form name="edit" method="post" action="">
                    <textarea id="tad" class="tad" name="content">$content</textarea>
                </form>
            </td>
        </tr>
    </table>
    <div class="center">
        <p>
            <input type="submit" name="save" value="__Save__" />
            <input type="reset" value="__Reset__" />
            <input type="submit" value="__Back__" onclick="javascript:history.back();" />
        </p>
    </div>
</fieldset>
