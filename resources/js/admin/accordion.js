let mainList = $('.main-list')
let listChildren = mainList.find('.list')
let parentList = $('.parent').children('.list')
parentList.each(function () {
    $(this).addClass('childrenList')
})
$('.closed-img').click(function (event) {
    var label = $(this).parent('.label');
    var parent = label.parent('.has-children');
    var list = label.siblings('.list');

    if ( parent.hasClass('is-open') ) {
        list.slideUp('fast');
        parent.removeClass('is-open');
        label.find('.closed-img').removeClass('open')
    }
    else {
        list.slideDown('fast');
        parent.addClass('is-open');
        label.find('.closed-img').addClass('open')
    }
});

$('.uncollapse-all').click(function () {
    $('.list:not(.childrenList)').each(function (){
        $(this).slideDown(50)
    })
    setTimeout(() =>{
        parentList.each(function () {
            $(this).slideDown(300)
        })
    }, 100)

    $('.has-children').each(function () {
        $(this).addClass('is-open')
    })

    $('.closed-img').each(function () {
        $(this).addClass('open')
    })
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
