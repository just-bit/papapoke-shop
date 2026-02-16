// Отладка WooPayments
jQuery(document).ready(function($) {
    console.log('WooPayments Debug: DOM ready');

    // Проверяем наличие конфигурации
    if (typeof wcpay_upe_config !== 'undefined') {
        console.log('WooPayments Debug: wcpay_upe_config found', wcpay_upe_config);
    } else {
        console.log('WooPayments Debug: wcpay_upe_config NOT found');
    }

    // Проверяем наличие Stripe
    if (typeof Stripe !== 'undefined') {
        console.log('WooPayments Debug: Stripe found');
    } else {
        console.log('WooPayments Debug: Stripe NOT found');
    }

    // Проверяем элементы WooPayments через 3 секунды
    setTimeout(function() {
        const upeElements = document.querySelectorAll('.wcpay-upe-element');
        console.log('WooPayments Debug: UPE elements found:', upeElements.length);

        upeElements.forEach(function(element, index) {
            console.log('WooPayments Debug: Element', index, element);
            console.log('WooPayments Debug: Element children:', element.children.length);
            console.log('WooPayments Debug: Element innerHTML:', element.innerHTML);

            // Проверяем стили элемента
            const styles = window.getComputedStyle(element);
            console.log('WooPayments Debug: Element', index, 'display:', styles.display);
            console.log('WooPayments Debug: Element', index, 'visibility:', styles.visibility);
            console.log('WooPayments Debug: Element', index, 'height:', styles.height);
            console.log('WooPayments Debug: Element', index, 'width:', styles.width);
        });

        // Проверяем express checkout wrapper
        const expressWrapper = document.querySelector('.wcpay-express-checkout-wrapper');
        if (expressWrapper) {
            console.log('WooPayments Debug: Express checkout wrapper found');
            console.log('WooPayments Debug: Express wrapper innerHTML:', expressWrapper.innerHTML);
        } else {
            console.log('WooPayments Debug: Express checkout wrapper NOT found');
        }

        // Проверяем express checkout элемент
        const expressElement = document.getElementById('wcpay-express-checkout-element');
        if (expressElement) {
            console.log('WooPayments Debug: Express checkout element found');
            const styles = window.getComputedStyle(expressElement);
            console.log('WooPayments Debug: Express element display:', styles.display);
            console.log('WooPayments Debug: Express element visibility:', styles.visibility);
            console.log('WooPayments Debug: Express element innerHTML:', expressElement.innerHTML);
        } else {
            console.log('WooPayments Debug: Express checkout element NOT found');
        }
    }, 3000);

    // Слушаем ошибки JavaScript
    window.addEventListener('error', function(e) {
        if (e.filename && (e.filename.includes('stripe') || e.filename.includes('wcpay'))) {
            console.log('WooPayments Debug: JavaScript error in WooPayments/Stripe:', e.message);
        }
    });

    // Слушаем необработанные promise rejections
    window.addEventListener('unhandledrejection', function(e) {
        if (e.reason && e.reason.message && (e.reason.message.includes('stripe') || e.reason.message.includes('wcpay'))) {
            console.log('WooPayments Debug: Unhandled promise rejection in WooPayments/Stripe:', e.reason.message);
        }
    });
});
