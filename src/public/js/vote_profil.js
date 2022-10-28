$('.js-vote').click(function(e) {
    e.preventDefault();

    let direction = $(e.currentTarget).data('direction')
    let valueDown = $(e.currentTarget).parent().find('.js-vote-down')
    let valueUp = $(e.currentTarget).parent().find('.js-vote-up')


    $.ajax({
      url: '/vote/3/' + direction,
      type: "POST",
      success: function (response) {
        $('.js-vote').css('fill', 'black');
        $(e.currentTarget).css('fill', '#ff6e14')

        if (!response.error) {
          valueUp.text(response.voteUp)
          valueDown.text(response.voteDown)
        }
      }
    })
})