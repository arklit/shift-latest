

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#search-tree').on('keyup', function () {
    $.ajax({
        method: 'post',
        url: '/ajax/search-tree',
        data: $(this).serialize(),
        success: function (data) {
            $('.list-container').html(data)
            accordion()
            setTimeout(()=>{
                uncollapse()
            }, 300)
        }
    })
})

$(document).ready(function () {
    $.ajax({
        method: 'get',
        url: '/ajax/get-tree',
        success: function (data) {
            $('.list-container').html(data)
            accordion()
        }
    })
})

function accordion() {


    $('.closed-img').click(function (event) {
        var label = $(this).parent('.label');
        var parent = label.parent('.has-children');
        var list = label.siblings('.list');

        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
            label.find('.closed-img').removeClass('open')
        } else {
            list.slideDown('fast');
            parent.addClass('is-open');
            label.find('.closed-img').addClass('open')
        }
    });

    $('.uncollapse-all').click(function () {
        uncollapse()
    })

    $('.collapse-all').click(function () {
        $('.list').each(function () {
            $(this).parent('.has-children').removeClass('is-open')
            $(this).slideUp()
        })
        $('.has-children').each(function () {
            $(this).removeClass('is-open')
        })
        $('.closed-img').each(function () {
            $(this).removeClass('open')
        })
    })

}


function uncollapse() {
    let parentList = $('.parent').children('.list')

    parentList.each(function () {
        $(this).addClass('childrenList')
    })

    $('.list:not(.childrenList)').each(function () {
        $(this).slideDown(50)
    })
    setTimeout(() => {
        parentList.each(function () {
            $(this).slideDown('slow')
        })
    }, 100)

    $('.has-children').each(function () {
        $(this).addClass('is-open')
    })

    $('.closed-img').each(function () {
        $(this).addClass('open')
    })
}
