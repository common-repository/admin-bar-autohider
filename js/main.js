{
	class ADMIN_BAR_AUTOHIDER {
		constructor(_option) {
			this.wpadminbar = null;
			this.wpadmin_hover_targets = null;
			this.getSetWpadminbar();
			if (GLOBAL_admin_bar_autohider_autohide === 'on') {
				document.addEventListener('scroll', () => this.scrollHandler(), false);
			}
		}
		getSetWpadminbar() {
			this.wpadminbar = document.getElementById('wpadminbar');
			if (this.wpadminbar === null) {
				setTimeout(() => this.getSetWpadminbar(), 100);
			} else {
				if (GLOBAL_admin_bar_autohider_pos === 'bottom') this.wpadminbar.classList.add('bottom');else this.wpadminbar.classList.add('top');

				if (GLOBAL_admin_bar_autohider_autohide === 'on') {
					this.wpadminbar.addEventListener('mouseover', () => this.mouseoverHandler(), false);
					this.wpadminbar.addEventListener('mouseout', () => this.mouseoutHandler(), false);
					this.reset();
				}
			}
		}
		reset() {
			this.startTimer();
		}
		startTimer() {
			this.timer = new Timer({
				time: GLOBAL_admin_bar_autohider_autohide_time,
				comp: () => this.hideAdminbar()
			});
			this.timer.start();
		}
		mouseoverHandler() {
			if (this.wpadminbar !== null) {
				this.timer.stop();
			}
		}
		mouseoutHandler() {
			if (this.wpadminbar !== null) {
				this.timer.restart();
			}
		}
		scrollHandler() {
			if (this.wpadminbar !== null) {
				if (this.timer.is_stop !== true) {
					this.timer.restart();
				} else {
					this.showAdminbar();
					this.reset();
				}
			}
		}
		hideAdminbar() {
			if (this.wpadminbar !== null) {
				this.wpadminbar.classList.add('hide');
				// if(GLOBAL_admin_bar_autohider_pos === 'bottom')
				// 	this.wpadminbar.style.bottom = "-33px"
				// else
				// 	this.wpadminbar.style.top = "-33px"
			}
		}
		showAdminbar() {
			if (this.wpadminbar !== null) {
				this.wpadminbar.classList.remove('hide');
				// if(GLOBAL_admin_bar_autohider_pos === 'bottom')
				// 	this.wpadminbar.style.bottom = "0px"
				// else
				// 	this.wpadminbar.style.top = "0px"
			}
		}
		scroll() {}
	}

	class Timer {
		constructor(_option) {
			this.option = Object.assign({
				time: 1000,
				stop: null,
				comp: null,
				reset: null
			}, _option);
			this.is_stop = true;
		}
		start() {
			this.timer_id = setTimeout(() => this.timeout(), this.option.time);
			this.is_stop = false;
		}
		timeout() {
			if (this.option.comp !== null) {
				this.option.comp();
			}
			this.is_stop = true;
			clearTimeout(this.timer_id);
		}
		stop() {
			this.is_stop = true;
			clearTimeout(this.timer_id);
		}
		restart() {
			clearTimeout(this.timer_id);
			this.start();
		}
	}

	new ADMIN_BAR_AUTOHIDER();
}