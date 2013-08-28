
M.addModelFns({

drop_menu_list: {
	load: function() {
		var parentModel = this.parentNode,
			list = this;
		// 滑鼠進入父Model，顯示Menu；反之，則隱藏Menu。
		M.addListener( parentModel, {
			mouseenter: function() {
				var className = this.className;
				this.className = [ className, " drop" ].join( "" );
				list.style.display = "block";
			},
			mouseleave: function() {
				var className = this.className;
				this.className = className.replace(/(\s+drop)+(\s+|$)/g, "");
				list.style.display = "none";
			}
		});
	}
	
}
});