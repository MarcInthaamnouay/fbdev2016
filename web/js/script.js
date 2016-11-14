$(document).ready(function(){
	$("i.formCaptcha").click(function(){
		$("iframe.formCaptcha").attr("src",$("iframe.formCaptcha").attr("src") );
	})
})