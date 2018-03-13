(function($){
	$.attentionbar=function(options) {
	if(typeof options=="string"){
		options={"messages":[options]};
	};
	var settings=$.extend(true,{},defaults,options);
	bar.apply(settings);
};

$.fn.textWidth=function(){
	var html_org=$(this).html();
	var html_calc='<span>'+html_org+'</span>'
	$(this).html(html_calc);
	var width=$(this).find('span:first').width();
	$(this).html(html_org);
	return width;
};
defaults={
	"height":30,
	"collapsedButtonHeight":30,
	"positioning":"fixed",
	"backgroundColor":"IndianRed",
	"border":"solid 3px #FFF",
	"enableShadow":true,
	"buttonTheme":"triangle-arrow",
	"display":"expanded",
	"displayDelay":0,
	"speed":200,
	"easing":"swing",
	"messages":{
		"backgroundColor":[],
		"fontColor":"white",
		"aFontColor":"white",
		"text":[]
	},
	"messagesDelay":2000,
	"messagesFadeDelay":500,
	"messagesScrollSpeed":50,
	"messagesScrollDelay":2000,
	"messagesScrollDirection":"left",
	"enableCookie":false,
	"cookieHash":"",
	"cookieExpire":1,
	"positionClose":"right",
	"positionAnnouncement":"left",
	"messageClass":"",
	"fontFamily":"Verdana",
	"fontSize":"10pt",
	"fontColor":"White",
	"fontShadow":null,
	"aFontFamily":"Verdana",
	"aFontSize":"10pt",
	"aFontColor":"LightYellow",
	"aFontDecoration":"underline",
	"aFontShadow":null,
	"aHoverFontFamily":null,
	"aHoverFontSize":null,
	"aHoverFontColor":null,
	"aHoverFontDecoration":null,
	"aHoverFontShadow":null,
	"announcement":{
		"text":"IMPORTANT!:",
		"fontFamily":"Verdana",
		"fontSize":[],
		"fontColor":"White",
		"fontShadow":null,
		"icon":[]
	},
	"sideBar2":{
		"enabled":false,
		"googleAPIKey":"",
		"url":"http://my-domain.com/rss",
		"maxResults":5,
		"linkText":"Read More",
		"linkTarget":"_blank"
	}
};
bar={
	settings:{},
	wrapper:null,
	container:null,
	shadow:null,
	left:null,
	center:null,
	message:null,
	right:null,
	closeButtonContainer:null,
	closeButton:null,
	openButtonContainer:null,
	openButton:null,
	initialized:false,
	isOpen:false,
	htmlMarginTop:0,
	messageTimeoutId:null,
	messageIndex:0,
	initialize:function(){
		var $html=$('html');
		bar.htmlMarginTop=parseInt($html.css('margin-top'));
		bar.htmlMarginTop=isNaN(bar.htmlMarginTop)?0:bar.htmlMarginTop;
		if($('.attentionbar-wrapper').length==0){
			bar.wrapper=$('<div/>').addClass('attentionbar-wrapper');
			bar.container=$('<div/>').addClass('attentionbar-container');
			bar.shadow=$('<div/>').addClass('attentionbar-shadow');
			bar.left=$('<div/>').addClass('attentionbar-container-left');
			bar.center=$('<div/>').addClass('attentionbar-container-center');
			bar.message=$('<span/>').addClass('attentionbar-message');
			bar.right=$('<div/>').addClass('attentionbar-container-right');
			bar.closeButtonContainer=$('<div/>').addClass('attentionbar-close-button-container');
			bar.closeButton=$('<a/>').attr('href','#close-attentionbar').addClass('attentionbar-close-button').text(' ');
			bar.openButtonContainer=$('<div/>').addClass('attentionbar-open-button-container');
			bar.openButton=$('<a/>').attr('href','#open-attentionbar').addClass('attentionbar-open-button').text(' ');
			bar.addTo('body');
			bar.initialized=true;
		}else{
			bar.wrapper=$('.attentionbar-wrapper');
			bar.container=$('.attentionbar-container');
			bar.shadow=$('.attentionbar-shadow');
			bar.left=$('.attentionbar-container-left');
			bar.center=$('.attentionbar-container-center');
			bar.message=$('attentionbar-message');
			bar.right=$('.attentionbar-container-right');
			bar.closeButtonContainer=$('.attentionbar-close-button-container');
			bar.closeButton=$('.attentionbar-close-button').text(' ');
			bar.openButtonContainer=$('.attentionbar-open-button-container');
			bar.openButton=$('.attentionbar-open-button').text(' ');
			bar.initialized=true;
		};
	},
	setCookie:function(name,value,days){
	if(days){
		var date=new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires="; expires="+date.toGMTString();
	}else{
		var expires="";
	};
	document.cookie=name+"="+value+expires+"; path=/";
},
getCookie:function(name){
	var nameEQ=name+"=";
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while(c.charAt(0)==' ')c=c.substring(1,c.length);
		if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length);
	}
	return null;
},
deleteCookie:function(name){
	bar.setCookie(name,"",-1);
},
addTo:function(selector){
	$(selector).prepend(bar.wrapper);
	bar.wrapper.append(bar.container).append(bar.shadow).append(bar.openButtonContainer);
	bar.container.append(bar.closeButtonContainer).append(bar.left).append(bar.center.append(bar.message)).append(bar.right);
	bar.closeButtonContainer.append(bar.closeButton);
	bar.openButtonContainer.append(bar.openButton);
},
isNotNullOrEmpty:function(strVal){
	return(typeof strVal=="string"&&strVal!=null&&strVal!="");
},
messageCycle:function(){
	bar.clearMessageTimeout();
	if(bar.settings.messages.text.length==1){
		bar.message.html(bar.settings.messages.text[0]);
		//var $announcement = bar.left.find('ul');
        	//var $text = $announcement.find('li');
	        //$text.css('color',bar.settings.messages.fontColor[0]);
        	//$text.css('font-size',bar.settings.announcement.fontSize[0]);

		//bar.container.add(bar.openButton).css({'background-color':bar.settings.messages.backgroundColor[0]});
		//bar.message.css('color',bar.settings.messages.fontColor[0]);
		if(!bar.isNotNullOrEmpty(bar.settings.messageClass)){
			var $a=bar.message.find('a');
			bar.styleLinks($a,0);
			bar.styleLinksHover($a,0);
		};
	var mWidth=bar.message.textWidth();
	var cWidth=bar.center.width();
	bar.message.css({'opacity':100});
	if(mWidth<=cWidth){
		bar.message.css({'left':0,'width':'auto'});
		bar.messageTimeoutId=setTimeout(bar.messageCycle,bar.settings.messagesDelay);
	}else if(bar.settings.messages.text.length==1&&mWidth>cWidth){
		var diff=mWidth-cWidth;
		var speed=Math.round(diff/bar.settings.messagesScrollSpeed)*1000;
		bar.message.css({'width':mWidth}).animate({'left':0},500);
		bar.message.delay(bar.settings.messagesScrollDelay).animate({'left':'-'+diff
	},
	speed,'linear',
	function(){
		bar.messageTimeoutId=setTimeout(bar.messageCycle,bar.settings.messagesDelay);
	});
	};
	}else if(bar.settings.messages.text.length>1){
		bar.message.animate({'opacity':0},
		bar.settings.messagesFadeDelay/2,
function(){
	bar.message.css({'left':0,'width':'auto'});
	bar.message.html(bar.settings.messages.text[bar.messageIndex]);
	bar.container.add(bar.openButton).css({'background-color':bar.settings.messages.backgroundColor[bar.messageIndex]});
	bar.message.css('color',bar.settings.messages.fontColor[bar.messageIndex]);
	var $announcement = bar.left.find('ul');
	var $text = $announcement.find('li.'+bar.settings.announcementClass);
	var $announcementicon = $announcement.find('a.announcementicon');
	//alert($announcementicon);
	if(icon.image[bar.messageIndex] != '') {
		$announcementicon.attr('title',icon.name[bar.messageIndex]).attr('target',icon.target).css({'background':"url('"+icon.image[bar.messageIndex]+"') no-repeat center center",'height':bar.settings.height});
	} else {
		$announcementicon.attr('title',icon.name[bar.messageIndex]).attr('target',icon.target).css('background','');
	}
	$text.animate({fontSize: bar.settings.announcement.fontSize[bar.messageIndex]}, 500 ).css('color',bar.settings.messages.fontColor[bar.messageIndex]).text(bar.settings.announcement.text[bar.messageIndex]);
	if(!bar.isNotNullOrEmpty(bar.settings.messageClass)){
		var $a=bar.message.find('a');
		bar.styleLinks($a,bar.messageIndex);
		bar.styleLinksHover($a);
	};
(bar.messageIndex>=(bar.settings.messages.text.length-1))?bar.messageIndex=0:bar.messageIndex++;
bar.message.animate({'opacity':100},
bar.settings.messagesFadeDelay/2,function(){
	var mWidth=bar.message.textWidth();
	var cWidth=bar.center.width();
	var diff=mWidth-cWidth;
	if(mWidth>cWidth){
		var speed=Math.round(diff/bar.settings.messagesScrollSpeed)*1000;
		bar.message.css({'width':mWidth}).delay(bar.settings.messagesScrollDelay).animate({'left':'-'+diff},speed,'linear',
		function(){
			bar.messageTimeoutId=setTimeout(bar.messageCycle,bar.settings.messagesDelay);
		});
		}else{
			bar.messageTimeoutId=setTimeout(bar.messageCycle,bar.settings.messagesDelay);
		};
	});
});
}else{
	bar.message.empty();
	bar.messageTimeoutId=setTimeout(bar.messageCycle,bar.settings.messagesDelay);
};
},styleLinks:function(links,messageindex){
	if(bar.isNotNullOrEmpty(bar.settings.aFontFamily)){
		links.css('font-family',bar.settings.aFontFamily);
	};
if(bar.isNotNullOrEmpty(bar.settings.aFontSize)){
	links.css('font-size',bar.settings.aFontSize);
};
if(bar.isNotNullOrEmpty(bar.settings.messages.aFontColor[messageindex])){
	links.css('color',bar.settings.messages.aFontColor[messageindex]);
};
if(bar.isNotNullOrEmpty(bar.settings.aFontDecoration)){
	links.css('text-decoration',bar.settings.aFontDecoration);
};
if(bar.isNotNullOrEmpty(bar.settings.aFontShadow)){
	links.css('text-shadow',bar.settings.aFontShadow);
};
},styleLinksHover:function(links){
	links.unbind('mouseenter.attentionbar').bind({'mouseenter.attentionbar':function(){
	if(bar.isNotNullOrEmpty(bar.settings.aHoverFontFamily)){
		links.css('font-family',bar.settings.aHoverFontFamily);
	};
if(bar.isNotNullOrEmpty(bar.settings.aHoverFontSize)){
	links.css('font-size',bar.settings.aHoverFontSize);
};
if(bar.isNotNullOrEmpty(bar.settings.aHoverFontColor)){
	links.css('color',bar.settings.aHoverFontColor);
};
if(bar.isNotNullOrEmpty(bar.settings.aHoverFontDecoration)){
	links.css('text-decoration',bar.settings.aHoverFontDecoration);
};
if(bar.isNotNullOrEmpty(bar.settings.aHoverFontShadow)){
	links.css('text-shadow',bar.settings.aHoverFontShadow);
};
},'mouseleave.attentionbar':function(){
	bar.styleLinks(links);}});},clearMessageTimeout:function(){
	if(typeof bar.messageTimeoutId!='undefined'&&bar.messageTimeoutId!=null){
		clearTimeout(bar.messageTimeoutId);
	};
bar.messageTimeoutId=null;},setMessageTimeout:function(){
	bar.clearMessageTimeout();
	if(typeof bar.messageTimeoutId=='undefined'||bar.messageTimeoutId==null){
		bar.messageTimeoutId=setTimeout(bar.messageCycle,bar.settings.messagesDelay);
	};
},
isGoogleLoaded:function(){
	var googleAPI=false;$('head > script[type="text/javascript"]').each(function(){
		var src=$(this).attr('src');
		var check="http://www.google.com/jsapi?key=";
		if(bar.isNotNullOrEmpty(src)&&src.length>=check.length&&src.substr(0,check.length)===check){
		googleAPI=true;
		};
	});
	return(googleAPI&&!(typeof google=='undefined'));},
	isFeedsLoaded:function(){
		return(bar.isGoogleLoaded()&&!(typeof google.feeds=='undefined'));
	},
	loadGoogle:function(){
		var script=document.createElement("script");
		script.src="http://www.google.com/jsapi?key="+bar.settings.sideBar2.googleAPIKey+"&callback=jQuery.attentionbarGoogleCallback";
		script.type="text/javascript";
		document.getElementsByTagName("head")[0].appendChild(script);
		},
		loadFeeds:function(){
			google.load("feeds","1",{'callback':bar.loadRss});
		},
		loadRss:function(){
			var feed=new google.feeds.Feed(bar.settings.sideBar2.url);
			feed.setNumEntries(bar.settings.sideBar2.maxResults);
			feed.load(function(result){
				bar.clearMessageTimeout();
				if(!result.error){
					for(var i=0;i<result.feed.entries.length;i++){
						var msg=result.feed.entries[i].title;
						if(bar.isNotNullOrEmpty(bar.settings.sideBar2.linkText)){
							msg+=' <a href="'+result.feed.entries[i].link+'" target="'+bar.settings.sideBar2.linkTarget+'">'+bar.settings.sideBar2.linkText+'</a>';
						};
						bar.settings.messages.push(msg);
						}
					};
					bar.message.stop().css({'left':0,'opacity':100});
					bar.messageCycle();
				});
			},
expand:function(e){
	if(typeof e!='undefined'&&e!=null&&typeof e.preventDefault=='function'){
		e.preventDefault();
	}
if(!bar.isOpen){
	bar.openButton.animate({'height':0},bar.settings.speed);
	bar.openButtonContainer.animate({'height':0},bar.settings.speed,
	function(){
		bar.container.animate({'height':bar.settings.height},
		bar.settings.speed,bar.settings.easing).css({'border-bottom':bar.settings.border});
		bar.wrapper.animate({'height':bar.settings.height+5},
		bar.settings.speed,bar.settings.easing);
		if(bar.settings.positioning=='fixed'){
			$('html').animate({'margin-top':'+='+(bar.settings.height+5)});
			}else{
			$('html').css({'margin-top':bar.htmlMarginTop+'px'});
		};
		bar.wrapper.focus();
	});
	bar.isOpen=true;
	if(bar.settings.enableCookie){
		bar.setCookie('attentionbar-state'+bar.settings.cookieHash,true,bar.settings.cookieExpire);
	}
};
},
collapse:function(e){
	if(typeof e!='undefined'&&e!=null&&typeof e.preventDefault=='function'){
		e.preventDefault();
	}
if(bar.isOpen){
	bar.container.animate({'height':0},bar.settings.speed).css({'border':'none'});
	if(bar.settings.positioning=='fixed'){
		$('html').animate({'margin-top':'-='+(bar.settings.height+5)});
	}else{
		$('html').css({'margin-top':bar.htmlMarginTop+'px'});
	};
bar.wrapper.animate({'height':5},
bar.settings.speed,
function(){
	bar.openButtonContainer.animate({'height':bar.settings.collapsedButtonHeight+11},
	bar.settings.speed,
	bar.settings.easing);
	bar.openButton.animate({'height':bar.settings.collapsedButtonHeight},
	bar.settings.speed,bar.settings.easing);});
	bar.isOpen=false;
	if(bar.settings.enableCookie){
		bar.setCookie('attentionbar-state'+bar.settings.cookieHash,false,bar.settings.cookieExpire);
	}
};
},
setExpanded:function(){
	bar.container.height(bar.settings.height).css({'border-bottom':bar.settings.border});
	bar.wrapper.height(bar.settings.height+5);
	bar.openButtonContainer.add(bar.openButton).height(0);
	if(bar.settings.positioning=='fixed'){
		$('html').css({'margin-top':(bar.htmlMarginTop<=0)?(bar.settings.height+5)+'px':(bar.htmlMarginTop+(bar.settings.height+5))+'px'});
		}else{
		$('html').css({'margin-top':bar.htmlMarginTop+'px'});
	};
bar.isOpen=true;
if(bar.settings.enableCookie){
	bar.setCookie('attentionbar-state'+bar.settings.cookieHash,true,bar.settings.cookieExpire);
}
},
setCollapsed:function(){
	bar.container.height(0).css({'border-bottom':0});
	bar.wrapper.height(5);
	bar.openButtonContainer.height(bar.settings.collapsedButtonHeight+11);
	bar.openButton.height(bar.settings.collapsedButtonHeight);
	if(bar.settings.positioning=='fixed'){
		$('html').css({'margin-top':(bar.htmlMarginTop<=0)?0+'px':(bar.htmlMarginTop-(bar.settings.height+5))+'px'});
		}else{
		$('html').css({'margin-top':bar.htmlMarginTop+'px'});
	};
bar.isOpen=false;
if(bar.settings.enableCookie){
	bar.setCookie('attentionbar-state'+bar.settings.cookieHash,false,bar.settings.cookieExpire);
}},
apply:function(settings){
	wasinit=true;
	bar.settings=settings;
	if(!bar.initialized){
		bar.initialize();
		wasinit=false;
	}
if(settings.messagesScrollDirection!='left'&&settings.messagesScrollDirection!='right'){
	settings.messagesScrollDirection=defaults.messagesScrollDirection;
};
bar.wrapper.height(bar.settings.height+5);
bar.openButtonContainer.height(bar.settings.collapsedButtonHeight+11);
bar.openButton.height(bar.settings.collapsedButtonHeight);
bar.container.add(bar.right).add(bar.left).add(bar.center).add(bar.message).add(bar.closeButtonContainer).add(bar.closeButton).height(bar.settings.height);
bar.right.add(bar.left).add(bar.center).add(bar.message).css({'line-height':bar.settings.height+'px'});
bar.container.add(bar.openButton).css({'background-color':bar.settings.backgroundColor});
bar.container.css({'border-bottom':bar.settings.border});
bar.openButton.css({'border':bar.settings.border,'border-top':'none'});
bar.openButtonContainer.css({'top':bar.htmlMarginTop+'px'});
bar.openButton.add(bar.closeButton).removeClass();
bar.openButton.addClass('attentionbar-open-button');
bar.closeButton.addClass('attentionbar-close-button');
bar.openButton.add(bar.closeButton).addClass(settings.buttonTheme);
if(bar.settings.enableShadow){
	bar.shadow.show();bar.openButtonContainer.addClass('shadow');
}else{
	bar.shadow.hide();bar.openButtonContainer.removeClass('shadow');
};
if(bar.isNotNullOrEmpty(bar.settings.messageClass)){
	bar.message.addClass(bar.settings.messageClass);
	}else{
	if(bar.isNotNullOrEmpty(bar.settings.fontFamily)){
		bar.message.css('font-family',bar.settings.fontFamily);
	};
if(bar.isNotNullOrEmpty(bar.settings.fontSize)){
	bar.message.css('font-size',bar.settings.fontSize);
};
if(bar.isNotNullOrEmpty(bar.settings.fontColor)){
	bar.message.css('color',bar.settings.fontColor);
};
if(bar.isNotNullOrEmpty(bar.settings.fontShadow)){
	bar.message.css('text-shadow',bar.settings.fontShadow);
};
};
if(bar.settings.sideBar2.enabled&&bar.isNotNullOrEmpty(bar.settings.sideBar2.googleAPIKey)&&bar.isNotNullOrEmpty(bar.settings.sideBar2.url)){
	bar.messageIndex=0;
	if(bar.isGoogleLoaded()&&bar.isFeedsLoaded()){
		bar.loadRss();
	}else if(bar.isFeedsLoaded()){
		bar.loadFeeds();
	}else{
		bar.loadGoogle();
	};
}else{
	bar.center.hover(bar.clearMessageTimeout,bar.setMessageTimeout);
	bar.message.stop(true,false).css({'left':0,'opacity':100});
	bar.messageCycle();
};
if(bar.settings.positionAnnouncement=='left'||bar.settings.positionAnnouncement=='right'){
	if(bar.settings.announcement.icon.length>0){
		var $announcement=$('.attentionbar-announcement').length>0?$('.attentionbar-announcement'):$('<ul/>').addClass('attentionbar-announcement');
		$announcement.empty();
		var $text=$('<li/>').text(bar.settings.announcement.text[0]).css({'height':bar.settings.height,'line-height':bar.settings.height+'px'});
		$text.css('color',bar.settings.messages.fontColor[0]);
		$text.css('fontSize',bar.settings.announcement.fontSize[bar.messageIndex]);
		if(bar.isNotNullOrEmpty(bar.settings.announcementClass)){
			$text.addClass(bar.settings.announcementClass);
			}else{
			$text.css({'padding-right':'10px','padding-left':'10px'});
			if(bar.isNotNullOrEmpty(bar.settings.announcement.fontFamily)){
				$text.css('font-family',bar.settings.announcement.fontFamily);
			};
			if(bar.isNotNullOrEmpty(bar.settings.announcement.fontSize)){
			$text.css('font-size',bar.settings.announcement.fontSize);
			};
			if(bar.isNotNullOrEmpty(bar.settings.announcement.fontColor)){
				$text.css('color',bar.settings.announcement.fontColor);
			};
			if(bar.isNotNullOrEmpty(bar.settings.announcement.fontShadow)){
				$text.css('text-shadow',bar.settings.announcement.fontShadow);
			};
		};
		
$announcement.append($text);
pdef={'name':null,'url':null,'image':null,'target':'_blank'};
$.each(bar.settings.announcement.icon,function(i,p){
	icon=$.extend({},pdef,p);
	if(icon.name!=null){
		if(icon.image[0]!=""){
			var $a=$('<a/>').addClass('announcementicon').attr('href',icon.url).attr('title',icon.name[0]).attr('target',icon.target).css({'background':"url('"+icon.image[0]+"') no-repeat center center",'height':bar.settings.height});
		} else {
			var $a=$('<a/>').addClass('announcementicon');
		};
			$announcement.prepend($('<li/>').css({'height':bar.settings.height}).append($a));
	};
});
if(bar.settings.positionAnnouncement=='right'){
	$announcement.css('float','right');
	bar.right.append($announcement);
	}else if(bar.settings.positionAnnouncement=='left'){
		$announcement.css('float','left');
		bar.left.append($announcement);
	};
}else{$('.attentionbar-announcement').remove();};
}else{$('.attentionbar-announcement').remove();};
if(bar.settings.positioning=='fixed'){
	bar.wrapper.css({'position':'fixed','top':bar.htmlMarginTop+'px','left':'0px'});
	bar.openButtonContainer.css({'position':'fixed'});
}else{
	bar.wrapper.css({'position':'relative','top':0,'left':0});
	bar.openButtonContainer.css({'position':'absolute'});
};
if(bar.settings.positionClose=='right'){
	bar.left.css('width','25%');
	bar.right.css('width','20%');
	bar.closeButtonContainer.css('float','right');
	bar.openButtonContainer.css({'left':'auto','right':'0px'});
	}else if(bar.settings.positionClose=='left'){
		bar.left.css('width','20%');
		bar.right.css('width','25%');
		bar.closeButtonContainer.css('float','left');
		bar.openButtonContainer.css({'right':'auto','left':'0px'});
	}else{
		bar.left.css('width','25%');
		bar.right.css('width','25%');
	};
var cookie=bar.getCookie('attentionbar-state'+bar.settings.cookieHash);
if(cookie==null||!bar.settings.enableCookie){
	if(!bar.settings.enableCookie){
		bar.deleteCookie('attentionbar-state'+bar.settings.cookieHash);
	}
switch(bar.settings.display){
	case'onscroll':
		bar.setCollapsed();
		$(window).one('scroll',function(){
		setTimeout(bar.expand,bar.settings.displayDelay);});
		break;
	case'delayed':
		bar.setCollapsed();
		setTimeout(bar.expand,bar.settings.displayDelay);
		break;
	case'collapsed':
		bar.setCollapsed();
		break;
	case'expanded':default:
		bar.setExpanded();
		break;
	};
}else{
	cookie=="true"?bar.setExpanded():bar.setCollapsed();
};
bar.openButton.unbind().click(bar.expand);
bar.closeButton.unbind().click(bar.collapse);}
};
})(jQuery);
