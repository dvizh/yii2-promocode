if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.promocode = {
    init: function() {
        $(document).on('click', '.promo-code-enter-btn', this.enter);
        $(document).on('change', '.promo-code-enter input', this.enter);
        $(document).on('click', '.promo-code-clear-btn', this.clear);
        
        return true;
    },

    clear: function() {
        var form = $(this).parents('form');
        var data = $(form).serialize();
        data = data+'&clear=1';
        
        jQuery.post($(form).attr('action'), data,
            function(json) {
                if(json.result == 'success') {
                    $(form).find('input[type=text]').css({'border': '1px solid #ccc'}).val('');
                    $(form).find('.promo-code-discount').show('slow').html(json.message);
                    
                    setTimeout(function() { $('.promo-code-discount').hide('slow'); }, 2300);
                    
                    if(json.informer) {
                        $('.dvizh-cart-informer').replaceWith(json.informer);
                    }
                }
                else {
                    $(form).find('input[type=text]').css({'border': '1px solid red'});
                    console.log(json.errors);
                }

                return true;

            }, "json");
            
        return false;
    },
    enter: function() {
        var form = $(this).parents('form');
        var data = $(form).serialize();

        jQuery.post($(form).attr('action'), data,
            function(json) {
                if(json.result == 'success') {
                    $(form).find('input[type=text]').css({'border': '1px solid green'});

                    if(json.informer) {
                        $('.dvizh-cart-informer').replaceWith(json.informer);
                    }
                }
                else {
                    $(form).find('input[type=text]').css({'border': '1px solid red'});
                    console.log(json.errors);
                }
				
				$(document).trigger("promocodeEnter", json.code);
				
                $(form).find('.promo-code-discount').show().html(json.message);

                return true;

            }, "json");
            
        return false;
    }
};

dvizh.promocode.init();