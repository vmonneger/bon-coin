$('.js-vote').click(function(e) {
    e.preventDefault();

    let direction = $(e.currentTarget).data('direction')
    let userId = $(e.currentTarget).data('user')
    let valueDown = $(e.currentTarget).parent().find('.js-vote-down')
    let valueUp = $(e.currentTarget).parent().find('.js-vote-up')
    let valueTotal = $('body').find('.voteTotal')

    $.ajax({
      url: `/vote/${userId}/${direction}`,
      type: "POST",
      async: false,
      success: function (response) {
        if (!response.error) {
          $('.js-vote').css('fill', 'black');
          $(e.currentTarget).css('fill', '#ff6e14')
          valueUp.text(response.voteUp)
          valueDown.text(response.voteDown)
          valueTotal.text(response.voteTotal)
        }
      }
    })
})
