BX.ready(function(){
	BX.addCustomEvent("onImDrawTab", function() {
		if($(".word_highlighter_hint").length==0) {
			var element = document.createElement("div")
			element.className = "word_highlighter_hint"
			$(".side-panel-overlay")[0].append(element)
		}

		$(".bx-messenger-body-wrap").undelegate(".bx-messenger-message u","mouseout")
		$(".bx-messenger-body-wrap").delegate(".bx-messenger-message u","mouseout",function (event) {
			object = $(".word_highlighter_hint")[0]
			object.style.display="none"
		})
		$(".bx-messenger-body-wrap").undelegate(".bx-messenger-message u","mouseover")
		$(".bx-messenger-body-wrap").delegate(".bx-messenger-message u","mouseover",function(event){
			let coordinates = event.target.getBoundingClientRect();
			
			
			var request = BX.ajax.runAction('icodes:chatwordhints.ajax.ajaxhandler.getWords', {
				data: {"word": event.target.textContent}
			});

			request.then(function(response){
				info = response.data.replaceAll('&lt;','<').replaceAll('&gt;','>')
				object = $(".word_highlighter_hint")[0]
				object.innerHTML = info
				object.style.display="block"
				object.style.top= coordinates.y - (object.clientHeight) +"px"
				object.style.left = coordinates.left - (object.clientWidth + 10) +"px"
				console.log('resp' + response);
			});

		})
	})

	BX.addCustomEvent("onPullEvent", function(module_id,command,params) {
		$chatId = BX.MessengerCommon.BXIM.messenger.currentTab;

		$chatId = $chatId.toString().split("chat");
		$chatId = $chatId[$chatId.length-1];
		if (module_id == "icodes.chatwordhints" && command == 'hint' && $chatId == params.CHAT) {

			setTimeout(()=>{BX.MessengerCommon.openDialog(BX.MessengerCommon.BXIM.messenger.currentTab)},500);

		}
	});
	BX.PULL.extendWatch('WORD_HL_CHAT');
});