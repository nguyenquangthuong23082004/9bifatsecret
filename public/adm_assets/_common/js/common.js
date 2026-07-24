// imgOn
function imgOn(item){
	var image = item.find("img");
	var imgsrc = $(image).attr("src");
	if ($(image).length){
		var on = imgsrc.replace(/_off/,"_on");
		$(image).attr("src",on);
	}
}

// imgOff
function imgOff(item){
	var image = item.find("img");
	for(var i=0;i<image.length;i++){
		var imgsrc = $(image[i]).attr("src");
		var off = imgsrc.replace(/_on/,"_off");
		$(image[i]).attr("src",off);
	}
}

jQuery(function(){


	// normal img on off
	$("img").hover(
		function(){this.src = this.src.replace("_off","_on");},
		function(){this.src = this.src.replace("_on","_off");}
	);


	
	/* gnb */
	var menuover=false; //current menu
	var clearenter;
	
	var gnb = $('#gnb')
	if (!gnb.length) gnb = $('.gnb')
	var gnb_link_depth1=$(gnb).find(">ol>li"); // 1depth
	if (!gnb_link_depth1.length) gnb_link_depth1=$(gnb).find(">ul>li"); // 1depth
	var gnb_link_depth2=$(gnb_link_depth1).find('li');  // 2depth
	var d_time = 0; 
	var current_menu;
	for (var i = 0; i < gnb_link_depth1.length ; i++ )
	{
		if ($(gnb_link_depth1[i]).hasClass('on'))
		{
			current_menu = i
		}
	}
	gnb_link_depth1.each(function(){
		//add mouseOver
		$(this).find('>a').on('mouseenter keyup' , function(){
			imgOff(gnb_link_depth1);
			clearTimeout(clearenter);
			imgOn($(this));
			$(this).parent().addClass("on").siblings().removeClass("on");
			menuover=true;
		});
		//add mouseOut
		$(this).on('mouseleave blur' , function(){
			menuover=false;
			clearenter = setTimeout(menuclear,d_time);
		});
	});
	function menuclear(){
		if(!menuover){
			gnb_link_depth1.removeClass("on");
			imgOff(gnb_link_depth1);
			if (current_menu !== undefined) {
				imgOn($(gnb_link_depth1[current_menu]).find('>a'));
				$(gnb_link_depth1[current_menu]).addClass('on');
			}
		}
	}
	gnb_link_depth2.each(function(){
		//add mouseOver
		$(this).on('mouseover keyup' , function(){
			clearTimeout(clearenter);
			imgOn($(this).parents('li'));
			imgOn($(this));
			$(this).addClass("on").siblings().removeClass("on");
			menuover=true;
		});
		//add mouseOut
		$(this).on('mouseout blur' , function(){
			imgOff($(this));
			menuover=false;
			clearenter = setTimeout(menuclear,d_time);
		});
	});
	
	//var nowpage = $("#gnb a")
	//$(nowpage[current_menu]).trigger("mouseover"); // current menu active page add




	

	// tabMenu
	function tabMenu(eleMenuParent,eleMenuChild,eleContent){
		var menus = $("." + eleMenuParent + " " + eleMenuChild);
		var contents = $("." + eleContent);
		contents.css({"display":"none"});
		contents.eq(0).css({"display":"block"});
		menus.click(function(e) {
			e.preventDefault();
			menus.removeClass("on");
			$(this).addClass("on");
			var num = $(this).index();
			contents.css({"display":"none"});
			contents.eq(num).fadeIn('1000');
		});
	};
	
//	tabMenu("tabs","li","tabContents");



	// placeholder
	$(".placeHolder").each(function() {
		var textElement = $(this);
		var displayText = textElement.attr("rel"); 
		 
		if ( textElement.val() === "" ) {
			textElement.val(displayText).css("color", "#9b9b9b"); 
		}
		 
		textElement.bind("focus.placeHolder", function() {  
			if ( textElement.val() === displayText ) {
				textElement.val("").css("color", "#9b9b9b");  
			}
		});
		 
		textElement.bind("blur.placeHolder", function() { 
			if ( textElement.val() === "" ) {
				textElement.val(displayText).css("color", "#9b9b9b"); 
			}
		});
	});


	//scroll top
	var scrollTop = jQuery('.scrollTop');    
	scrollTop.click(function(e){
	e.preventDefault();
	jQuery('html, body').stop().animate({scrollTop:0});
	});

}); //ready