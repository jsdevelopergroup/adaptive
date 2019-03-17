window.addEventListener('load',mainEvents,false)

function actEvents(e,naviBlock,inputElem,inputText){

        if(e.target.className == 'rand_name') {
           var randName = document.querySelector('.rand_name').innerHTML;
           inputElem.value = randName;

        }else if(e.target.className == 'search-text'){
           inputElem.value = '';
           inputElem.focus();

        }else if(e.target.className == 'block_btn_menu'){
           naviBlock.style.display = 'block';
           document.querySelector('.wrap').style.display = 'block';

        }else if(e.target.className == 'wrap' || e.target.className == 'close_block'){
           naviBlock.style.display = 'none';
           document.querySelector('.wrap').style.display = 'none';

        }else if(e.target.className == 'button-search'){

        }else{
           inputElem.value = inputText;
        }
        return false;

}

function mainEvents(){
    var naviBlock = document.querySelector('.navi');
    var inputText = "Search celebrity, movie, models, cars etc...";
    var inputElem = document.getElementById("search-text");
    var bodyBlock = document.querySelector('body');

    inputElem.value = inputText;
   // naviBlock.style.display = 'block';

    var mobile = (device.tablet() || device.mobile()) ? true : false;

    if(mobile){
       bodyBlock.ontouchend = function(e){
         actEvents(e,naviBlock,inputElem,inputText);
       }
    } else {
       bodyBlock.onclick = function(e){
         actEvents(e,naviBlock,inputElem,inputText);
       }
    }
    return false;
}

function creationBlock(data,products,url_name,name_name,type_load){
   var name_ = name_name.replace(" ","_");
   $.each(data, function(index,data){
   var opac;
   var div_block = '<div class="block_names">';
       div_block += '<span class="item_id"># '+data.object_id+' </span>';
       div_block += '<div class="block_names__inner">';
       div_block += '<div class="block_names__image">';
       if(data.object_adult == 1){
          div_block += '<div class="nature">';
          div_block += '<span class="nature_text">Probability mature content</span>';
          div_block += '</div>';
          opac = 'style="opacity:0.3;"';
       }
       // mature
       /*<div class="nature">
          <span class="nature_text" style="background:#ef7a57;">Probability mature content</span>
       </div>*/
       /*
         url_name = https://idposter.com/Jessica-Alba-posters-and-prints
         site =
         name_name =

       */
       div_block += '<a href="'+url_name+'/'+data.object_id+'_'+name_+'_poster.html">';
       //  div_block += data.object_url;
       div_block += '<img src="'+data.object_url+'"';
       div_block += 'alt="'+name_name+' picture '+data.object_banner+'"';
       div_block += 'title="'+name_name+' poster #'+data.object_id+'"';
       div_block += 'border="0"';
       div_block += 'id=""';
       div_block += 'class="img-shadow" '+opac+'></a></div>';
       div_block += '<div class="prodlinks_block">';
       $.each(products, function(index,products){
         // foreach
         div_block += '<div class="prlist_block">';
         div_block += '<a class="prlist_link"';
         div_block += 'href="'+url_name+'/'+data.object_id+'_'+name_+'_'+products.url_product+'.html"';
         div_block += 'title="'+name_name+' '+products.product+' #'+data.object_id+'">';
         div_block += '<span class="prlist_link__title">'+products.product+'</span>';
         div_block += '</a></div>';
         // endforeach
       });
       div_block += '</div></div>';
       if(type_load == 'scrolling'){
          setTimeout( function() {
             $('.posters_list').append(div_block);
          }, 500);
       } else {
          $('.posters_list').append(div_block);
       }
       //$('.load_content').css({'display':'none'});
   });
}

function creationOfBlocks(start_loader,end_loader,object_parent,sort_by,url_name,name_name,type_load,products,mobile){
      $.ajax({
         url: '/loading/',
      method: 'POST',

        data: {'start_loader':start_loader,'end_loader':end_loader,'object_parent':object_parent,'sort_by':sort_by,'mobile':mobile},
  beforeSend:function(){




      }
      }).done(function(data){



          $('.load_content').css({'bottom':'0px'});
          $('.load_content').css({'z-index':'100'});
          $('.load_content').css({'display':'block'});
          data = jQuery.parseJSON(data);
       if(data.length > 0){
          products=jQuery.parseJSON(products);
          if(products.length > 0){
             $('.load_content').css({'display':'none'});
             creationBlock(data,products,url_name,name_name,type_load);
             var totalItems = $('#total_items').text();
             if(totalItems > 24){
                $('.load_more').css({'display':'block'});
                $('.load_more span').text(totalItems - 24);
             }
           }
       } else {
          $('.load_content').css({'display':'none'});
       }
    });
 }

$(document).ready(function(){



   var scrolling_load=false;
   var starting_load=false;
   var object_parent=$('#object_parent').text();
   var sort_by=$('#sort_by').text();
   // var url_name='https://idposter.com/'+$('#url_name').text().replace("_","-").replace("/","")+'-posters-and-prints';
   var url_name = 'https://adaptive.idposter.com/'+$('#url_name').text().replace("/","");
   //  alert(url_name);
   var name_name=$('#url_name').text().replace("_"," ").replace("/","");
   var win_width = $(window).width();
   var box_width = 1550;
   var start_num = 24;
   var products = $('#products_list').text();
   var mobile = (device.tablet() || device.mobile()) ? true : false;

   start_num = 24;
   start_loader = start_num;
   end_loader = start_loader;

   if(!starting_load){

    

      creationOfBlocks(0,end_loader,object_parent,sort_by,url_name,name_name,'starting',products,mobile);
      starting_load = true;
   }
   // $(window).scroll(function(){
   $('.load_more').click(function(){
          // if($(window).scrollTop() + $(window).height() >= $(document).height() && !scrolling_load){
          // position: fixed;
          scrolling_load=true;
          creationOfBlocks(start_loader,end_loader,object_parent,sort_by,url_name,name_name,'scrolling',products,mobile);
          start_loader += start_num;
          scrolling_load=false;
          // }
   });
});

/*function change_value(e, t) {
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
function change_value(e, t) {
	$(e).attr("value", t).focus(function() {
		$(this).val() != t && "" == $(this).val() || $(this).attr("value", "")
	}).blur(function() {
		"" == $(this).val() && $(this).attr("value", t)
	})
}
$(document).ready(function() {
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
	});
    $(function() {
		change_value("#search-text", "Search celebrity, movie, models, cars etc")
	});
    $(".exp").click(function() {
		var e = $(".exp").text();
		return $("#search-text").val(e), !1
	});
    $(document).on("keyup", function(e) {
		return 27 == e.keyCode && ("" === $("#suggesstion-box").html() && "" === $("#search-text").val() || ($("#search-text").val("").blur(), $("#suggesstion-box").html(""))), !0
	}).on("click", function(e) {
		$(e.target).closest(".search_block").length || "" === $("#suggesstion-box").html() && "" === $("#search-text").val() || ($("#search-text").val("").blur(), $("#suggesstion-box").html(""))
	});
    $(".button-search").click(function() {
		var e = $("#search-text").val();
		"Search celebrity, movie, models, cars etc" != e && "" != e && topsearch(e, a)
	});
});*/
