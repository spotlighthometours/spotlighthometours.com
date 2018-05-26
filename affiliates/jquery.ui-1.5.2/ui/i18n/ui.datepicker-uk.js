= (e.pageY-smp.top)||0;
		var trigger = this._change[a];
		if (!trigger) return false;
		
		// Calculate the attrs that will be change
		var data = trigger.apply(this, [e, dx, dy]), ie6 = $.browser.msie && $.browser.version < 7, csdif = this.sizeDiff;
		
		if (o._aspectRatio || e.shiftKey)
			data = this._updateRatio(data, e);
		
		data = this._respectSize(data, e);
		
		// plugins callbacks need to be called first
		this.propagate("resize", e);
		
		el.css({
			top: this.position.top + "px", left: this.position.left + "px", 
			width: this.size.width + "px", height: this.size.height + "px"
		});
		
		if (!o.helper && o.proportionallyResize)
			this._proportionallyResize();
		
		this._updateCache(data);
		
		// calling the user callback at the end
		this.element.triggerHandler("resize", [e, this.ui()], this.options["resize"]);
		
		return false;
	},
	mouseStop: function(e) {
		
		this.options.resizing = false;
		var o = this.options, num = function(v) { return parseInt(v, 10) || 0; }, self = this;
		
		if(o.helper) {
			var pr = o.proportionallyResize, ista = pr && (/textarea/i).test(pr.get(0).nodeName), 
						soffseth = ista && $.ui.hasScroll(pr.get(0), 'left') /* TODO - jump height */ ? 0 : self.sizeDiff.height,
							soffsetw = ista ? 0 : self.sizeDiff.width;