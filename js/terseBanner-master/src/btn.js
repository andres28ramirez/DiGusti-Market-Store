
	/**
	 * 指示按钮
	 */
	Banner.prototype.btn = function() {
		var s = this,
			o = this.option,
			$banner = this.$banner,
			$btn;

		for (var i = 0, item = ''; i < s.len; i++) {
			item += '<a></a>';
		}
		$banner.append($('<div class="tb-btn"/>').append(item));

		s.$btn = $btn = $('.tb-btn a', $banner);

		$btn.first().addClass('active');
		$btn.parent().css({
			left: '50%',
			marginLeft: -($btn.outerWidth(true) * $btn.length / 2)
		});

		if (!Util.IS_MOBILE) {
			$btn.on('click.terseBanner', function() {
				if (s.isAnimated) return;

				o.before.call(s, s.$banner, s.$item, s.currentIndex);
				s.currentIndex = $(this).index();
				s.play();
			});
		}
	};
