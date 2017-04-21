if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.promocodeCumulative = {

    init: function(){
        $(document).on('change','.promo-code-discount-type',this.cumulative);
        $(document).on('click','.add-cumulative-row',function(e){
            dvizh.promocodeCumulative.addRow(e);
        });

        console.log('promocode_cumulative');

        $(document).on('click','[data-role=remove-row]',function () {

            if(!confirm('Вы действительно хотите удалить этот элемент?')) {
                return false;
            }
            var delete_url = $(this).data('href');
            var block = $(this).closest('.cumulative-row');
            var data = {
                'promocodeId' : dvizh.promocodeCumulative.getUrlVar()["id"],
                'conditionId' : $(this).data('condition')
            };
            dvizh.promocodeCumulative.deleteCondition(delete_url,data,block);
            $(currentBlock).remove();
        });

    },

    cumulativeRowIncrement: 0,

    addRow: function(e) {
        e.preventDefault();
        dvizh.promocodeCumulative.cumulativeRowIncrement++;
        $('.cumulative-block').append($('<div class="cumulative-row form-group"></div>')
            .append('<input class="form-control" name="Conditions[C'+dvizh.promocodeCumulative.cumulativeRowIncrement+'][sumStart]" type="text" placeholder="От"> - ')
            .append(' <input class="form-control" name="Conditions[C'+dvizh.promocodeCumulative.cumulativeRowIncrement+'][sumStop]" type="text"placeholder="До">')
            .append(' <input class="form-control" name="Conditions[C'+dvizh.promocodeCumulative.cumulativeRowIncrement+'][percent]" type="text" style="width: 50px"placeholder="%">')
            .append(' <span class="remove-condition-btn btn glyphicon glyphicon-remove" style="color: red;" data-role="remove-row"></span>')
        );
    },

    removeCondition: function(){
        $(this).closest('.cumulative-row').remove();
    },

    deleteCondition: function (url,data,$block) {
        currentBlock = $block;
        $.ajax({
            type: 'POST',
            url: url,
            data: {data: data},
            success: function (response) {
                if (response.status === 'success') {
                    $(currentBlock).remove();
                }
            },
            fail: function () {
                alert('Что-то пошло не так :\'(');
            }
        });
    },

    cumulative: function(){

        $('#promocode-discount').removeAttr('disabled','disabled').val('');
        $('#promocode-date_elapsed').removeAttr('disabled','disabled').val('');
        $('#promocode-amount').removeAttr('disabled','disabled').val('');
        $('.promocode-cumulative-form').addClass('hidden').fadeOut();

        if  ($(this).val() == 'cumulative') {
            $('.promocode-cumulative-form').removeClass('hidden').fadeIn();
            $('#promocode-discount').attr('disabled','disabled').val('');
            $('#promocode-date_elapsed').attr('disabled','disabled').val('');
            $('#promocode-amount').attr('disabled','disabled').val('');
        }

    },

    onLoad: function(){

        if  ($('#promocode-type').val() == 'cumulative') {
            $('#promocode-discount').attr('disabled','disabled').val('');
            $('#promocode-date_elapsed').attr('disabled','disabled').val('');
            $('#promocode-amount').attr('disabled','disabled').val('');
        }

    },

    getUrlVar: function () {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }
};

dvizh.promocodeCumulative.init();
dvizh.promocodeCumulative.onLoad();