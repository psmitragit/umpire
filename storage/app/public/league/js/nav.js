$(document).ready(function(){	
	onloadmethod();	
	
	$('#menuBtn').click(function(e){
		e.stopPropagation();		
		$('.header-nav').toggleClass('open');
		$('#swipeNav').toggleClass('open');
		$('#swipe_overlay').toggleClass('in');
		$('body').toggleClass('overf');
	});
	$('#closeBtn').click(function(){
		$('.header-nav').removeClass('open');
		$('#swipeNav').removeClass('open');
		$('#swipe_overlay').removeClass('in');
		$('body').removeClass('overf');
	});
	$(document).mouseup(function (e)	
		{			
		if($('#swipeNav').length>0){
			if(!$('#swipeNav, #menuBtn, #menuBtn span, #pull_nav, .pull_nav_close, .top_nav .arrow').is(e.target)){
				if($(window).width()){
					$('.header-nav').removeClass('open');
					$('#swipeNav').removeClass('open');
					$('#swipe_overlay').removeClass('in');
					$('body').removeClass('overf');
				}else{
					$('#swipeNav').fadeIn();
				}
			}
		}
	});
		
	$(".top_nav ul").parent().addClass("has-dropdown");
	$(".top_nav li.has-dropdown").append("<span class='arrow'></span>");
	$(".top_nav li.has-dropdown .arrow").click(function(){		
		if ($(this).parent().hasClass('open')) {
			$(this).parent().removeClass("open")
		}else{
			$(this).parent().addClass("open")
		}
	});
	
});


$(window).resize(function(){	
	onloadmethod();	  
});

function onloadmethod(){	
}



