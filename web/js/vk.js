FastacashVK = (function (VK) {
	function postOnWall(message, recipientUid, callback, attachments) {
		if (!message && !attachments)
			throw new Error("Need at least message or attachments. See http://vk.com/dev/wall.post");

		var options = {
			message: message,
			owner_id: recipientUid
		};

		if (attachments) options.attachments = attachments;

		VK.api('wall.post', options, callback);
	}

	var RequestLink = function (recipientUid, link, callback) {
		function postMessage(message) {
			postOnWall(message, recipientUid, callback);
		}

		this.send = function (message) {
			if (!message) message = '';

			postMessage(message + ' ' + link);
		}

		this.accept = postMessage;
		this.reject = postMessage;
		this.cancel = postMessage;
	};

	var SendExternalLink = function (recipientUid, link, callback) {
		function postMessage(message) {
			postOnWall(message, recipientUid, callback);
		}

		this.send = function (message) {
			if (!message) message = '';

			postMessage(message + ' ' + link);
		}

		this.accept = postMessage;
		this.reject = postMessage;
		this.cancel = postMessage;
	};

	var FastacashVK = {
		'RequestLink': RequestLink,
		'SendExternalLink': SendExternalLink
	};

	FastacashVK.spreadTheLove = function (message, recipientUid, callback, attachments) {
		if (!attachments)
			throw new Error("Need attachments. See http://vk.com/dev/wall.post");

		postOnWall(message, recipientUid, callback, attachments);
	};

	return FastacashVK;
})(VK);