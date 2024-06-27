var loop = null;
var runing = false;

$(document).ready(function(){
	var currentPosition = 0;
	var sitmHeight = $(".imgin .itm:eq(0)").outerHeight();
	var sitms = $('.imgin .itm');
	var numberOfsitms = sitms.length;
	var dir = 1;

	$('.images #next, .images #prev')
		.bind('click', function(){ 

		window.clearInterval(loop);
 		if(!runing){
 			runing = true;
 			dir = ($(this).attr('id')=='next') ? 1 : 0;
		
 			reBuild(dir);
		
			// Move.sinner using margin-left
			$('.imgin').animate({
			//'marginLeft' : sitmHeight*(-currentPosition)
				'marginTop' : sitmHeight*(-dir)
			}, 400, 'swing', function(){
				manageControls(dir);
				runing = false;
			});
			
		}
		return false;
	});



 function reBuild(dir){
 if(dir == 0){ 

			first = $('.imgin .itm').last().clone();
			first.prependTo('.imgin');
			$('.imgin').css({'margin-top': -sitmHeight});

	} else{

			first = $('.imgin .itm').eq(0).clone();
			first.appendTo('.imgin');

	  }
	}


	function manageControls(dir){

		// Hide left arrow if position is first sitm
 		if(dir == 0){ 

			$('.imgin .itm').last().remove();
			$('.imgin').css({'margin-top': 0});

		} else{

			$('.imgin .itm').eq(0).remove();
			$('.imgin').css({'margin-top': 0});

		  }

		} 
	});
