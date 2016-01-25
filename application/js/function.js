
setTinyMce = function(objElem, minimalMode, options) {
	if (objElem != null && typeof tinyMCE == 'object') {
		if (objElem.substr(0, 1) == '#') {
			objElem = objElem.replace("#", "");
		}
		if (typeof options != 'undefined' && options != null) {}
		if (minimalMode == null || typeof minimalMode == 'undefined' || minimalMode == false) {
			tinymce.init({
				selector: "textarea",
				theme: "modern",
				plugins: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor tiny_mce_wiris"
				],
				toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
				toolbar2: "print preview | link image media | forecolor backcolor emoticons | tiny_mce_wiris_formulaEditor",
				image_advtab: true
			});
		} else {
			tinyMCE.init({
				mode: "exact",
				elements: objElem,
				theme: "modern",
				theme_advanced_toolbar_location: "top"
			});
		}
	}
	if (typeof tinyMCE != 'object') {
		alert("tidak ditemukan library tiny mce");
	}
}