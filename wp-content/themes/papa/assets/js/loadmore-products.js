jQuery(function($){
  var batch = 12;
  var loading = false;

  function checkAndLoad() {
    if(loading) return;

    var $container = $('.menu-sec-container:visible');
    if(!$container.length || $container.data('no-more')) return;

    var $list = $container.find('ul.products');
    if(!$list.length) return;

    var containerBottom = $container.offset().top + $container.outerHeight();
    var windowBottom = $(window).scrollTop() + $(window).height();

    if(windowBottom + 400 >= containerBottom) {
      loadMore($container, $list);
    }
  }

  function loadMore($container, $list) {
    loading = true;

    var $loader = $container.find('.papa-loader');
    if(!$loader.length) {
      $loader = $('<div class="papa-loader"></div>').appendTo($container);
    }
    $loader.show();

    var tab = $container.attr('id');
    var currentPage = parseInt($container.data('current-page') || 1);
    var nextPage = currentPage + 1;

    $.ajax({
      url: papaLoadMore.ajaxurl,
      type: 'POST',
      data: {
        action: 'papa_load_more_products',
        nonce: papaLoadMore.nonce,
        tab: tab,
        page: nextPage
      },
      success: function(html) {
        var $newItems = $(html).filter('li.product');
        
        if($newItems.length > 0) {
          $list.append($newItems);
          $container.data('current-page', nextPage);

          if($.fn.magnificPopup) {
            $('.menu-sec-container .product').magnificPopup('destroy').magnificPopup({
              type: 'inline',
              midClick: true,
              gallery: {enabled: true},
              delegate: 'span.wpb_wl_preview',
              removalDelay: 500,
              callbacks: {
                beforeOpen: function() {
                  this.st.mainClass = this.st.el.attr('data-effect');
                }
              },
              closeOnContentClick: false
            });
          }

          if(window.updateFilters) {
            window.updateFilters();
          }

          if($newItems.length < batch) {
            $container.data('no-more', true);
          } else {
            setTimeout(checkAndLoad, 100);
          }
        } else {
          $container.data('no-more', true);
        }
      },
      error: function() {
        $container.data('no-more', true);
      },
      complete: function() {
        $loader.hide();
        loading = false;
      }
    });
  }

  $(window).on('scroll resize', checkAndLoad);
  
  $('.menu-sec-item, .tabbtn').on('click', function() {
    $('.menu-sec-container').removeData('no-more current-page');
    setTimeout(checkAndLoad, 200);
  });

  $(document).on('click', '.menu-filter li', function() {
    setTimeout(checkAndLoad, 200);
  });

  setTimeout(checkAndLoad, 500);
});
