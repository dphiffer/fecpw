jQuery(window).ready(function($) {
  
  var editing = false;
  
  $('a.content').click(function(e) {
    $('form').show();
    $(this).hide();
    $('input.content')[0].select();
    editing = true;
    e.preventDefault();
  });
  
  $(document).keydown(function(e) {
    var id = parseInt($('a.content').attr('id').match(/content-(\d+)/)[1]);
    if (editing && e.which == 27) {
      $('form').hide();
      $('a.content').show();
    } else if (!editing && e.which == 37) {
      if (id != 1) {
        window.location = '?id=' + (id - 1);
      }
    } else if (!editing && e.which == 39) {
      if (!$('a.content').hasClass('newest')) {
        window.location = '?id=' + (id + 1);
      }
    }
    
  });
});
