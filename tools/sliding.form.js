$(function() {
	/* Number of fieldsets */
	var fieldsetCount = $('#registration').children().length;
	/* Current position of fieldset / navigation link */
	var current = 1;
	/* Sum and save the widths of each one of the fieldsets
	   Set the final sum as the total width of the steps element */
	var stepsWidth = 0;
	var widths = new Array();
	$('#steps .step').each(function(i){
        var $step = $(this);
        widths[i] = stepsWidth;
        stepsWidth += $step.width();
    });
	$('#steps').width(stepsWidth);
	/* To avoid problems in IE, focus the first input of the form */
	$('#registration').children(':first').find(':input:first').focus();
	/* Show the navigation bar */
	$('#navigation').show();
	/* When clicking on a navigation link
	   the form slides to the corresponding fieldset */
	$('#navigation a').bind('click',function(e){
        var $this = $(this);
	    $this.closest('ul').find('li').removeClass('selected');
        $this.parent().addClass('selected');
	    /* We store the position of the link in the current variable */
	    current = $this.parent().index() + 1;
	    /* Animate / slide to the next or to the corresponding fieldset.
	       The order of the links in the navigation is the order of the fieldsets.
	       Also, after sliding, we trigger the focus on the first input element of the new fieldset
	       If we clicked on the last link (confirmation), then we validate
	       all the fieldsets, otherwise we validate the previous one before the form slided */
	    $('#steps').stop().animate({
	        marginLeft: '-' + widths[current-1] + 'px'
	    },500);
          e.preventDefault();
        });
	    /* Clicking on the tab (on the last input of each fieldset), makes the form slide to the next step */
	    $('#registration > fieldset').each(function(){
	        var $fieldset = $(this);
	        $fieldset.children(':last').find(':input').keydown(function(e){
	        if (e.which === 9){
	           $('#navigation li:nth-child(' + (parseInt(current)+1) + ') a').click();
	           /* Force the blur for validation */
	           $(this).blur();
  	           e.preventDefault();
   	        }
	    });
	});
});
