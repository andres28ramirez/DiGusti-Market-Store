
$(document).ready(function(){

	$bootSlider = {
		interval : 4000,
		animate : "opacity" // opacity || flipping
	};

	_sliderInit();

});

function _sliderInit() {

	$slideInterval = window.$bootSlider != undefined &&
					"interval" in $bootSlider &&
					$bootSlider.interval !== "" ? $bootSlider.interval : 4000;

	$slideAnimate = window.$bootSlider != undefined &&
					"animate" in $bootSlider &&
					$bootSlider.animate != "" ? $bootSlider.animate : "flipping";

	$slideFullItems = 0;
	$lastIndex = null;

	if ($("#slider_full_items > div").length) {
		$slideFullItems = $("#slider_full_items > div");
	}
	else {
		$("#slider_full_items").append( $(".slider_full_item") );
		$slideFullItems = $(".slider_full_item");
	}

	_slideChange(0);

	$("#slider_items > div").click(function(){
		var $index = $(this).index();
		_slideChange($index);
	});

	/* Назначение кнопок | The buttons */
	$("#slider_next").click(function(){
		_slideTo("next");
	});

	$("#slider_prev").click(function(){
		_slideTo("prev");
	});

	// Пауза при наведении мыши | Hover pause
	$("#slider_full").hover(function(){
		$slideProgress.pause();
		
	}, function(){
		$slideProgress.resume();
	});
}

var $slideProgress = {
	progressBar : $("#slider_loading"),
	isPause : false,

	start : function ($slideInterval) {
		if ($slideInterval == 0) {
			return;
		}

		if (this.isPause == false) {
			var self = this;
			this.startAnimation();
			this.interval = setInterval(function() { self.intervalFunction(); }, $slideInterval);
		}
		else {
			this.stop();
		}
	},

	stop : function() {
		clearInterval(this.interval);
		delete this.interval;
		this.stopAnimation();
	},

	pause : function() {
		clearInterval(this.interval);
		delete this.interval;
		this.pauseAnimation();
		this.isPause = true;
	},

	resume : function() {
		if ($slideInterval == 0) {
			return;
		}

		if (this.isPause == true) {
			var $residue = $slideInterval - (this.progressBar.width() / $("#slider_full_items").outerWidth() * $slideInterval);
			var self = this;
			
			this.resumeAnimation($residue);
			this.interval = setInterval(function() { self.intervalFunction(); }, $residue);
			this.isPause = false;
		}
	},

	intervalFunction : function() {
		_slideTo("next");
	},

	startAnimation : function() {
		this.progressBar = $("#slider_loading");

		if (this.progressBar.length) {
			this.progressBar.stop().css({"width" : "0px"});
			this.progressBar.animate({"width" : "100%"}, $slideInterval, "linear", function(){ $(this).css({"width" : "0px"}); });
		}
	},
	stopAnimation : function() {
		if (this.progressBar.length) {
			this.progressBar.stop().css({"width" : "0px"});;
		}
	},
	pauseAnimation : function() {
		if (this.progressBar.length) {
			this.progressBar.stop();
		}
	},

	resumeAnimation : function($residue) {
		if (this.progressBar.length) {
			this.progressBar.animate({"width" : "100%"}, $residue, "linear", function(){ $(this).css({"width" : "0px"}); });
		}
	}

}

function _slideChange($index) {
	// Не продолжаем функцию, если элемент тот же самый.
	if ($lastIndex == $index) {
		return;
	}

	$lastIndex = $index;
	$slideProgress.stop();
	$slideProgress.start($slideInterval);
	
	$("#slider_full_items > div").removeClass("slider_full_item_active_last");
	var $slideActive = $("#slider_full_items > div.slider_full_item_active");
	var $slideItems = $("#slider_items > div");
	var $way = 1;

	if ($slideActive.length) {
		$slideActive.addClass("slider_full_item_active_last");
		$way = $slideActive.index() > $index ? -1 : 1;
	}
	$slideActive.removeClass("slider_full_item_active");

	$slideItems.removeClass("slider_item_active");
	$slideItems.eq($index).addClass("slider_item_active");
	
	$slideFullItems.eq($index).addClass("slider_full_item_active");

	_slideAnimate($way, $slideAnimate);
}

function _slideTo($way) {
	var $index = $("#slider_full_items > div.slider_full_item_active").index();
	var $lastFullItem = $("#slider_full_items > div:last").index();
	
	if ($way == "next") {
		$index += 1;
		if ($index > $lastFullItem)
			$index = 0;
	}
	else if ($way == "prev") {
		$index -= 1;
		if ($index < $("#slider_full_items > div:first").index())
			$index = $lastFullItem;
	}
	_slideChange($index);
}

function _slideAnimate($way, $animation) {
	var $slide = $("#slider_full_items > div.slider_full_item_active");
	var $slideLastActive = $("#slider_full_items > div.slider_full_item_active_last");
	
	if ($animation == "flipping") {
		$slideLastActive.css("display","block").animate({"left" : -$slide.outerWidth() * $way}, function(){ $(this).css("display","none"); });
		$slide.css({"left" : $slide.outerWidth() * $way}).animate({"left" : "0px"});
	}

	if ($animation == "opacity") {
		$slideLastActive.css({"display" : "block", "z-index" : "1"}).fadeOut("fast");
		$slide.css({"left" : "0", "z-index" : "0"});
	}
}