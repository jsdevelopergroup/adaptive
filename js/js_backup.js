window.addEventListener('load',listClicks,false);

function listClicks(){
    var closeBtn = document.querySelector('.close_block');
    var naviBlock = document.querySelector('.navi');
    var btnMenu = document.querySelector('.block_btn_menu');
    closeBtn.onclick = function(e){
        naviBlock.style.display = (naviBlock.style.display == 'none') ? 'block' : 'none';
    }
    btnMenu.onclick = function(e){
        naviBlock.style.display = (naviBlock.style.display == 'none') ? 'block' : 'none';
    }    
}




function change_value(e, t) {
	$(e).attr("value", t).focus(function() {
		$(this).val() != t && "" == $(this).val() || $(this).attr("value", "")
	}).blur(function() {
		"" == $(this).val() && $(this).attr("value", t)
	})
}

function topsearch(e, t) {
	var n = t + "/?query=" + $.trim(e).replace(/ /g, "+");
	document.location.replace(n)
}

function selectCountry(e, t) {
	$("#search-text").val(e), $("#suggesstion-box").hide(), topsearch(e, t)
}

function show(e) {
	document.getElementById("search-inp").value = "Search celebrity, models, actors, movies, cars...", document.getElementById("window").style.display = e, document.getElementById("wrap").style.display = e
}

function request(e, t, n) {
	var a = new XMLHttpRequest,
		o = n + "=" + encodeURIComponent(t);
	a.open("POST", "/loading/", !0), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.send(o), a.onreadystatechange = function() {
		4 == a.readyState && 200 == a.status && (document.getElementById(e).innerHTML = a.responseText)
	}
}

function submitImage(e) {
	request("groud", e + "-" + document.getElementById("sel_image").innerHTML, "sub_image"), show("none")
}

function setAttrib() {
	var e = document.getElementById("n_image").getAttribute("src");
	document.getElementById("img_path").value = e, document.getElementById("cart_form").submit()
}

function clickImage(e) {
	var t = e.src;
	e.height, e.width;
	request("sel_image", t, "sel_image");
	var n = document.getElementById("img_block").childNodes;
	for(i = 0; i < n.length; i++) n[i].childNodes[1].style.opacity = "1";
	document.getElementById(e.id).style.opacity = "0.5"
}

function checkValues(e) {
	var t = new XMLHttpRequest,
		n = document.getElementById(e.id),
		a = e.getAttribute("data-f"),
		o = "_None",
		c = e.id.slice(1);
	n.checked && (o = "_Check");
	var i = "string=" + encodeURIComponent(a + e.id + o);
	t.open("POST", "/loading/", !0), t.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), t.setRequestHeader("X-Requested-With", "XMLHttpRequest"), t.send(i), t.onreadystatechange = function() {
		4 == t.readyState && 200 == t.status && ("A" == t.responseText ? (document.getElementById("img_" + c).style.opacity = "", document.getElementById("imgmat_" + c).innerHTML = "", document.getElementById("imgmat_" + c).style.background = "") : "M" == t.responseText ? (document.getElementById("img_" + c).style.color = "white", document.getElementById("img_" + c).style.opacity = "0.3", document.getElementById("imgmat_" + c).innerHTML = "Probability mature content", document.getElementById("imgmat_" + c).style.background = "#ef7a57") : (document.getElementById("img_" + c).style.opacity = "0.3", document.getElementById("img_" + c).style.color = "white", document.getElementById("imgmat_" + c).style.background = "#393331", document.getElementById("imgmat_" + c).innerHTML = "Deleted"))
	}
}

function setAts(e) {
	document.getElementById("grays").value = e
}
window.onload = function() {
	$("#checkgray").click(function() {
		jQuery("#checkgray").is(":checked") ? (grayscale($("#bigimage")), setAts("gray")) : (grayscale.reset($("#bigimage")), setAts("none"))
	})
}, $(document).ready(function() {
	function e(e, t) {
		$.ajax({
			type: "POST",
			url: "/loading/",
			data: "record=true&pay=" + e + "&dev=" + t,
			success: function(t) {
				"paypal" == e && $("#payform").submit(), "ccnow" == e && $("#ccnow").submit()
			}
		})
	}

	function t(e) {
		var t = $("#radioGender").val(),
			n = $("#radioColor").val(),
			a = n + "+" + t;
		return $("#sexgroud").css("backgroundImage", "url(" + e + "/images/" + a + ".png)"), "Unisex" == t && "Black" == n ? ($("#price_tab").html("$50.99"), $("input[name='price']").val("50.99")) : "Unisex" == t && "White" == n ? ($("#price_tab").html("$24.99"), $("input[name='price']").val("24.99")) : "Black" == n ? ($("#price_tab").html("$41.99"), $("input[name='price']").val("41.99")) : ($("#price_tab").html("$20.99"), $("input[name='price']").val("20.99")), !1
	}

	function n() {
		var e = document.getElementById("poster_papers"),
			t = e.options[e.selectedIndex].getAttribute("data-price");
		document.getElementById("price_tab").innerHTML = t
	}
	var a = "https://idposter.com";
	$("a#delete").click(function() {
		var e = $(this).attr("i");
		$.ajax({
			type: "POST",
			url: "/loading/",
			data: "inc=" + e,
			success: function(e) {
				window.location = a + "/shopping-cart/"
			}
		})
	}), $("a#update").click(function() {
		var e = $(this).attr("i"),
			t = $("input#" + e).val();
		$.ajax({
			type: "POST",
			url: "/loading/",
			data: "inc=" + e + "&val=" + t,
			success: function(e) {
				window.location = a + "/shopping-cart/"
			}
		})
	}), $("a#checkout").click(function() {
		e($("input[name=pay]:checked", "#payform").val(), $("#device").attr("data"))
	}), $("#radioGender").change(function() {
		t(a)
	}), $("#radioColor").change(function() {
		t(a)
	}), $(function() {
		$("#image").change(function() {
			$("#load_box").html('<img src="/images/ajax-load.gif">'), $("#imageform").ajaxForm({
				target: "#load_box"
			}).submit()
		})
	}), $("#poster_sizes").change(function() {
		var e = $("#poster_sizes").val(),
			t = $("input[name='product']").val();
		$.ajax({
			type: "POST",
			url: "/getdata/",
			data: "poster_sizes=" + e + "&product=" + t,
			success: function(e) {
				$("#poster_papers").html(e), n()
			}
		})
	}), $("#poster_papers").change(function() {
		n()
	}), $("a.create-products").mouseover(function() {}), $("input[name='types']", $("#radio_types")).change(function(e) {
		"2d" == $("input[name='types']:checked").val() ? ($("#radio_color").css("display", "block"), $(".color_title").css("display", "block")) : ($("#radio_color").css("display", "none"), $(".color_title").css("display", "none"))
	});
	var o = $("#home-text").html();
	$("#result-data").html(o), $("#search-text").on("keyup", function(e) {
		return 13 == e.keyCode ? ($(".button-search").click(), !1) : 27 == e.keyCode ? ($("#search-text").val("").blur(), $("#suggesstion-box").html(""), !1) : void $.ajax({
			type: "POST",
			url: "/loading/",
			data: "keyword=" + $(this).val() + "&site_name=" + a,
			beforeSend: function() {
				$("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px")
			},
			success: function(e) {
				$("#suggesstion-box").show(), $("#suggesstion-box").html(e), $("#search-box").css("background", "#FFF")
			}
		})
	}), $(function() {
		change_value("#search-text", "Search celebrity, movie, models, cars etc")
	}), $(".exp").click(function() {
		var e = $(".exp").text();
		return $("#search-text").val(e), !1
	}), $(document).on("keyup", function(e) {
		return 27 == e.keyCode && ("" === $("#suggesstion-box").html() && "" === $("#search-text").val() || ($("#search-text").val("").blur(), $("#suggesstion-box").html(""))), !0
	}).on("click", function(e) {
		$(e.target).closest(".search_block").length || "" === $("#suggesstion-box").html() && "" === $("#search-text").val() || ($("#search-text").val("").blur(), $("#suggesstion-box").html(""))
	}), $(".button-search").click(function() {
		var e = $("#search-text").val();
		"Search celebrity, movie, models, cars etc" != e && "" != e && topsearch(e, a)
	})
});