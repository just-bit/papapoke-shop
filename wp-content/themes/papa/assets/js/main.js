jQuery(function ($) {

    /* Open menu */

    $('#trigger-overlay').on('click', function () {
        $(".overlay").addClass('open');
    });

    $('#trigger-overlay2').on('click', function () {
        $(".overlay").addClass('open');
    });

    $('.overlay-close').on('click', function () {
        $(".overlay").removeClass('open');
    });

    $('.menu-item').on('click', function () {
        $(".overlay").removeClass('open');
    });

    $('.contact-menu').on('click', function () {
        $(".overlay").removeClass('open');
    });


    /*
    $('.mobile__menu .menu-item-6203').click(function() {
            $('.menu-item-6203 .sub-menu').slideToggle();
            //check if all services menu is open and close it on click
            if($('.menu-item-4043 .sub-menu').css('display') !== 'none') {
              $('.menu-item-4043 .sub-menu').slideToggle();
            }
        });

        $('.mobile__menu .menu-item-4043').click(function() {
            $('.menu-item-4043 .sub-menu').slideToggle();
            //check if expertise menu is open and close it on click
            if($('.menu-item-147 .sub-menu').css('display') !== 'none') {
              $('.menu-item-147 .sub-menu').slideToggle();
            }
        });*/


//menu mobile

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 120) {
            $(".scroll-header").removeClass("show");

        } else {
            $(".scroll-header").addClass("show");
        }
    });
});


/*var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
var currentScrollPos = window.pageYOffset;
if (prevScrollpos > currentScrollPos) {
    document.getElementsByClassName('scroll-header')[0].style.top = "0";
    } else {
    document.getElementsByClassName('scroll-header')[0].style.top = "-140px";
    }
    prevScrollpos = currentScrollPos;
}*/


// homepage tabs
function openCity(evt, cityName) {
    var i, x, tablinks;
    x = document.getElementsByClassName("papabowls");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tabbtn");
    for (i = 0; i < x.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active-red", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active-red";
}


//faq accordeon
var acc = document.getElementsByClassName("single__accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("single__active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}

jQuery(function ($) {

    $('.quantity').on('click', '.quant-plus', function (e) {
        $input = $(this).prev('input.qty');
        var val = parseInt($input.val());
        $input.val(val + 1).change();

        // Обновление чекаута при клике на плюс
        if ($('body').hasClass('woocommerce-checkout')) {
            updateCheckoutOnQuantityChange($input);
        }
    });

    $('.quantity').on('click', '.quant-minus',
        function (e) {
            $input = $(this).next('input.qty');
            var val = parseInt($input.val());
            if (val > 1) {
                $input.val(val - 1).change();

                // Обновление чекаута при клике на минус
                if ($('body').hasClass('woocommerce-checkout')) {
                    updateCheckoutOnQuantityChange($input);
                }
            }
        });

    // Функция обновления чекаута при изменении количества
    function updateCheckoutOnQuantityChange($input) {
        var cart_item_key = $input.attr('name').match(/cart\[([^\]]+)\]/);
        if (cart_item_key && cart_item_key[1]) {
            var quantity = $input.val();

            // AJAX запрос для обновления корзины
            $.ajax({
                type: 'POST',
                url: checkout_quantity_params.ajax_url,
                data: {
                    action: 'update_cart_item_quantity',
                    cart_item_key: cart_item_key[1],
                    quantity: quantity,
                    nonce: checkout_quantity_params.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Обновляем блок с суммами
                        if (response.data.cart_totals) {
                            $('#checkout-bottom__subtotal').html(response.data.cart_totals.subtotal);
                            $('.checkout-bottom__sum .checkout-bottom__sum-item:last-child span:last-child').html(response.data.cart_totals.total);
                        }

                        // Обновляем скрытый список товаров
                        if (response.data.cart_items_html) {
                            $('.checkout-bottom__list').html(response.data.cart_items_html);
                        }

                        // Обновляем счетчик товаров в корзине
                        if (response.data.cart_contents_html) {
                            $('.cart-contents').replaceWith(response.data.cart_contents_html);
                        }

                        // Запускаем стандартное обновление чекаута WooCommerce
                        $(document.body).trigger('update_checkout');
                    }
                },
                error: function() {
                    console.log('Ошибка при обновлении количества товара');
                }
            });
        }
    }


    // Handler for action button
    $('.action-button').on('click', function (e) {

        var target = $(this).data('target');
        var href = $(this).attr('href');

        if (target) {
            var tabs = document.getElementsByClassName("papabowls");
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].style.display = "none";
            }
            var tabButtons = document.getElementsByClassName("tabbtn");
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].className = tabButtons[i].className.replace(" active-red", "");
            }
            document.getElementById(target).style.display = "block";
            var targetButton = $('.menu-sec-button-' + target);
            if (targetButton.length) {
                targetButton.addClass('active-red');
            }
        }

    });

    $('.popup-btn').magnificPopup({
        removalDelay: 300, mainClass: 'mfp-fade', callbacks: {
            beforeOpen: function () {
                $('body').addClass('mfp-open');
            }, afterClose: function () {
                $('body').removeClass('mfp-open');
            }
        }
    });

    $('.popup-content-btn-collect').on('click', function (e) {
        $.magnificPopup.close();
        $('.overlay-close-ico').trigger('click');
    });

    $.extend(true, $.magnificPopup.defaults, {
        fixedContentPos: true,
        fixedBgPos: true
    });

    var isSync = false; // Флаг для предотвращения зацикливания

    // Обработчик для переключения методов оплаты (включая мобильные устройства)
    $(document).on('change', '.checkout-bottom__methods-item input[type="radio"]', function () {
        if (!isSync && $(this).is(':checked')) {
            $('.checkout-bottom__methods-item .method-details').slideUp();
            $(this).closest('.checkout-bottom__methods-item').find('.method-details').slideDown();

            // Синхронизация с основными методами оплаты WooCommerce
            isSync = true;
            var selectedValue = $(this).val();
            $('.wc_payment_methods input[name="payment_method"][value="' + selectedValue + '"]').prop('checked', true).trigger('change');
            isSync = false;
        }
    });

    // Дополнительный обработчик для клика по label на мобильных
    $(document).on('click touchstart', '.checkout-bottom__methods-item label', function (e) {
        e.preventDefault();
        if (!isSync) {
            var $input = $(this).prev('input[type="radio"]');
            if ($input.length && !$input.is(':checked')) {
                $input.prop('checked', true).trigger('change');
            }
        }
    });

    // Синхронизация в обратную сторону - от WooCommerce к кастомным методам
    $(document).on('change', '.wc_payment_methods input[name="payment_method"]', function() {
        if ($(this).is(':checked') && !isSync) {
            isSync = true;
            var selectedValue = $(this).val();
            $('.checkout-bottom__methods input[name="payment_method"][value="' + selectedValue + '"]').prop('checked', true);

            // Обновляем видимость method-details
            $('.checkout-bottom__methods-item .method-details').slideUp();
            $('.checkout-bottom__methods input[name="payment_method"][value="' + selectedValue + '"]')
                .closest('.checkout-bottom__methods-item').find('.method-details').slideDown();
            isSync = false;
        }
    });

    $('.checkout-bottom__btn').on('click', function (e) {
        $('.checkout.woocommerce-checkout button').trigger('click');
    })



    let currentIndex = 0;

    function activateNextDot() {
        document.querySelectorAll('.menu-filter').forEach(menu => {
            const dots = menu.querySelectorAll('li:not(.active)');
            dots.forEach(dot => dot.classList.remove('animate'));
            if (dots[currentIndex]) {
                dots[currentIndex].classList.add('animate');
            }
        });

        const menuFilter = document.querySelector('.menu-filter');
        const totalDots = menuFilter ? menuFilter.children.length : 0;
        currentIndex = (currentIndex + 1) % totalDots;
    }

    setInterval(activateNextDot, 1500);



    // order popup
    if (!sessionStorage.getItem('popupShown')) {
        setTimeout(function() {
            $.magnificPopup.open({
                items: {
                    src: '#order-popup'
                },
                type: 'inline',
                showCloseBtn: true,
                closeOnBgClick: true,
                mainClass: 'mfp-fade',
                removalDelay: 300,
                callbacks: {
                    beforeOpen: function () {
                        $('body').addClass('mfp-open');
                    },
                    open: function() {
                        $('.place-order-btn').on('click', function(e) {
                            e.preventDefault();

                            $.magnificPopup.close();

                            const isHomePage = window.location.pathname === '/' || window.location.pathname.includes('index');

                            if (isHomePage) {
                                $('html, body').animate({
                                    scrollTop: $(".menu-sec").offset().top - 195
                                }, 800);
                            } else {
                                window.location.href = "/#menu";
                            }
                        });
                    },
                    afterClose: function () {
                        $('body').removeClass('mfp-open');
                    }
                }
            });
            sessionStorage.setItem('popupShown', 'true');
        }, 5000);
    }

});

// filters
document.addEventListener('DOMContentLoaded', function() {
    const filterContainers = document.querySelectorAll('.has-filter');

    filterContainers.forEach(container => {
        const menuItems = container.querySelectorAll('.menu-filter li');

        const activeFilters = Array.from(container.querySelectorAll('.menu-filter li.active'))
            .map(item => item.dataset.filter);
        applyFilters(container, activeFilters);

        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('active');
                const activeFilters = Array.from(container.querySelectorAll('.menu-filter li.active'))
                    .map(item => item.dataset.filter);
                applyFilters(container, activeFilters);
            });
        });

        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    const activeFilters = Array.from(container.querySelectorAll('.menu-filter li.active'))
                        .map(item => item.dataset.filter);
                    applyFilters(container, activeFilters);
                }
            });
        });

        const productsList = container.querySelector('.products');
        if (productsList) {
            observer.observe(productsList, {
                childList: true,
                subtree: true
            });
        }
    });

    function applyFilters(container, activeFilters) {
        const products = container.querySelectorAll('.products .product');

        products.forEach(product => {
            const productTags = product.dataset.item;
            product.classList.remove('show-item', 'hide-item');
            if (activeFilters.length === 0) {
                product.classList.add('show-item');
                return;
            }

            if (!productTags) {
                product.classList.add('hide-item');
            } else {
                const tags = productTags.split(';').map(tag => tag.trim());
                const hasMatchingTag = tags.some(tag =>
                    activeFilters.includes(tag)
                );

                if (hasMatchingTag) {
                    product.classList.add('show-item');
                } else {
                    product.classList.add('hide-item');
                }
            }
        });
    }
    window.updateFilters = function() {
        const filterContainers = document.querySelectorAll('.has-filter');
        filterContainers.forEach(container => {
            const activeFilters = Array.from(container.querySelectorAll('.menu-filter li.active'))
                .map(item => item.dataset.filter);
            applyFilters(container, activeFilters);
        });
    };
});
