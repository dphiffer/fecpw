jQuery(window).ready(function($) {
  
  var editing = false;
  
  // Upon clicking the current revision, show the edit form
  $('a.content').click(function(e) {
    $('form').show();
    $(this).hide();
    $('input.content')[0].select();
    editing = true;
    e.preventDefault();
  });
  
  $('form').submit(function(e) {
    // Only update if there's new content
    if ($('input.content')[0].value == $('a.content').html()) {
      e.preventDefault();
      $('form').hide();
      $('a.content').show();
      editing = false;
    }
  });
  
  // A few keyboard shortcuts
  $(document).keydown(function(e) {
    var id = parseInt($('a.content').attr('id').match(/content-(\d+)/)[1]);
    if (editing && e.which == 27) {
      // ESC key cancels out of an edit window
      $('form').hide();
      $('a.content').show();
      editing = false;
    } else if (!editing && e.which == 37) {
      // Left arrow only works if we're not at the first revision
      if (id != 1) {
        window.location = '?id=' + (id - 1);
      }
    } else if (!editing && e.which == 39) {
      // Right arrow only works if we're not at the newest content
      if (!$('a.content').hasClass('newest')) {
        window.location = '?id=' + (id + 1);
      }
    }
  });
  
  
  
});
