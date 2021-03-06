function creationBlock(data, products, url_name, name_name, type_load) {
  var name_ = name_name.replace(" ", "_");
  $.each(data, function(index, data) {
    var div_block = '<div class="block_names">';
    var opac;
    div_block += '<span class="item_id"># ' + data.picture_id + ' </span>';
    div_block += '<div class="block_names__inner">';
    div_block += '<div class="block_names__image">';
    if(data.object_adult == 1) {
       div_block += '<div class="nature">';
       div_block += '<span class="nature_text">Probability mature content</span>';
       div_block += '</div>';
       var opac = 'style="opacity:0.3"';
    }
    div_block += '<a href="' + url_name + '/' + data.picture_id + '_' + name_ + '_poster.html">';
    //  div_block += data.object_url;
    div_block += '<img src="' + data.picture_path + '"';
    div_block += 'alt="' + name_name + ' picture ' + data.picture_big + '"';
    div_block += 'title="' + name_name + ' poster #' + data.picture_id + '"';
    div_block += 'border="0"';
    div_block += 'id=""';
    div_block += 'class="img-shadow" ' + opac + '></a></div>';
    div_block += '<div class="prodlinks_block">';
    $.each(products, function(index, products) {
      // foreach
      div_block += '<div class="prlist_block">';
      div_block += '<a class="prlist_link"';
      div_block += 'href="' + url_name + '/' + data.picture_id + '_' + name_ + '_' + products.url_product + '.html"';
      div_block += 'title="' + name_name + ' ' + products.product + ' #' + data.picture_id + '">';
      div_block += '<span class="prlist_link__title">' + products.product + '</span>';
      div_block += '</a></div>';
      // endforeach
    });
    div_block += '</div></div>';
    $('.posters_list').append(div_block);
  });
}

function creationOfBlocks(start_loader, end_loader, object_parent, sort_by, url_name, name_name, type_load, products, mobile) {
  $.ajax({
    url: '/loading/',
    method: 'POST',
    headers: {
      "my-first-header": "first value",
      "my-second-header": "second value"
    },
    data: {
      'start_loader': start_loader,
      'end_loader': end_loader,
      'object_parent': object_parent,
      'sort_by': sort_by,
      'mobile': mobile
    },
    beforeSend: function() {}
  }).done(function(data) {
    $('.load_content').css({
      'bottom': '0px'
    });
    $('.load_content').css({
      'z-index': '100'
    });
    $('.load_content').css({
      'display': 'block'
    });
    
    if (data.length > 0) {
      products = jQuery.parseJSON(products);
      if (products.length > 0) {
        $('.load_content').css({
          'display': 'none'
        });
        
        creationBlock(data, products, url_name, name_name, type_load);
        var totalItems = $('#total_items').text();
        var totalSpan = $('.load_more span').text();
        
        if (totalItems > 24) {
          $('.load_more').css({'display': 'block'});
        if(totalSpan == ''){
           totalItems = totalItems - 24; 
        }else{
           totalItems = totalSpan - 24; 
        }  
          if(totalItems < 0){
             $('.load_more').css({'display': 'none'});
          }else{
             $('.load_more span').text(totalItems);
          }
        }
      }
    } else {
      $('.load_content').css({
        'display': 'none'
      });
    }
  });
}

$(document).ready(function() {
  var projectUrl = $('#site-name').text()+'/';  
  var scrolling_load = false;
  var starting_load = false;
  var object_parent = $('#object_parent').text();
  var sort_by = $('#sort_by').text();
  // var url_name='https://idposter.com/'+$('#url_name').text().replace("_","-").replace("/","")+'-posters-and-prints';
  var url_name = projectUrl + $('#url_name').text().replace("/", "");
  //  alert(url_name);
  var name_name = $('#url_name').text().replace("_", " ").replace("/", "");
  var win_width = $(window).width();
  var box_width = 1550;
  var start_num = 24;
  var products = $('#products_list').text();
  var mobile = (device.tablet() || device.mobile()) ? true : false;

  start_num = 24;
  start_loader = start_num;
  end_loader = start_loader;

  if(!starting_load) {
     creationOfBlocks(0, end_loader, object_parent, sort_by, url_name, name_name, 'starting', products, mobile);
     starting_load = true;
  }
  // $(window).scroll(function(){
  $('.load_more').click(function() {
    // if($(window).scrollTop() + $(window).height() >= $(document).height() && !scrolling_load){
    // position: fixed;
    scrolling_load = true;
    creationOfBlocks(start_loader, end_loader, object_parent, sort_by, url_name, name_name, 'scrolling', products, mobile);
    start_loader += start_num;
    scrolling_load = false;
    // }
  });
});